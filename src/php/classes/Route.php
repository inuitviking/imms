<?php

namespace Angus\Imms\php\classes;
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
	private array $titles;
	private bool $MDExists;
	private bool $HTMLExists;
	private array|false $ini;

	/**
	 * Handles routing in general.
	 * Will always load rendered markdown (HTML files; see Cache.php).
	 * If there is no available HTML file, it will check if the MD exists, and render it if it does.
	 * If not, it will throw a 404.
	 * @throws SassException
	 */
	public function __construct(){
		$this->ini = parse_ini_file(Bootstrapper::RootDirectory() . '/config/config.ini');

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
		$this->MDExists = file_exists(Bootstrapper::RootDirectory() . $this->ini['app_md_path'] . $this->path . '.md');
		// Check if HTML exists.
		$this->HTMLExists = file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $this->path . '.html');

		$cache = new Cache();

		// If the HTML doesn't exist, create a new cached version of the raw MD. If the MD file doesn't exist, do nothing, and let it fail.
		if ($this->HTMLExists !== true) {
			if ($this->MDExists) {
				$cache->MD2HTML(Bootstrapper::RootDirectory() . $this->ini['app_md_path'] . $this->path . '.md');
			}
		}

		// Compile SCSS
		$compiler = new Compiler();
		$compiler->SetImportPaths(Bootstrapper::RootDirectory() . $this->ini['app_scss_path']);
		$css = $compiler->compileString(file_get_contents(Bootstrapper::RootDirectory() . $this->ini['app_scss_path'] . $this->ini['app_scss_file']))->getCss();
		file_put_contents(Bootstrapper::RootDirectory() . $this->ini['app_css_path'], $css);

		// Get header
		require_once 'assets/viewables/header.php';

		// Routing.
		if(empty($this->elements[0])){
			if (file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $this->ini['app_default_view'] . '.html') != true) {
				$cache->MD2HTML(Bootstrapper::RootDirectory() . $this->ini['app_md_path'] . $this->ini['app_default_view'] . '.md');
			}
			require_once Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $this->ini['app_default_view'] . '.html';
		}else{
			if ($this->MDExists) {
				if (file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $this->path . '.html') != true) {
					$cache->MD2HTML(Bootstrapper::RootDirectory() . $this->ini['app_md_path'] .  $this->path . '.md');
				}
				require_once Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . $this->path . '.html';
			}else{
				// Set the response code to 404
				http_response_code(404);

				// Create cache if the HTML file exists (it should)
				if (file_exists(Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . 'errors/404.html') != true) {
					$cache->MD2HTML(Bootstrapper::RootDirectory() . $this->ini['app_md_path'] . 'errors/404.md');
				}

				// Show 404
				require_once Bootstrapper::RootDirectory() . $this->ini['app_html_path'] . 'errors/404.html';
			}
		}

		// Get footer
		require_once 'assets/viewables/footer.php';
	}

	public static function CreateMenu ($dir) {
		if (is_dir($dir)) {
			if (basename($dir) != 'errors') {
				echo "<li><a href='#'>".ucfirst(basename($dir))."</a><ul>";

				foreach (glob("$dir/*") as $path) {
					self::CreateMenu($path);
				}

				echo "</ul></li>";
			}
		} else {
			$extension = pathinfo($dir);
			$extension = $extension['extension'];

			$url = str_replace(Bootstrapper::RootDirectory() . '/src/documents/', "", $dir);
			$url = str_replace('.md', "", $url);

			echo "<li><a href='$url'>".ucfirst(basename($dir, ".".$extension))."</a></li>";
		}
	}

}