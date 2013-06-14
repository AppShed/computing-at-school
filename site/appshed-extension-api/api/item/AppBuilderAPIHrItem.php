<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIHrItem extends AppBuilderAPIItem {

	public function __construct($height = 1, $color = '0,0,0') {
		$this->item = parent::__construct('hr');
		$this->setHeight($height);
		$this->setColor($color);
	}

	/* HTML Export */

	public function getHTMLNode($xml, &$data) {
		return null;
	}

	/* JSON Export */

	public function &getObj() {
		$this->myObj = null;
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setAttribute('height', $style->getStyle('height'));
	}

}
