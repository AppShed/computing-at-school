<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('Home');

$feedUrl = 'http://blog.stuartherbert.com/php/?feed=rss2';
$rawFeed = file_get_contents($feedUrl);
$xml = new SimpleXmlElement($rawFeed);

foreach ($xml->channel->item as $item) {
	$screen->addChild($link = new AppBuilderAPIPlainItem((string) $item->title));
	$screen->addChild(new AppBuilderAPITextItem((string) $item->description));
	$link->setWebLink((string) $item->link);
}

echo AppBuilderAPI::getScreenResponse($screen);