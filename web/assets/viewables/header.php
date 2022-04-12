<?php
namespace Angus\Ivmdcms\php\classes;
require_once Bootstrapper::RootDirectory() . '/vendor/autoload.php';

?><!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/hack-font@3/build/web/hack.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="/assets/js/main.js"></script>
	</head>
	<body>
		<header>
			<div class="searchbar">
				<input type="text" id="search" placeholder="Search" />
				<div id="search-result"></div>
			</div>
		</header>

		<div class="sidebar">
			<nav>
				<?php Route::CreateMenu(Bootstrapper::RootDirectory() . "/src/documents/"); ?>
			</nav>
		</div>

		<main>