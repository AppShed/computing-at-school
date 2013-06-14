<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIPickerItem');
$screen->addChild(new AppBuilderAPIPickerItem('Time', 'time', 'time'));
$screen->addChild(new AppBuilderAPIPickerItem('Date', 'date', 'date'));

echo AppBuilderAPI::getScreenResponse($screen);
