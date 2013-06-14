<?php

include('../api/api.php');


$screen = new AppBuilderAPIListScreen('Home');





$screen->addChild($link = new AppBuilderAPILinkItem('link'));
$link->setRemoteLink('http://vitaliy.ekreative.com/appshed-git/site/components/com_appbuilder/libraries/appshed-extension-api/examples/example-links2.php');


echo AppBuilderAPI::getScreenResponse($screen);