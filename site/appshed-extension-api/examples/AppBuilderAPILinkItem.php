<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPILinkItem');
$screen->addChild($link = new AppBuilderAPILinkItem('Link item'));

echo AppBuilderAPI::getScreenResponse($screen);

