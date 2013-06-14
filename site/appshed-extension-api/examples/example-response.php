<?php

include('../api/api.php');


$screen = new AppBuilderAPIListScreen('Home');





$screen->addChild(new AppBuilderAPITextItem('form variable:'.( isset($_GET['yourformvariable']) ? $_GET['yourformvariable'] : 'null')));

echo AppBuilderAPI::getScreenResponse($screen);