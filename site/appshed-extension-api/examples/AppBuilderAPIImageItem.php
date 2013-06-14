<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIImageItem');
$screen->addChild(new AppBuilderAPIImageItem('http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'));

echo AppBuilderAPI::getScreenResponse($screen);