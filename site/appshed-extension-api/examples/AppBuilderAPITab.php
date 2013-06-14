<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$tab = new AppBuilderAPITab('tab');
$tab->setScreenLink($screen = new AppBuilderAPIListScreen('Home', 4));
$screen->setAppsBackground('components/com_appbuilder/assets/images/appbuilder/appscreen-bg.jpg');

echo AppBuilderAPI::getScreenResponse($tab);
