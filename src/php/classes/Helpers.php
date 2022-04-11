<?php

namespace Angus\Ivmdcms\php\classes;

class Helpers {

	public static function SearchAjax ($search) {

		if ($search != "") {
			exec("grep -rli /var/www/src/documents/* -e '$search'", $result);
			echo '<div>';
			if (empty($result)) {
				echo "<a>No results.</a>";
			} else {
				foreach ($result as $r) {
					$r = str_replace(Bootstrapper::RootDirectory() . '/src/documents/', '', $r);
					$r = str_replace('.md', '', $r);
					$textR = '';
					if (str_contains($r, '/')) {

						$rArray = explode('/', $r);
						for ($i = 0; $i < count($rArray); $i++) {
							$rArray[$i] = ucfirst($rArray[$i]);
							if ($i === (count($rArray) - 1)) {
								$textR .= $rArray[$i];
							} else {
								$textR .= $rArray[$i].' → ';
							}
						}
					} else {
						$textR = ucfirst($r);
					}
					echo "<a onclick='fill($r)' href='/$r'>$textR</a>";
				}
			}
			echo '<div>';
		}
	}

}