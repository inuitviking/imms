<?php

namespace Angus\Ivmdcms\php\classes;

class Bootstrapper {

	/**
	 * Static function to return the root directory of the project.
	 * @return string
	 */
	public static function RootDirectory (): string {
		// Change the second parameter to suit your needs
		return dirname(__FILE__, 4);
	}
}