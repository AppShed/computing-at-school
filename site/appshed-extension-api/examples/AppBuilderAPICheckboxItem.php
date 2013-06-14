<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPICheckboxItem');
$screen->addChild(new AppBuilderAPICheckboxItem('Checkbox', 'checkbox', true));

echo AppBuilderAPI::getScreenResponse($screen);
