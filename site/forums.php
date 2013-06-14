<?php
require_once 'appshed-extension-api/api/api.php';
require_once 'functions.php';

if(empty($_GET['username']) || empty($_GET['password'])) {
    $apiScreen = new AppBuilderAPIListScreen("Incorrect Login");
    echo AppBuilderAPI::getScreenResponse($apiScreen);
    exit;
}

$file = fetchURL('http://community.computingatschool.org.uk/forums.json', null, false, array(
    'username' => $_GET['username'],
    'password' => $_GET['password']
));

if(!$file) {
    $apiScreen = new AppBuilderAPIListScreen("Incorrect Login");
    echo AppBuilderAPI::getScreenResponse($apiScreen);
    exit;
}

$data = json_decode($file, true);

$apiScreen = new AppBuilderAPIListScreen("Discussion Forums");
$apiScreen->setBack(false);

$base = base();

foreach($data['normal'] as $forum) {
    $apiItem = new AppBuilderAPILinkItem($forum['name'], "http://images-resized.appshed.com/10148plain_x2.png");
    $apiItem->setImageSize(array (
        'width' => 40,
        'height' => 40,
    ));
    
    $query = http_build_query(array(
        'url' => $forum['json_url'],
        'name' => $forum['name'],
        'username' => $_GET['username'],
        'password' => $_GET['password']
    ));
    
    $apiItem->setRemoteLink("$base/forum.php?$query");
    $apiScreen->addChild($apiItem);
}

echo AppBuilderAPI::getScreenResponse($apiScreen);
