<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
use ScssPhp\ScssPhp\Compiler;

class Cache {

	private string $mdPath = '../src/documents/';
	private string $htmlPath = 'assets/cache/';


	/**
	 * Generates HTML file for a single MD file.
	 * @param $file
	 * @param $settings
	 * @return void
	 */
	public function MD2HTML ($file, $settings = null) {
		// Prepare the markdown converter
		$converter = new CommonMarkConverter([
			'html_input' => 'strip',
			'allow_unsafe_links' => false,
		]);

		// If the file exists
		if (file_exists($file)) {
			// Fetch folder structure
			$folderStructure = str_replace($this->mdPath, "", $file);
			$folderStructure = substr($folderStructure, 0, strrpos($folderStructure, '/'));

			if (strlen($folderStructure) > 0) {
				if (!is_dir($this->htmlPath . $folderStructure)) {
					mkdir($this->htmlPath . $folderStructure);
				}
				$folderStructure .= '/';
			}

			// Generate path to HTML file
			$HTMLFile = basename($file);
			$HTMLFile = str_replace(".md", ".html", $HTMLFile);
			$HTMLFile = $folderStructure . $HTMLFile;

			// Create HTML file.
			file_put_contents($this->htmlPath . $HTMLFile, $converter->convert(file_get_contents($file)));
		}else {
			echo "File '$file' doesn't exist.";
		}
	}

	/**
	 * Clears the HTML cache for a single URL path (usually just a single HTML file)
	 * @param $URLPath
	 * @return void
	 */
	public function ClearCacheSingularURL ($URLPath) {
		if(file_exists($this->htmlPath . $URLPath . '.html')){
			unlink($this->htmlPath . $URLPath . '.html');
		}
	}

	/**
	 * @param $cachePath
	 * @return void
	 */
	public function ClearCache ($cachePath) {
		$files = glob($cachePath.'*');
		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
			}
			if(is_dir($file)){
				array_map("unlink", glob("$file/*"));
				array_map("rmdir", glob("$file/*"));
				rmdir($file);
			}
		}
	}

}