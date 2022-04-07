<?php

namespace Angus\Ivmdcms\php\classes;
require_once '../vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;

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
	private string $defaultMDPath;
	private string $defaultHTMLPath;
	private bool $MDExists;
	private bool $HTMLExists;


	/**
	 * Handles routing of MD files (cached versions).
	 * @throws SassException
	 */
	public function __construct(){
		// Default MD path
		$this->defaultMDPath	= '../src/documents/';
		// Default HTML path
		$this->defaultHTMLPath	= 'assets/cache/';
		// Select a default view
		$this->defaultView		= 'index';

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
		// Check if MD exists.
		$this->MDExists = file_exists($this->defaultMDPath . $this->path . '.md');
		// Check if HTML exists.
		$this->HTMLExists = file_exists($this->defaultHTMLPath . $this->path . '.html');

		$cache = new Cache();

		// If the HTML doesn't exist, create a new cached version of the raw MD. If the MD file doesn't exist, do nothing, and let it fail.
		if ($this->HTMLExists !== true) {
			if ($this->MDExists) {
				$cache->MD2HTML($this->defaultMDPath . $this->path . '.md');
			}
		}

		// Compile SCSS
		$compiler = new Compiler();
		$compiler->SetImportPaths('../src/scss/');
		$css = $compiler->compileString(file_get_contents('../src/scss/main.scss'))->getCss();
		file_put_contents('assets/css/main.css', $css);

		// Get header
		require_once 'assets/viewables/header.php';

		// Routing.
		if(empty($this->elements[0])){
			if (file_exists($this->defaultHTMLPath . $this->defaultView . '.html') != true) {
				$cache->MD2HTML($this->defaultMDPath . $this->defaultView . '.md');
			}
			require_once $this->defaultHTMLPath . $this->defaultView . '.html';
		}else{
			if ($this->MDExists) {
				if (file_exists($this->defaultHTMLPath . $this->path . '.html') != true) {
					$cache->MD2HTML($this->defaultMDPath . $this->path . '.md');
				}
				require_once $this->defaultHTMLPath . $this->path . '.html';
			}else{
				// Set the response code to 404
				http_response_code(404);

				// Create cache if the HTML file exists (it should)
				if (file_exists($this->defaultHTMLPath . 'errors/404.html') != true) {
					$cache->MD2HTML($this->defaultMDPath . 'errors/404.md');
				}

				// Show 404
				require_once $this->defaultHTMLPath . 'errors/404.html';
			}
		}

		// Get footer
		require_once 'assets/viewables/footer.php';
	}

}