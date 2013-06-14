<?php
require_once 'appshed-extension-api/api/api.php';
require_once 'functions.php';

if(empty($_GET['username']) || empty($_GET['password'])) {
    $apiScreen = new AppBuilderAPIListScreen("Incorrect Login");
    echo AppBuilderAPI::getScreenResponse($apiScreen);
    exit;
}

if(!isset($_GET['url']) || !isset($_GET['name']) || strpos($_GET['url'], 'http://community.computingatschool.org.uk') != 0) {
    exit;
}

$file = fetchURL($_GET['url'], null, false, array(
    'username' => $_GET['username'],
    'password' => $_GET['password']
));

if(!$file) {
    $apiScreen = new AppBuilderAPIListScreen("Incorrect Login");
    echo AppBuilderAPI::getScreenResponse($apiScreen);
    exit;
}

$data = json_decode($file, true);

$apiScreen = new AppBuilderAPIListScreen($_GET['name']);

foreach($data as $topic) {
    if(!($countPosts = count($topic['posts']))) {
        continue;
    }
    $createStr = date('j M Y', strtotime($topic['created_at']));
    $apiItem = new AppBuilderAPIThumbItem($topic['name'],
        "by {$topic['posts'][0]['user']['name']} - $createStr - $countPosts replies",
        "http://community.computingatschool.org.uk{$topic['posts'][0]['user']['picture']}");
    $apiItem->setImageSize(array(
        'width' => 24,
        'height' => 21,
    ));
    $apiItem->setScreenLink(getTopicScreen($topic));
    $apiItem->setHrAfter(false);
    $apiScreen->addChild($apiItem);
    
    $lastPost = $countPosts - 1;
    $updatedStr = time_since(time() - strtotime($topic['updated_at']));
    $apiItem = new AppBuilderAPITextItem("Latest reply ({$topic['posts'][$lastPost]['user']['name']} - $updatedStr ago):");
    $apiItem->setSize(12);
    $apiItem->setHrAfter(false);
    $apiItem->setPaddingLeft(20);
    $apiItem->setPaddingRight(20);
    $apiScreen->addChild($apiItem);
    
    $noTagsBody = strip_tags($topic['posts'][$lastPost]['body']);
    $shortNoTagsBody = substr($noTagsBody, 0, 200);
    $shortend = strlen($noTagsBody) > 200;
    
    $apiItem = new AppBuilderAPITextItem($shortNoTagsBody);
    $apiItem->setItalic(true);
    $apiItem->setPaddingLeft(20);
    $apiItem->setPaddingRight(20);
    if($shortend) {
        $apiItem->setHrAfter(false);
    }
    $apiScreen->addChild($apiItem);
    
    if($shortend) {
        $apiItem = new AppBuilderAPILinkItem("more");
        $apiItem->setImageSize(array(
            'width' => 40,
            'height' => 40,
        ));
        $apiItem->setScreenLink(getMoreScreen($topic['name'], $topic['posts'][$lastPost]['body']));
        $apiItem->setColor("8,74,255");
        $apiItem->setAlign("right");
        $apiScreen->addChild($apiItem);
    }
}

echo AppBuilderAPI::getScreenResponse($apiScreen);

function getMoreScreen($topicName, $body) {
	$apiScreen = new AppBuilderAPIListScreen($topicName);
	$apiItem = new AppBuilderAPIFormattedItem($body);
	$apiScreen->addChild($apiItem);
	return $apiScreen;
}

function getTopicScreen($topic) {
    $apiScreen = new AppBuilderAPIListScreen($topic['name']);
    
	$apiItem = new AppBuilderAPITextItem($topic['name']);
	$apiItem->setBold(true);
	$apiItem->setSize(18);
	$apiItem->setAlign("center");
	$apiItem->setPaddingTop(20);
	$apiItem->setPaddingBottom(20);
	$apiItem->setPaddingLeft(20);
	$apiItem->setPaddingRight(20);
	$apiScreen->addChild($apiItem);
	
    foreach($topic['posts'] as $post) {
        $apiItem = new AppBuilderAPIThumbItem($post['user']['name'], $post['user']['roles'], "http://community.computingatschool.org.uk{$post['user']['picture']}");
        $apiItem->setImageSize(array (
            'width' => 32,
            'height' => 24,
        ));
        $apiItem->setHrAfter(false);
        $apiScreen->addChild($apiItem);
        
        $noTagsBody = strip_tags($post['body']);
        $shortNoTagsBody = substr($noTagsBody, 0, 200);
        $shortend = strlen($noTagsBody) > 200;
        
        $apiItem = new AppBuilderAPITextItem($shortNoTagsBody);
        if($shortend) {
            $apiItem->setHrAfter(false);
        }
        $apiScreen->addChild($apiItem);
        
        if($shortend) {
            $apiItem = new AppBuilderAPILinkItem("... more");
            $apiItem->setScreenLink(getMoreScreen($topic['name'], $post['body']));
            $apiItem->setColor("8,74,255");
            $apiItem->setAlign("right");
            $apiScreen->addChild($apiItem);
        }
    }
    
    return $apiScreen;
}
