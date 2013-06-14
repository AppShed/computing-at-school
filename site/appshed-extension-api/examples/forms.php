<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */


include('../api/api.php'); // подключаем апи

$screen = new AppBuilderAPIListScreen('Home'); // 

if (isset($_GET['addcomment'])) { // проверяем добавляем ли мы комент
	$screen->setCSSText('   
		textbox .title{width: 94px;}
		item.textarea{height: 165px;}
	'); // добавляем CSS стили
	$screen->setHrAfter(false); // отключаем линии под элементами на всем скрине
	$screen->addChild($name = new AppBuilderAPIInputItem('name', '', 'Имя')); // добавляем поле воода имени
	$screen->addChild($email = new AppBuilderAPIInputItem('email', '', 'Email')); // добавляем поле воода почты
	$screen->addChild($text = new AppBuilderAPITextAreaItem('text', 'text', 'Текст'));// добавляем поле воода тсообщения
	$screen->addChild($button = new AppBuilderAPIButtonItem('Написать')); // добавляем кнопку 
	$button->setRemoteLink('http://dev.appshed.net/examples/forms.php?post=true'); // устанавливаем RemoteLink
	$button->addVariables($name, $email, $text); // добавляем переменные
}
else { // если комент не добавляем то отображаем старые

	$data = @file_get_contents('data.txt'); // читаем файл с информацией
	
	$coments = json_decode($data); // декодируем данные в json объект
	
	if(!is_array($coments)){ // если коментов еще нет то создаем пустой масив
		$coments = array();	
	}
	
	if (isset($_GET['post'])) { // проверяем нужно ли добавлять комент
		$comment = new stdClass(); // если да тосоздаем пустой stdClass
		$comment->name = @$_GET['name']; // добавляем имя
		$comment->email = @$_GET['email']; // добавляем email
		$comment->text = @$_GET['text']; // добавляем текст
		$comment->date = date('d-m-Y H:i:s'); // добавляем дату
		array_unshift($coments, $comment); // добавляем запись в начало масива
		file_put_contents('data.txt', json_encode($coments)); // сохраняем данные
	}

	foreach ($coments as $comment) { // бежим по масиву записей
		$screen->addChild($header = new AppBuilderAPIPlainItem($comment->name, $comment->email)); // создаем AppBuilderAPIPlainItem который будет
																								  // заглавием коментария и сразу вставляем в скрин
		$header->setHrAfter(false); // отключаем линию после заголавия
		$screen->addChild(new AppBuilderAPIFormattedItem($comment->text.'<br>'.$comment->date)); // добавляем AppBuilderAPIFormattedItem для текста сообщения
	}

	$screen->addChild($add = new AppBuilderAPILinkItem('Добавить сообщение')); // добавляем AppBuilderAPILinkItem для перехода на добавление комента
	$add->setRemoteLink('http://dev.appshed.net/examples/forms.php?addcomment=true'); // устанавливаем RemoteLink  
}
echo AppBuilderAPI::getScreenResponse($screen); // показываем скрин
