<?php

include('../api/api.php');

$apps = array(
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1378, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1378, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1378, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'),
	array('id'=>1386, 'icon' => 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png')
	
);



$screen = new AppBuilderAPIAppsScreen('Home' ,4);
$screen->setAppsBackground( 'components/com_appbuilder/assets/images/appbuilder/appscreen-bg.jpg');


$screen->addHomeChild(new AppBuilderAPIIconItem(1386 , 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'));
$screen->addHomeChild(new AppBuilderAPIIconItem(1386 , 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'));
$screen->addHomeChild(new AppBuilderAPIIconItem(1386 , 'http://fred-test-images-resized.s3.amazonaws.com/23362_Icon.png'));

foreach ($apps as $app) {
	$screen->addChild($link = new AppBuilderAPIIconItem($app['id'] , $app['icon']));
	$link->setAppLink($app['id']);
	
}
    $screen->addChild($lisnk = new AppBuilderAPIIconItem('sasda' , 'http://fred-test-images-resized.s3.amazonaws.com/21454_Icon.jpeg'));
	$lisnk->setAppLink(23442);

	$data =  AppBuilderAPI::getElementAsHTMLObj($screen);


?><html>
	<head>
		<style>
			table tr td{
				border: 1px solid coral;
			}
			table{
				float: left;
				border: 3px solid aqua;
			}
			
			
			
		</style>
	</head>
	<body>


		<?php echo $data['screen'][$data['settings']['main']]['html']; ?>		
	</body>
</html>