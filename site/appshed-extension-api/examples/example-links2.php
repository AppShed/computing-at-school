<?php

include('../api/api.php');


$screen = new AppBuilderAPIListScreen('Home');
$screen->addChild($link = new AppBuilderAPILinkItem('screen3'));



$link->setScreenLink($screen2 = new AppBuilderAPIListScreen('Home'));
					$screen2->addChild($link2 = new AppBuilderAPILinkItem('link 2 '));
										//$link2->setRemoteLink('http://vitaliy.ekreative.com/appshed-git/site/components/com_appbuilder/libraries/appshed-extension-api/examples/example-links.php');


echo AppBuilderAPI::getScreenResponse($screen);