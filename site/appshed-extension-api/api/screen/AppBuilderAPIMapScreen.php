<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIMapScreen extends AppBuilderAPIScreen {
	
	const TYPE = 'map';
	
	protected $scrolling = false;
	private $zoom = 12;
	
	public function setDefaultZoom($zoom) {
		$this->zoom = $zoom;
	}


	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " map";
	}

	protected function getHTMLNodeBase($xml, &$data, $css , &$javascripts=array()) {
		$this->node = parent::getHTMLNodeBase($xml, $data, $css, $javascripts);
		$this->node->setAttribute('data-zoom', $this->zoom);
		return $this->node;
	}
	
	/**
	 *
	 * @param DOMElement $items
	 * @param DOMDocument $xml
	 * @param array $data 
	 */
	protected function addHTMLChildren($items, $xml, &$data, $css, &$javascripts) {
		$items->appendChild($itemsInner = $xml->createElement('script', array('type' => 'application/json')));
		$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
		$data['settings']['currentscreen'] = $this->getId();
		$locs = array();
		foreach ($this->children as $child) {
			$locs[] = $child->getMarkerObject($xml, $data);
			$child->getCSS($css, $data);
			$child->getJavascript($javascripts);
		}
		$data['settings']['currentscreen'] = $s;
		$itemsInner->appendChild($xml->createTextNode(json_encode($locs)));
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'map';
		}
		return $this->myObj;
	}

	/* XML Export */

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml, $root = true) {
		$node = parent::getNode($xml);

		$mapNode = new AppBuilderAPIElement('map');
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
			$rootNode->setAttribute('type', 'map');
			$rootNode->appendChild($node);
			$node = $rootNode;
		}

		return $node;
	}

}
