<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIIconScreen extends AppBuilderAPIScreen {
	
	const TYPE = 'icon';

	public function __construct($title, $cols = 3) {
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

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " icon";
	}
	
	/**
	 *
	 * @param DOMElement $items
	 * @param DOMDocument $xml
	 * @param array $data 
	 */
	protected function addHTMLChildren($items, $xml, &$data, $css, &$javascripts) {
		$items->appendChild($itemsInner = $xml->createElement('div', 'items-inner'));
		$itemsInner->appendChild($table = $xml->createElement('table'));
		$table->appendChild($row = $xml->createElement('tr'));
		
		$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
		$data['settings']['currentscreen'] = $this->getId();
		
		$i = 0;
		$cols = $this->getStyle('cols');
		if(!$cols) {
			$cols = 3;
		}
		foreach ($this->children as $child) {
			$c = $child->getHTMLNode($xml, $data);
			if ($c) {
				if($i == $cols) {
					$table->appendChild($row = $xml->createElement('tr'));
					$i = 0;
				}
				$i++;
				$row->appendChild($c);
			}
			$child->getCSS($css, $data);
			$child->getJavascript($javascripts);
		}
		while($i < $cols) {
			$row->appendChild($xml->createElement('td'));
			$i++;
		}
		
		$data['settings']['currentscreen'] = $s;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'icon';
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function style($style, $element) {
		$element->setImageAttribute('backgroundimage', $this->getStyle('iconbackground'));
		$element->setAttribute('cols', $this->getStyle('cols'));
	}

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml, $root = true) {
		$node = parent::getNode($xml);

		$mapNode = new AppBuilderAPIElement('gallery');
		$mapNode->setAttribute('type', 'gallery');
		$mapNode->setAttribute('title', $this->title);
		$this->applyStyle($mapNode);
		$mapNode = $mapNode->getNode($xml);
		$mapNode->appendChild($node);

		$itemsNode = new AppBuilderAPIElement('items');
		$this->applyStyle($itemsNode);
		$itemsNode = $itemsNode->getNode($xml);
		$itemsNode->appendChild($mapNode);

		$node = $itemsNode;

		if ($root) {
			$rootNode = $this->getRootNode($xml);
			$rootNode->appendChild($node);
			$node = $rootNode;
		}

		return $node;
	}

}
