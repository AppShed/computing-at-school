<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
abstract class AppBuilderAPIItem extends AppBuilderAPIElement {
	const objType = 'items';

	private $styleParent;
	
	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " item";
	}
	
	protected function getIdType() {
		return "item";
	}
	
	public function getJavascript(&$javascripts) {
	
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function applyStyle($element) {
		if ($this->styleParent) {
			$this->styles($this->styleParent, $element);
		}
		$this->styles($this, $element);
	}

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setImageAttribute('backgroundimage', $style->getStyle('item_background'));
		$element->setAttribute('color', $style->getStyle('color'));
	}

	/*
	 * @deprecated since version 2.0
	 */
	public function setStyleParent($element) {
		$this->styleParent = $element;
	}
}
