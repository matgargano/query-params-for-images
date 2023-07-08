<?php

/**
* Plugin Name: Query Parameters for Images For SpinupWP
* Plugin URI: http://matgargano.com/
* Description: This will auto add a query parameter to all images uploaded to the media library and refresh when spinuwp cache is flushed
* Version: 1.0.0
* Author: Mat Gargano
* Author URI: http://matgargano.com/
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: your-plugin-domain
*
* DISCLAIMER
* This software comes with no guarantee that it will work, and the author is not responsible for any
* consequences arising from its use. Use this plugin at your own risk.
*/

$namespace = ['QPFR'];

\spl_autoload_register(
	function ($class) use ($namespace) {
		$base = explode('\\', $class);
		if (in_array($base[0], $namespace)) {
			$file = __DIR__ . DIRECTORY_SEPARATOR . strtolower(
					str_replace(
						['\\', '_'],
						[
							DIRECTORY_SEPARATOR,
							'-',
						],
						$class
					) . '.php'
				);
			if (file_exists($file)) {
				require $file;
			} else {
				wp_die(sprintf('File %s not found', esc_html($file)));
			}
		}
	}
);


$actions = new \QPFR\Actions();
$actions->init();
