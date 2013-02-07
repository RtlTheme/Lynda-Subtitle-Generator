<?php
/**
 * Lynda Subtitle Generator - PHP application
 * https://github.com/qolami/Lynda-Subtitle-Generator
 * Copyright 2013 Hashem Qolami <hashem@qolami.com>
 * Version 0.7.0
 * Released under the MIT and GPL licenses.
*/

# Path to subtitle folder
define('DIR', 'subtitles');

# App version
$version = '0.7.0';

# Custom output
function e($msg, $err=FALSE)
{
	global $version;
	include 'inc/result.php';
	exit;
}

# Get transcript url
$url = $_GET['url'] or e('Insert a URL to grab transcript.', TRUE);

// # API
// $api = !!$_GET['api'] or FALSE;

// # No time limit
set_time_limit(0);
@ini_set("max_execution_time", 0);

// # Load libraries
include 'lib/simple_html_dom.php';
include 'lib/CreateZipFile.inc.php';


function get_path($url)
{
	$arr = array('root' => rtrim(DIR, '/'));

	if ( @preg_match('#^https?://(.*)#i', $url, $param) ) {
		$param = explode('/', $param[1]);
		$arr['course'] = "$param[1]-$param[2]";

	} else { # local address
		$param = end(explode('/', $url));
		$arr['course'] = basename($param, strrchr($param, '.'));
	}
	$arr['full'] = $arr['root'].'/'.$arr['course'];
	return $arr;
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
	
	$zip->addFile($data, rtrim($path, '/').'/'.$title.'.srt');
}

function parse_chapter_name($ch, $i)
{
	$i = $i<10?"0$i":$i;
	return "$i. " . preg_replace("/^\d+\. /", '', $ch);
}

function process_chapter($e, $path, $chno)
{
	global $zip;

	$chapter = $e->find('span.chTitle', 0)->plaintext;
	$sections = $e->find('tr.showToggleDeltails');

	$chapter = parse_chapter_name($chapter, $chno);

	$dir = $path .'/'. str_pure($chapter);
	$zip->addDirectory($dir);

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
		return substr($_SERVER['REQUEST_URI'], 0, $pos - 1) . $filename;
}

############################################################
######                   Controller                   ######
############################################################

# Make instances
$html = new simple_html_dom();
$zip = new CreateZipFile;

# Load the DOM
$html->load_file($url);

$chs = $html->find('td.tChap') or e("Unable to find chapters on: <strong><i>$url</i></strong>", TRUE);

# Course path
$path = get_path($url);

for ($i=0; $i<count($chs); $i++) {
	process_chapter($chs[$i], $path['course'], $i);
}

# Clear DOM object
$html->clear();

# Free memory
unset($html);

$zip_file = $path['full'].'.zip';

# Write to file, force binary mode
$file = fopen($zip_file, 'wb');
$out = fwrite($file, $zip->getZippedfile());
fclose($file);

// $zip->forceDownload($zip_file);
// @unlink($zip_file);

$link = get_file_address($zip_file);
e("Subtitles have been generated successfully!<br>Located at: <a href='$link'><strong>$zip_file</strong></a>");