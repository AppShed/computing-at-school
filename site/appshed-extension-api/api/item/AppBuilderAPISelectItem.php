<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPISelectItem extends AppBuilderAPIFormItem {

	private $options = array();

	public function __construct($title, $variable) {
		parent::__construct('select', $variable);
		$this->setTitle($title);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	public function addOption($name, $value) {
		$this->options[] = array('title' => $name, 'value' => $value);
	}
	
	public function setDefaultValue($defaultValue) {
		$this->setAttribute('defaultvalue', $defaultValue);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " select";
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
		
		$default = $this->getAttribute('defaultvalue');
		foreach ($this->options as $option) {
			if($option['value'] == $default) {
				$option['selected'] = true;
			}
		}
		
		$node->appendChild($inner = $xml->createElement('div', 
				'selected-container' . (empty($title) ? ' no-title' : '')
		));
		/*$inner->appendChild($xml->createElement('span', array(
			'class'=>'selected',
			'data-select' => 'yes',
			'data-variable' => $this->getAttribute('variable'),
			'text' => json_encode($this->options)
		)));
		*/
		$inner->appendChild($select = $xml->createElement('select', array(
			'name' => $this->getAttribute('variable'),
			'data-variable' => $this->getAttribute('variable'),
			'data-save-value' => $this->save)));
		$default = $this->getAttribute('defaultvalue');
		foreach ($this->options as $option) {
			$o = $xml->createElement('option', array('value' => $option['value'], 'text' => $option['title']));
			$select->appendChild($o);
			if ($option['value'] == $default) {
				$o->setAttribute('selected', 'selected');
			}
		}
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'select';
			$obj['title'] = $this->getAttribute('title');
			$obj['defaultValue'] = $this->getAttribute('defaultvalue');
			$obj['options'] = array();
			foreach ($this->options as $options) {
				$o = array(
					'id' => AppBuilderAPIElement::id(),
					'name' => $options['title'],
					'value' => $options['value'],
					'item' => $obj['id']
				);
				$obj['options'][] = $o;
			}
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function getNode($xml) {
		$node = parent::getNode($xml);

		$items = new AppBuilderAPIElement('items');
		$items = $items->getNode($xml);
		foreach ($this->options as $option) {
			$optionNode = new AppBuilderAPIElement('option');
			foreach ($option as $k => $v) {
				$optionNode->setAttribute($k, $v);
			}
			$optionNode = $optionNode->getNode($xml);
			$items->appendChild($optionNode);
		}

		$node->appendChild($items);
		return $node;
	}

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
	}

}
