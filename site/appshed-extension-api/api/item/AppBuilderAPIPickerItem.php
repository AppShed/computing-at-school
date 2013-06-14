<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIPickerItem extends AppBuilderAPIFormItem {

	private $pickerType;
	private $placeholder;
	
	const INPUT_TYPE = 'text';
	
	public function __construct($title, $type, $variable) {
		parent::__construct('picker', $variable);
		$this->setTitle($title);
		$this->setPickerType($type);
	}
	
	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}
	
	public function setPickerType($type) {
		$this->pickerType = $type;
	}
	
	public function setDefaultValue($defaultValue) {
		$this->setAttribute('defaultvalue', $defaultValue);
	}
	
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " textbox picker ".$this->pickerType;
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
		$inner->appendChild($xml->createElement('span', array(
			'class'=>'picked',
			'data-value'=>$this->getAttribute('defaultvalue'),
			'data-placeholder'=>$this->placeholder,
			'data-variable' => $this->getAttribute('variable'),
			'data-picker-type' => $this->pickerType,
			'data-save-value' => $this->save)));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'picker';
			$obj['title'] = $this->getAttribute('title');
			$obj['pickertype'] = $this->pickerType;
			$obj['defaultValue'] = $this->getAttribute('defaultvalue');
		}
		return $this->myObj;
	}
}
