<?php
/**
 * Lynda Subtitle Generator - PHP application
 * https://github.com/qolami/Lynda-Subtitle-Generator
 * Copyright 2013 Hashem Qolami <hashem@qolami.com>
 * Version 0.9.0
 * Released under the MIT and GPL licenses.
 */

# App version
$version = '0.9.0';

if (! isset($_GET['url'])) {
	include 'inc/view.php';
	exit;
}

# Path to subtitle folder
define('DIR', 'subtitles');

# File lifetime
define('FILE_LIFETIME', 7*24*3600);


# Custom output
function e($msg, $err=FALSE)
{
	global $api;
	if ($api == TRUE) {
		$key = !!$err ? 'error' : 'success';
		$data[$key] = $msg;
		header('Content-Type: application/json');
		echo json_encode($data);
	} else {
		header('Content-Type: text/plain');
		echo $msg;
	}
	exit;
}

# Get transcript url
$url = $_GET['url'] or e('Insert a URL to generate subtitles', TRUE);

# API
$api = isset($_GET['api']) ? !!$_GET['api'] : FALSE;

# No time limit
set_time_limit(0);
@ini_set("max_execution_time", 0);

# Load library
include 'lib/simple_html_dom.php';


function get_transcript($url)
{
	$url = urldecode(trim($url));
	$pattern[0]		= "#^(.+).html$#i";
	$replacement[0]	= "$1/transcript";

	$pattern[1]		= "#^(.+)(\d)/?$#i";
	$replacement[1]	= "$1$2/transcript";

	$pattern[2]		= "#^(.+)(/transcript)$#i";
	$replacement[2]	= "$1$2";

	return preg_replace($pattern, $replacement, $url);
}

function get_path($url)
{
	$arr = array('root' => rtrim(DIR, '/'));

	if ( @preg_match('#^https?://(www.)?lynda.com(.+)/transcript$#i', $url, $param) ) {
		$param = explode('/', $param[2]);
		$arr['course'] = trim($param[1], '-')."-$param[2]";

	} else {
		// # local address
		// $param = end(explode('/', $url));
		// $arr['course'] = basename($param, strrchr($param, '.'));
		return FALSE;
	}

	$arr['full'] = $arr['root'].'/'.$arr['course'];
	return $arr;
}

function get_url_content($url)
{
	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
	} else {
		$output = @file_get_contents($url) or FALSE;
	}
	return $output;
}

function str_pure($str)
{
	return trim(str_replace(array('\\','/',':','*','?','"','<','>','|'), '', $str));
}

function to_srt($data, $path, $title)
{
	global $zip;

	if (function_exists('mb_convert_encoding')) {
		$data = mb_convert_encoding($data, 'UTF-8', 'HTML-ENTITIES');
	}
	
	$zip->addFromString($path.'/'.$title.'.srt', $data);
}

function parse_chapter_name($ch, $i)
{
	$i = $i<10?"0$i":$i;
	return "$i. " . preg_replace("/^\d+\. /", '', $ch);
}

function process_chapter($e, $path, $chno)
{
	$chapter = $e->find('span.chTitle', 0)->plaintext;
	$sections = $e->find('tr.showToggleDeltails');

	$chapter = parse_chapter_name($chapter, $chno);

	$dir = $path .'/'. str_pure($chapter);

	$j = 1;
	foreach ($sections as $section) {
		$num = $j<10?"0$j":$j;
		$title = "$num. ".$section->find('a', 0)->plaintext;
		$rows = $section->find('td.tC');
		$sub = '';

		for ($i = 0; $i < count($rows)-1;) {
			$start = $rows[$i]->plaintext;
			$end = $rows[$i+1]->plaintext;
			$text = trim($rows[$i]->next_sibling()->plaintext);
			$i++;
			$sub .= "$i
00:{$start},000 --> 00:{$end},000
{$text}

";
		}
		
		to_srt( $sub, $dir, str_pure($title) );
		$j++;
	}
}

function get_file_address($filename)
{
	$pos = strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING']);
	$addr = substr($_SERVER['REQUEST_URI'], 0, $pos - 1);
	if ($pos = strrpos($addr, '.php')) {
		$addr = substr($addr, 0, strrpos($addr, basename($addr)));
	}
	return $addr . $filename;
}

############################################################
######                   Controller                   ######
############################################################

# Make instances
$html = new simple_html_dom;
$zip = new ZipArchive;

# Transcript path
$url = get_transcript($url);

# Course path
$path = get_path($url) or e("Unable to fetch transcript from: <strong><i>$url</i></strong>", TRUE);

$zip_file = $path['full'].'.zip';

# Check file: existence and lifetime
if (file_exists($zip_file) && time() - filemtime($zip_file) < FILE_LIFETIME) {
	e(get_file_address($zip_file));
}

# Get URL content
$content = get_url_content($url) or e("Unable to load data from: <strong><i>$url</i></strong>", TRUE);

# Load the DOM
$html->load($content);

$chs = $html->find('td.tChap') or e("Unable to find chapters on: <strong><i>$url</i></strong>", TRUE);

if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {

	for ($i=0; $i<count($chs); $i++) {
		process_chapter($chs[$i], $path['course'], $i);
	}

	$zip->close();

	$output = array(
		'data'	=> get_file_address($zip_file), 
		'err'	=> FALSE
	);

} else {
	$output = array(
		'data'	=> 'Zip compression failed!', 
		'err'	=> TRUE
	);
}

# Clear DOM object
$html->clear();

# Free memory
unset($html);

e($output['data'], $output['err']);