<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
abstract class AppBuilderAPIFormItem extends AppBuilderAPIItem {

	public $post = false;

	public $save = 'yes';

	protected function __construct($tagName, $variable) {
		parent::__construct($tagName);
		$this->setVariable($variable);
	}

	public function setVariable($variable) {
		$this->setAttribute('variable', $variable);
	}

	public function setSaveValue($save) {
		$this->save = $save ? 'yes' : 'no';
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['variable'] = $this->getAttribute('variable');
			$obj['savevalue'] = $this->save;
		}
		return $this->myObj;
	}
	
	public function getJavascript(&$javascripts) {
		
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setAttribute('size', $style->getStyle('size'));
		$element->setAttribute('color', $style->getStyle('color'));
	}
}
