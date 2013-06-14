<?php

/**
 * @deprecated
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIPlainItem extends AppBuilderAPILinkingItem {

	public function __construct($title, $subtitle = null) {
		parent::__construct('plainlink');
		$this->setTitle($title);
		$this->setSubTitle($subtitle);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	public function setSubTitle($title) {
		$this->setAttribute('subtitle', $title);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " thumb";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		$node->appendChild($xml->createElement('div', array('class' => 'text', 'text' => $this->getAttribute('subtitle'))));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'thumb';
			$obj['title'] = $this->getAttribute('title');
			$obj['text'] = $this->getAttribute('subtitle');
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function styles($style, $element) {
		//parent::style($style, $element);
		$element->setAttribute('titlesize', $style->getStyle('titlesize'));
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
		$element->setAttribute('subtitlesize', $style->getStyle('subtitlesize'));
		$element->setAttribute('subtitlecolor', $style->getStyle('subtitlecolor'));
		$element->setAttribute('titlefont', $style->getStyle('titlefont'));
		$element->setAttribute('titlex', $style->getStyle('titlex'));
		$element->setAttribute('titley', $style->getStyle('titley'));
		$element->setAttribute('subtitlefont', $style->getStyle('subtitlefont'));
		$element->setAttribute('weight', $style->getStyle('weight'));
		$element->setImageAttribute('background', $style->getStyle('background'));
		$element->setImageAttribute('foregroundimage', $style->getStyle('foregroundimage'));
	}

}
