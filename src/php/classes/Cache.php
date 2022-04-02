<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
use ScssPhp\ScssPhp\Compiler;

class Cache {

	private string $mdPath = '../src/documents/';
	private string $htmlPath = 'assets/cache/';

	public function MD2HTML ($file, $settings = null) {

		// Prepare the markdown converter
		$converter = new CommonMarkConverter([
			'html_input' => 'strip',
			'allow_unsafe_links' => false,
		]);

		file_put_contents($this->htmlPath . '.html', $converter->convert(file_get_contents($this->mdPath . $file . '.md')));
	}

	public function BulkMD2HTML () {

	}

}