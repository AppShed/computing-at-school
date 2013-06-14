<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');
 
$screen = new AppBuilderAPIListScreen('AppBuilderAPISelectItem');
$screen->addChild($select = new AppBuilderAPISelectItem('select', 'select'));
$select->addOption('foo', '1');
$select->addOption('bar', '2');

echo AppBuilderAPI::getScreenResponse($screen);

