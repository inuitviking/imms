#!/usr/bin/php
<?php

require_once '../vendor/autoload.php';

use Angus\Ivmdcms\php\classes\Cache;

$shortopts = "c:";  // Required value
$shortopts .= "q"; // These options do not accept values

$longopts  = array(
	"cc:",		// Required value
	"quiet",	// No value
);
$options = getopt($shortopts, $longopts);

function printQuiet ($text, $q = null) {
	if ($q != null) {
		if (isset($q['q']) || isset($q['quiet'])) {
			exit;
		} else {
			echo $text."\n";
		}
	}
}

if (!empty($options)) {
	if (isset($options['cc']) || isset($options['c'])) {
		$cache = new Cache();
		printQuiet("Clearing cache.", $options);
		$cache->ClearCache('../web/assets/cache/');
		printQuiet("Cache cleared.", $options);
	}
}