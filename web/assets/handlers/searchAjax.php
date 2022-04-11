<?php

// A couple of usings
use Angus\Ivmdcms\php\classes\Bootstrapper;
use Angus\Ivmdcms\php\classes\Helpers;

// I have no idea why this is needed
require_once '../../../src/php/classes/Bootstrapper.php';

// But when the above line is in, everything works.
require_once Bootstrapper::RootDirectory() . '/vendor/autoload.php';
Helpers::SearchAjax($_POST['search']);