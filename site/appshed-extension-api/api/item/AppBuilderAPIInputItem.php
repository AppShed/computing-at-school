<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIInputItem extends AppBuilderAPIFormItem {

	private $placeholder;
	private $autocomplete;
	private $autocompleteVar;
	private $localsearch;

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
	
	public function setAutoCompleteUrl($url, $var = null) {
		$this->autocomplete = $url;
		$this->autocompleteVar = $var;
	}
	
	public function toLocalsearch($type = true) {
		$this->localsearch = $type;
	}
	
	/* HTML Export */
	
	const INPUT_TYPE = 'text';
	
	protected function getClass() {
		return parent::getClass() . ' textbox';
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
		$node->appendChild($inner = $xml->createElement('div', 'textbox-container' . (empty($title) ? ' no-title' : '')));
		$inner->appendChild($xml->createElement('input', array(
			'class'=>'textbox'.($this->localsearch ? ' localsearch' : '' ),
			'type'=>static::INPUT_TYPE,
			'name'=>$this->getAttribute('variable'),
			'value'=>$this->getAttribute('defaultvalue'),
			'placeholder'=>$this->placeholder,
			'data-variable' => $this->getAttribute('variable'),
			'data-save-value' => $this->save,
			'data-autocomplete-url' => $this->autocomplete,
			'data-autocomplete-variable' => $this->autocompleteVar ? $this->autocompleteVar : $this->getAttribute('variable'))));
		return $node;
	}
	
	/* XML Export */
	
	protected function styles($style, $element) {
		parent::style($style, $element);
		$element->setAttribute('size', $style->getStyle('size'));
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
	}
	
	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'textbox';
			$obj['title'] = $this->getAttribute('title');
			$obj['defaultValue'] = $this->getAttribute('defaultvalue');
			$obj['placeholder'] = $this->placeholder;
			$obj['autocomplete'] = $this->autocomplete;
			$obj['autocompleteVar'] = $this->autocompleteVar;
		}
		return $this->myObj;
	}

}
