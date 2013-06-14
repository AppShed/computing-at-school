<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIAppsScreen('AppBuilderAPIHrItem');
$screen->addChild(new AppBuilderAPIHrItem(10, '255,127,255'));

echo AppBuilderAPI::getScreenResponse($screen);