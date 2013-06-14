<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPICaptureItem');
$screen->addChild(new AppBuilderAPICaptureItem('Capture', 'video', 'video'));
		
echo AppBuilderAPI::getScreenResponse($screen);
