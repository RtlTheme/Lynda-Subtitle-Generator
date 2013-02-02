<?php
/**
 * Lynda Subtitle Generator - PHP application
 * https://github.com/qolami/Lynda-Subtitle-Generator
 * Copyright 2013 Hashem Qolami <hashem@qolami.com>
 * Version 0.6.1
 * Released under the MIT and GPL licenses.
*/

# Path to subtitle folder
define('DIR', './subtitle');

# App version
$version = '0.6.1';

# Custom output
function e($msg, $err=FALSE)
{
	global $version;
	include 'inc/result.php';
	exit;
}

# Get transcript url
$url = $_GET['url'] or e('Insert a URL to grab transcript.', TRUE);

# No time limit
set_time_limit(0);

# Load HTML DOM library
include 'lib/simple_html_dom.php';


function get_path($url)
{
	if ( @preg_match('#^https?://(.*)#i', $url, $param) ) {
		$param = explode('/', $param[1]);
		return rtrim(DIR, '/')."/$param[1]-$param[2]";

	} else { # local address
		$param = end(explode('/', $url));
		return rtrim(DIR, '/').'/'.basename($param, strrchr($param, '.'));
	}
}

function str_pure($str)
{
	return trim(str_replace(array('\\','/',':','*','?','"','<','>','|'), '', $str));
}

function to_dir($path)
{
	# Make directory if not exists. set permission to 0777.
	is_dir($path) and @chmod($path, 0777) or mkdir($path, 0777, true);
	return $path;
}

function to_srt($data, $path, $title)
{
	if (function_exists('mb_convert_encoding')) {
		$data = mb_convert_encoding($data, 'UTF-8', 'HTML-ENTITIES');
	}
	
	file_put_contents(rtrim($path, '/').'/'.$title.'.srt', $data) or e('Unable to write the data.', TRUE);

	# Change permission of folder according to security issues.
	@chmod($path, 0755);
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

	$dir = to_dir( $path .'/'. str_pure($chapter) );

	$j = 1;
	foreach ($sections as $section) {
		$num = $j<10?"0$j":$j;
		$title = "$num. ".$section->find('a', 0)->plaintext;
		$rows = $section->find('td.tC');
		$sub = '';

		for ($i = 0; $i < count($rows)-1;) {
			$start = $rows[$i]->plaintext;
			$end = $rows[$i+1]->plaintext;
			$text = $rows[$i]->next_sibling()->plaintext;
			$i++;
			$sub .= "$i\n\r
00:{$start},000 --> 00:{$end},000\n\r
{$text}\n\r\n\r\n\r";
		}

		to_srt( $sub, $dir, str_pure($title) );
		$j++;
	}
}

############################################################
######                   Controller                   ######
############################################################

# Make an instance
$html = new simple_html_dom();

# Load the DOM
$html->load_file($url);

$chs = $html->find('td.tChap');

# Course path
$cpath = get_path($url);

for ($i=0; $i<count($chs); $i++) {
	process_chapter($chs[$i], $cpath, $i);
}

# Clear DOM object
$html->clear();

# Free memory
unset($html);

e("Subtitles have been generated successfully!<br>Located at: <strong>$cpath</strong>");
?>