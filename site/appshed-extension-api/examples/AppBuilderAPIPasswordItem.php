<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIPasswordItem');
$screen->addChild(new AppBuilderAPIPasswordItem('Input', 'input', 'text'));

echo AppBuilderAPI::getScreenResponse($screen);

