<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPICheckboxItem extends AppBuilderAPIFormItem {

	private $value;

	public function __construct($title, $variable, $value) {
		parent::__construct('checkbox', $variable);
		$this->setTitle($title);
		$this->setValue($value);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}
	
	public function setValue($value) {
		$this->value = $value;
	}

	public function setDefaultValue($defaultValue) {
		$this->setAttribute('defaultvalue', $defaultValue ? 'checked' : null);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " textbox checkbox";
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
			'type' => 'checkbox',
			'name' => $this->getAttribute('variable'),
			'data-variable' => $this->getAttribute('variable'),
			'data-save-value' => $this->save,
			'checked' => $this->getAttribute('defaultvalue'),
			'value' => $this->value)));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'checkbox';
			$obj['value'] = $this->value;
			$obj['title'] = $this->getAttribute('title');
		}
		return $this->myObj;
	}
}
