<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIHeaderButtonItem extends AppBuilderAPILinkingItem {

	private $refreshRss = false;
	
	public function __construct($title) {
		parent::__construct('button');
		$this->setTitle($title);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " headerbutton";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($xml->createElement('div', array('class'=>'button', 'text'=>$this->getAttribute('title'))));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'headerbutton';
			$obj['text'] = $this->getAttribute('title');
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		parent::styles($style, $element);
		$element->setAttribute('format', $style->getStyle('formatformat'));
		$element->setAttribute('enablefavourite', $style->getStyle('enablefavourite'));
		$element->setAttribute('titlesize', $style->getStyle('titlesize'));
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
		$element->setAttribute('variable', $style->getStyle('variable'));
		$element->setAttribute('defaultstate', $style->getStyle('defaultstate'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
		$element->setImageAttribute('foregroundimage', $style->getStyle('foregroundimage'));
		$element->setImageAttribute('foregroundimagealternate', $style->getStyle('foregroundimagealternate'));
	}

}