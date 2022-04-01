<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;

class Route {
	/**
	 * @var false|int|string
	 */
	private bool $pathExists;
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
		$this->defaultView		= '../src/documents/index.md';

		// Regarding paths
		$this->path				= ltrim($_SERVER['REQUEST_URI'],'/');	// Current path
		$urlParams = ltrim(substr($this->path, strpos($this->path, '?')), '?'); // Get the parameters
		$urlParams = explode('&', $urlParams); // Split them up if there are more than one
		// If path has "?" remove it from the path, as it will be read wrong.
		if (str_contains($this->path, "?")) {
			$this->path = substr($this->path, 0, strpos($this->path, "?"));
		}
		$this->elements			= preg_split('/ [\/|?|&] /', $this->path);
		$this->pathExists = file_exists('../src/documents/' . $this->path);

		// Get header
		require_once 'assets/viewables/header.php';

		$converter = new CommonMarkConverter([
			'html_input' => 'strip',
			'allow_unsafe_links' => false,
		]);

		if(empty($this->elements[0])){
			echo $converter->convert(file_get_contents($this->defaultView));
		}else{
			if ($this->pathExists !== false) {
				echo $converter->convert(file_get_contents('../src/documents/' . $this->path));
			}else{
				// Show 404
				echo $converter->convert(file_get_contents('../src/documents/errors/404.md'));
			}
		}
	}

}