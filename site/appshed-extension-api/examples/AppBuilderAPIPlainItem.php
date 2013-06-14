<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIPlainItem');
$screen->addChild(new AppBuilderAPIPlainItem('AppBuilderAPIPlainItem'));
$screen->addChild(new AppBuilderAPIPlainItem('AppBuilderAPIPlainItem','subtitle'));

echo AppBuilderAPI::getScreenResponse($screen);

