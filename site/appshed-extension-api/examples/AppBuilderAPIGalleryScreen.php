<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$images = array(
	'http://pierre.chachatelier.fr/programmation/images/mozodojo-original-image.jpg',
	'http://upload.wikimedia.org/wikipedia/commons/8/85/Image-New_Delhi_Lotus.jpg',
	'http://s3.freefoto.com/images/05/45/05_45_3_web.jpg',
	'http://img1.liveinternet.ru/images/attach/c/6/90/63/90063819_Image_033.jpg',
	'http://www.script-tutorials.com/demos/13/files/image.jpg',
	'http://img13.imageshost.ru/img/2012/11/26/image_50b3bc4f60a30.jpg'
);

$screen = new AppBuilderAPIGalleryScreen('GalleryScreen', 4);
foreach ($images as $image) {
	$screen->addChild(new AppBuilderAPIGalleryImageItem($image));
}

echo AppBuilderAPI::getScreenResponse($screen);