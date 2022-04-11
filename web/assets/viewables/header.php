<?php
namespace Angus\Ivmdcms\php\classes;
require_once Bootstrapper::RootDirectory() . '/vendor/autoload.php';
?><!DOCTYPE HTML>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/css/main.css" />
	</head>
	<body>
		<div class="sidebar">
			<nav>
				<?php Route::CreateMenu(Bootstrapper::RootDirectory() . "/src/documents/"); ?>
			</nav>
		</div>
