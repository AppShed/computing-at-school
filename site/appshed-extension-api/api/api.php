<?php

/**
 * @package AppBuilderAPI
 * @subpackage  AppBuilderAPI
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */

spl_autoload_register(function ($classname) {
	$dirs = array('core', 'app', 'screen', 'item');
	foreach($dirs as $d) {
		$f = dirname(__FILE__) . DIRECTORY_SEPARATOR . $d . DIRECTORY_SEPARATOR . $classname . ".php";
		if(file_exists($f)) {
			require_once $f;
			break;
		}
	}
});

