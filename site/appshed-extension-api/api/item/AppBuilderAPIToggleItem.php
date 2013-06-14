<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIToggleItem extends AppBuilderAPIFormItem {

	private $true;
	private $false;

	public function __construct($variable, $defaultvalue = false) {
		parent::__construct('toggle', $variable);
		$this->setDefaultValue($defaultvalue);
	}

	public function setDefaultValue($defaultValue) {
		$this->setAttribute('defaultValue', $defaultValue);
	}

	public function setTrue($item) {
		$this->true = $item;
	}

	public function setFalse($item) {
		$this->false = $item;
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
	public function getNode($xml) {
		$node = parent::getNode($xml);

		if ($this->true) {
			$trueNode = $xml->createElement('true');
			$node->appendChild($trueNode);
			$trueNode->appendChild($this->true->getNode($xml));
		}

		if ($this->false) {
			$falseNode = $xml->createElement('false');
			$node->appendChild($falseNode);
			$falseNode->appendChild($this->false->getNode($xml));
		}

		return $node;
		$items = $xml->createElement('items');
		$items->appendChild($node);
		return $items;
	}

}
