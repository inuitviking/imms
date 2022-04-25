<?php
namespace Angus\Imms\php\classes;
require_once Bootstrapper::RootDirectory() . '/vendor/autoload.php';

?><!DOCTYPE html>

<html lang="en">
	<head>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/hack-font@3/build/web/hack.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="/assets/js/main.js"></script>

		<link rel="stylesheet" href="/assets/css/dark.min.css">
		<script src="/assets/js/highlight.min.js"></script>
		<script>
			import hljs from "../js/highlight.min";
			hljs.highlightAll();

			function hideResult() {
				document.getElementById("search-result").style.display = 'none';
			}
		</script>
	</head>
	<body>
		<header>
			<div class="searchbar">
				<label for="search" style="display: none;">Search</label><input type="text" id="search" placeholder="Search" onBlur="hideResult()"/>
				<div id="search-result"></div>
			</div>
		</header>

		<div class="sidebar">
			<nav>
				<?php Route::CreateMenu(Bootstrapper::RootDirectory() . "/src/documents/"); ?>
			</nav>
		</div>

		<main>