<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPITextItem extends AppBuilderAPIFormattedItem {

	private $text;

	public function __construct($text) {
		parent::__construct('text');
		$this->setText($text);
	}

	public function setText($text) {
		$this->text = $text;
		$this->setHTML(nl2br($text));
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " text";
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'text';
			$obj['text'] = $this->text;
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function getNode($xml) {
		$node = parent::getNode($xml);
		$contentNode = $xml->createElement('content');
		$contentNode->appendChild($xml->createCDATASection($this->text));
		$node->appendChild($contentNode);
		return $node;
	}

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		parent::style($style, $element);
		$element->setAttribute('enablefavourite', $style->getStyle('enablefavourite'));
		$element->setAttribute('fontfamily', $style->getStyle('fontfamily'));
		$element->setAttribute('size', $style->getStyle('size'));
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setAttribute('align', $style->getStyle('align'));
		$element->setAttribute('titlex', $style->getStyle('titlex'));
		$element->setAttribute('titley', $style->getStyle('titley'));
		$element->setAttribute('defaultstate', $style->getStyle('defaultstate'));
		$element->setAttribute('weight', $style->getStyle('weight'));
		$element->setAttribute('height', $style->getStyle('height'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
		$element->setImageAttribute('foregroundimage', $style->getStyle('foregroundimage'));
		$element->setImageAttribute('foregroundimagealternate', $style->getStyle('foregroundimagealternate'));
	}

}
