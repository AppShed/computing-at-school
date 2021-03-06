<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIAppsScreen extends AppBuilderAPIScreen {

	const TYPE = 'app';

	private $homeChildren = array();

	public function __construct($title, $cols = 4) {
		parent::__construct($title);
		$this->setColumns($cols);
	}

	/**
	 * Add $item as a child of this screen
	 * @param AppBuilderAPIIconItem $item 
	 */
	public function addChild($item) {
		parent::addChild($item);
	}

	public function addHomeChild($item) {
		if (count($this->homeChildren) <= 4) {
			$this->homeChildren[] = $item;
		}
		else {
			return false;
		}
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " appsscreen";
	}

	/**
	 *
	 * @param DOMElement $items
	 * @param DOMDocument $xml
	 * @param array $data 
	 */
	protected function addHTMLChildren($items, $xml, &$data, $css, &$javascripts) {
		$items->appendChild($itemsInner = $xml->createElement('div', 'items-inner'));
		//Each screen has up to 16 apps
		$screens = array_chunk($this->children, 16);

		//Home bar
		$items->appendChild($homebar = $xml->createElement('div', array('class' => 'home-bar')));
		$items->appendChild($xml->createElement('div', 'home-underlay'));

		//Dots
		$homebar->appendChild($homeDots = $xml->createElement('div', array('class' => 'home-dots')));
		$homebar->appendChild($xml->createElement('div', array('class' => 'home-bar-bg')));
		$homeDots->appendChild($cnt = $xml->createElement('div', array('class' => 'home-dots-inner')));
		$screensCount = count($screens) + 1;
		for ($i = 0; $i < $screensCount; $i++) {
			$cnt->appendChild($xml->createElement('div', array('class' => 'dot ' . ($i == 0 ? 'curr search' : 'page' ), 'text' => $i == 0 ? '' : $i)));
		}
		//Empty screen if there are no apps
		if(count($screens) == 0) {
			$cnt->appendChild($xml->createElement('div', array('class' => 'dot page', 'text' => '1')));
		}
		$homeDots->appendChild($xml->createElement('div', array('style' => 'clear:both')));

		//Home bar buttons
		if (count($this->homeChildren) > 0) {
			$homebar->appendChild($homebarButtons = $xml->createElement('div', array('class' => 'home-bar-buttons')));
			foreach ($this->homeChildren as $homeButton) {
				$c = $homeButton->getHTMLNode($xml, $data);
				if ($c) {
					$homebarButtons->appendChild($c);
				}
				$homeButton->getCSS($css, $data);
				$homeButton->getJavascript($javascripts);
			}
		}

		//Search
		$itemsInner->appendChild($searchEl = $xml->createElement('div', array('class' => 'apps search')));
		$searchEl->appendChild($appInner = $xml->createElement('div', array('class' => 'apps-inner')));
		$appInner->appendChild($searchContainer = $xml->createElement('div', array('class' => 'search-holder')));
		$searchContainer->appendChild($inp = $xml->createElement('input', array('type' => 'search', 'placeholder' => 'Search', 'class' => 'search-box', 'x-webkit-speech' => 'x-webkit-speech')));
		$appInner->appendChild($searchResults = $xml->createElement('div', array('class' => 'search-results')));
		
		//Screens
		$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
		$data['settings']['currentscreen'] = $this->getId();
		$i = 1;
		foreach ($screens as $screen) {
			$itemsInner->appendChild($appsEl = $xml->createElement('div', array('class' => 'apps')));
			$appsEl->appendChild($xml->createElement('div', array('class' => 'apps-title', 'text' => "Page $i")));
			$appsEl->appendChild($appInner = $xml->createElement('div', array('class' => 'apps-inner')));
			foreach ($screen as $app) {
				$c = $app->getHTMLNode($xml, $data);
				if ($c) {
					$appInner->appendChild($c);
				}
				$app->getCSS($css, $data);
				$app->getJavascript($javascripts);
			}
			$i++;
		}
		//Empty screen if there are no apps
		if(count($screens) == 0) {
			$itemsInner->appendChild($appsEl = $xml->createElement('div', array('class' => 'apps')));
			$appsEl->appendChild($xml->createElement('div', array('class' => 'apps-title', 'text' => "Page $i")));
			$appsEl->appendChild($appInner = $xml->createElement('div', array('class' => 'apps-inner')));
		}
		$data['settings']['currentscreen'] = $s;
	}
}
