#!/usr/bin/php
<?php

require_once '../vendor/autoload.php';

use Angus\Ivmdcms\php\classes\Cache;

$shortopts = "c:";  // Required value
$shortopts .= "q"; // These options do not accept values

$longopts  = array(
	"clear-cache:",		// Required value
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
	if (isset($options['clear-cache']) || isset($options['c'])) {
		$cc = "";
		if (isset($options['clear-cache'])) {
			$cc = $options['clear-cache'];
		} elseif (isset($options['c'])) {
			$cc = $options['c'];
		}

		$cache = new Cache();
		if ($cc === "all") {
			$cache->ClearCache('../web/assets/cache/');
			printQuiet("All cache cleared.", $options);
		} else {
			if ($cc !== "") {
				$cache->ClearCacheSingularURL($cc, true);
				printQuiet("Cache cleared for $cc");
			}
		}
	}
}