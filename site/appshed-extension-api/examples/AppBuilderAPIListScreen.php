<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIListScreen');
$screen->addChild( new AppBuilderAPIPlainItem('AppBuilderAPIPlainItem'));
$screen->addChild( new AppBuilderAPIPlainItem('AppBuilderAPIPlainItem','subtitle'));
$screen->addChild( new AppBuilderAPILinkItem('AppBuilderAPILinkItem', 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'));

echo AppBuilderAPI::getScreenResponse($screen);
