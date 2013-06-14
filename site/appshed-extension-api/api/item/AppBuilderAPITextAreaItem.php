<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPITextAreaItem extends AppBuilderAPIFormItem {
	
	private $placeholder;

	public function __construct($variable, $defaultValue = null, $title = '') {
		parent::__construct('input', $variable);
		$this->setDefaultValue($defaultValue);
		$this->setTitle($title);
	}

	public function setDefaultValue($defaultValue) {
		$this->setAttribute('defaultvalue', $defaultValue);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}
	
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
	}
	
	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . ' textarea';
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$title = $this->getAttribute('title');
		if(!empty($title)) {
			$node->appendChild($xml->createElement('div', array('class'=>'title', 'text'=>$this->getAttribute('title'))));
		}
		$node->appendChild($inner = $xml->createElement('div', 'textarea-container' . (empty($title) ? ' no-title' : '')));
		$inner->appendChild($xml->createElement('textarea', array(
			'class' => 'textarea',
			'name' => $this->getAttribute('variable'),
			'text' => $this->getAttribute('defaultvalue'),
			'data-variable' => $this->getAttribute('variable'),
			'data-save-value' => $this->save)));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'textarea';
			$obj['title'] = $this->getAttribute('title');
			$obj['defaultValue'] = $this->getAttribute('defaultvalue');
			$obj['placeholder'] = $this->placeholder;
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function style($style, $element) {
		parent::style($style, $element);
		$element->setAttribute('height', $style->getStyle('height'));
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setAttribute('size', $style->getStyle('size'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
	}

}
