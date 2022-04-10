<?php

namespace Angus\Ivmdcms\php\classes;

class Bootstrapper {
	public static function RootDirectory (): string {
		// Change the second parameter to suit your needs
		return dirname(__FILE__, 4);
	}
}