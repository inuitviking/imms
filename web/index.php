<?php

require_once '../vendor/autoload.php';
require_once '../src/php/classes/Route.php';

$route = new \Angus\Ivmdcms\php\classes\Route();

//use League\CommonMark\CommonMarkConverter;
//
//$converter = new CommonMarkConverter([
//	'html_input' => 'strip',
//	'allow_unsafe_links' => false,
//]);
//
//$testMD = file_get_contents('../src/documents/test.md');
//
//echo $converter->convert($testMD);
//
//// <h1>Hello World!</h1>