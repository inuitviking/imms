<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';

use JetBrains\PhpStorm\Pure;
use League\CommonMark\CommonMarkConverter;
use ScssPhp\ScssPhp\Compiler;

class Cache {

	private string $mdPath = '../src/documents/';
	private string $htmlPath = 'assets/cache/';
	private array|false $ini;


	/**
	 * The Cache class takes care of rendering markdown into HTML.
	 * This is mostly used to pre-render the markdown and store it to a cache directory for faster load times.
	 * There are also methods for clearing caches.
	 */
	#[Pure(true)] public function __construct () {
		$this->ini = parse_ini_file(Bootstrapper::RootDirectory() . '/config/config.ini');
	}

	/**
	 * Renders a single markdown file into HTML.
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
			$folderStructure = str_replace(Bootstrapper::RootDirectory() . $this->ini['app_md_path'], "", $file);
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
			file_put_contents(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $HTMLFile, $converter->convert(file_get_contents($file)));
		}else {
			echo "File '$file' doesn't exist.";
		}
	}

	/**
	 * Clears the HTML cache for a single URL path (usually just a single HTML file).
	 * Example:
	 * The url is example.com/php/functions/file_exists, the "urlPath" will then be "php/functions/file_exists".
	 * @param $URLPath
	 * @param bool $cli
	 * @return void
	 */
	public function ClearCacheSingularURL ($URLPath, bool $cli = false) {

		if ($cli) {
			if(file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $URLPath . '.html')){
				unlink(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $URLPath . '.html');
			}
		}

		if(file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $URLPath . '.html')){
			unlink(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $URLPath . '.html');
		}
	}

	/**
	 * Clears the entire cache. Markdown will be rendered on first load.
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