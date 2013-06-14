<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPICaptureItem extends AppBuilderAPIFormItem {

	public function __construct($title, $type, $variable) {
		parent::__construct('imageselect', $variable);
		$this->post = true;
		$this->setTitle($title);
		$this->setType($type);
		$this->setAttribute('method', 'POST');
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	public function setSubTitle($subtitle) {
		$this->setAttribute('subtitle', $subtitle);
	}

	public function setThumbnail($url) {
		$this->setImageAttribute('thumbnail', $url);
	}

	public function setType($type) {
		$this->setAttribute('type', $type);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . ' capture thumb ' . $this->getAttribute('type');
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$type = $this->getAttribute('type');
		$node->setAttribute('data-capturetype', $type);
		$node->setAttribute('data-name', $this->getAttribute('variable'));
		$node->setAttribute('data-save-value', $this->save);
		
		$node->appendChild($on = $xml->createElement('div', 'on'));
		
		$on->appendChild($xml->createElement('div', 'image'));
		$on->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		$on->appendChild($xml->createElement('div', array('class' => 'text', 'text' => $this->getAttribute('subtitle'))));

		$on->appendChild($xml->createElement('button', array('class' => 'capture', 'text' => 'Capture')));
		$on->appendChild($xml->createElement('button', array('class' => 'choose', 'text' => 'Choose')));

		$node->appendChild($off = $xml->createElement('div', 'off'));
		
		$off->appendChild($xml->createElement('div', array('class' => 'title', 'text' => ucfirst($type) . ' Capture')));
		$off->appendChild($xml->createElement('div', array('class' => 'text', 'text' => "You need to install this app via the store to use $type capture")));
		
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'capture';
			$obj['title'] = $this->getAttribute('title');
			$obj['text'] = $this->getAttribute('subtitle');
			$obj['capturetype'] = $this->getAttribute('type');
			$myImage = &$this->getImageObject('thumbnail');
			if ($myImage) {
				$obj['image'] = $myImage['id'];
			}
		}
		return $this->myObj;
	}

	public function getObjects(&$obj) {
		parent::getObjects($obj);

		$myImage = &$this->getImageObject('thumbnail');
		if ($myImage) {
			$obj['images'][$myImage['id']] = $myImage;
		}
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setAttribute('defaultstate', $style->getStyle('defaultstate'));
		$element->setAttribute('titlesize', $style->getStyle('titlesize'));
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
		$element->setAttribute('subtitlesize', $style->getStyle('subtitlesize'));
		$element->setAttribute('subtitlecolor', $style->getStyle('subtitlecolor'));
		$element->setAttribute('titlefont', $style->getStyle('titlefont'));
		$element->setAttribute('weight', $style->getStyle('weight'));
		$element->setAttribute('subtitlefont', $style->getStyle('subtitlefont'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
		$element->setImageAttribute('foregroundimage', $style->getStyle('foregroundimage'));
		$element->setImageAttribute('foregroundimagealternate', $style->getStyle('foregroundimagealternate'));
	}

}
