<?php

include('../api/api.php');

$app = new AppBuilderAPIApp();

$tab = new AppBuilderAPITab('Home');
$screen = new AppBuilderAPIListScreen('Home');
$screen->addChild(new AppBuilderAPITextItem('some text of this first screen'));
$tab->setScreenLink($screen);
$app->addTab($tab);

$tab = new AppBuilderAPITab('Tab 2');
$screen = new AppBuilderAPIListScreen('Tab 2');
$screen->addChild(new AppBuilderAPITextItem('some text of this second screen'));
$tab->setScreenLink($screen);
$app->addTab($tab);

$tab = new AppBuilderAPITab('Tab 3');
$screen = new AppBuilderAPIListScreen('Tab 3');
$screen->addChild(new AppBuilderAPITextItem('some text of this third screen'));
$tab->setScreenLink($screen);
$app->addTab($tab);

echo AppBuilderAPI::getScreenResponse($app);