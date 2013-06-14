<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$apps = array(
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('title' => 'title', 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png')
);

$screen = new AppBuilderAPIIconScreen('IconScreen', 4);
foreach ($apps as $app) {
	$screen->addChild(new AppBuilderAPIIconItem($app['title'], $app['icon']));
}

echo AppBuilderAPI::getScreenResponse($screen);