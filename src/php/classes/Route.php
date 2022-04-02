<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
use ScssPhp\ScssPhp\Compiler;

class Route {
	/**
	 * @var false|int|string
	 */
	private bool|int|string $pathExists;
	/**
	 * @var array
	 */
	private array $elements;
	/**
	 * @var string
	 */
	private string $path;
	/**
	 * @var array
	 */
	private array $paths;
	/**
	 * @var array
	 */
	private array $titles;
	private string $defaultView;

	public function __construct(){
		// Select a default view
		$this->defaultView		= '../src/documents/index';

		// Fetch current path
		$this->path				= ltrim($_SERVER['REQUEST_URI'],'/');	// Current path

		$urlParams				= ltrim(substr($this->path, strpos($this->path, '?')), '?'); // Get the parameters
		$urlParams				= explode('&', $urlParams); // Split them up if there are more than one
		// If path has "?" remove it from the path, as it will be read wrong.
		if (str_contains($this->path, "?")) {
			$this->path = substr($this->path, 0, strpos($this->path, "?"));
		}

		// Split the path into various elements.
		$this->elements			= preg_split('/ [\/|?|&] /', $this->path);
		// Check if path exists.
		$this->pathExists = file_exists('../src/documents/' . $this->path . '.md');

		// Compile SCSS
		$compiler = new Compiler();
		$compiler->SetImportPaths('../src/scss/');
		$css = $compiler->compileString(file_get_contents('../src/scss/main.scss'))->getCss();
		file_put_contents('assets/css/main.css', $css);

		// Get header
		require_once 'assets/viewables/header.php';

		// Prepare the markdown converter
		$converter = new CommonMarkConverter([
			'html_input' => 'strip',
			'allow_unsafe_links' => false,
		]);

		// routing.
		if(empty($this->elements[0])){
			echo $converter->convert(file_get_contents($this->defaultView . '.md'));
		}else{
			if ($this->pathExists !== false) {
				echo $converter->convert(file_get_contents('../src/documents/' . $this->path . '.md'));
			}else{
				// Show 404
				echo $converter->convert(file_get_contents('../src/documents/errors/404.md'));
			}
		}
	}

}