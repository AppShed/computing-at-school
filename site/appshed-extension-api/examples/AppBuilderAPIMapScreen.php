<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIMapScreen('AppBuilderAPIListScreen');
$screen->addChild( new AppBuilderAPIMarkerItem('Черкассы','', 32.065,49.4418));

echo AppBuilderAPI::getScreenResponse($screen);
