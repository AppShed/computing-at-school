<?php


include('../api/api.php');


//$screen = new AppBuilderAPIListScreen('Home');
//$screen = new AppBuilderAPIIconScreen('Home');
$screen = new AppBuilderAPIMapScreen('Home');

$link = new AppBuilderAPIMarkerItem( 'asd', 'link 1' , 49,32);

///$link = new AppBuilderAPIIconItem( '', 'link 1 ');

$link->setJsCodeLink('alert(\'123\')');
$screen->addChild($link);
$link2 = new AppBuilderAPIMarkerItem( 'asd', 'link 1' , 49,32);
$screen->addChild($link2);




//echo AppBuilderAPI::getElementAsHTMLJSONP($screen);
echo AppBuilderAPI::getScreenResponse($screen);
