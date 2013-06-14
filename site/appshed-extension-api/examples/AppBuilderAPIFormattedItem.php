<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php');

$screen = new AppBuilderAPIListScreen('AppBuilderAPIFormattedItem');
$screen->addChild(new AppBuilderAPIFormattedItem('

<h1>FormattedItem</h1><br>
<b>Bold</b><br>
<i>italic</i><br>


'));

echo AppBuilderAPI::getScreenResponse($screen);
