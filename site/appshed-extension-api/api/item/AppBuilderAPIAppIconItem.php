<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIAppIconItem extends AppBuilderAPIIconItem {

	const HTML_TAG = 'div';
	
	protected function getClass() {
		return parent::getClass();// . ' app-icon';
	}
	
	protected $innerClass;
	
	public function setInnerClass($class) {
		$this->innerClass = $class;
	}
	
	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->setAttribute('data-no-glow', 'no-glow');
		return $node;
	}
	
	protected function getInner($xml, &$data) {
		$inner = $xml->createElement('div', 'item-icon-inner');
		$inner->appendChild($icon = $xml->createElement('div', 'app-icon' . (empty($this->innerClass) ? '' : ' ' . $this->innerClass)));
		$icon->appendChild($xml->createElement( 'div', array( 'class'=>'image', 'style' => $this->getImageURL('image') ? 'background-image:url(\''.$this->getImageURL('image').'\')' : '')));
		$icon->appendChild($xml->createElement( 'div', array( 'class'=>'background')));
		$this->applyLinkToNode($xml, $icon, $data);
		$inner->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		return $inner;
	}
	
	protected function applyLink($xml, $node, &$data) {
		
	}
}
