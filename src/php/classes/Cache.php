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

		if (file_exists($file)) {

			$HTMLFile = basename($file);
			$HTMLFile = str_replace(".md", ".html", $HTMLFile);

			$folderStructure = str_replace($this->mdPath, "", $file);
			$folderStructure = substr($folderStructure, 0, strrpos($folderStructure, '/'));

			if (strlen($folderStructure) > 0) {
				if (!is_dir($this->htmlPath . $folderStructure)) {
					mkdir($this->htmlPath . $folderStructure);
				}
				$folderStructure .= '/';
			}

			$HTMLFile = $folderStructure . $HTMLFile;

			file_put_contents($this->htmlPath . $HTMLFile, $converter->convert(file_get_contents($file)));
		}else {
			echo "File '$file' doesn't exist.";
		}
	}

	public function BulkMD2HTML () {

	}

}