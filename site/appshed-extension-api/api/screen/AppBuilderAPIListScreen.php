<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIListScreen extends AppBuilderAPIScreen {
	
	const TYPE = 'list';
	
	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " list";
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'list';
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function style($style, $element) {
		$element->setImageAttribute('backgroundimage', $this->getStyle('listbackground'));
	}

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml, $root = true) {
		$node = parent::getNode($xml);

		if ($root) {
			$rootNode = $this->getRootNode($xml);
			$rootNode->setAttribute('type', 'list');
			$rootNode->setAttribute('title', $this->title);
			$rootNode->appendChild($node);
			$node = $rootNode;
		}

		return $node;
	}

}
