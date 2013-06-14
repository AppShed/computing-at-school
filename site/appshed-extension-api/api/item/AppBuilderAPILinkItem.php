<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPILinkItem extends AppBuilderAPILinkingItem {

	private $imageSize;
	
	public function __construct($title, $icon = null) {
		parent::__construct('textlink');
		$this->setTitle($title);
		if ($icon) {
			$this->setIcon($icon);
		}
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	public function setIcon($url) {
		$this->setImageAttribute('thumbnail', $url);
	}
	
	public function setImageSize($width, $height = null) {
		if(is_array($width)) {
			$this->imageSize = $width;
		}
		else if($width != null && $height != null) {
			$this->imageSize = array('width' => $width, 'height' => $height);
		}
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " plain";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		if ($this->hasAttribute('thumbnail')) {
			$node->appendChild($xml->createImgElement($this->getImageURL('thumbnail'), 'icon', $this->imageSize));
		}
		$node->appendChild($xml->createElement('div', array('class' => 'text', 'text' => $this->getAttribute('title'))));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'plain';
			$obj['text'] = $this->getAttribute('title');
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
		parent::style($style, $element);
		$element->setAttribute('height', $style->getStyle('height'));
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setAttribute('align', $style->getStyle('align'));
		$element->setAttribute('imagex', $style->getStyle('imagex'));
		$element->setAttribute('imagey', $style->getStyle('imagey'));
		$element->setAttribute('titlex', $style->getStyle('titlex'));
		$element->setAttribute('titley', $style->getStyle('titley'));
		$element->setAttribute('titlesize', $style->getStyle('titlesize'));
		$element->setAttribute('enablefavourite', $style->getStyle('enablefavourite'));
		$element->setImageAttribute('thumbnail', $style->getStyle('thumbnail'));
		$element->setImageAttribute('backgroundimage', $style->getStyle('backgroundimage'));
		$element->setImageAttribute('foregroundimage', $style->getStyle('foregroundimage'));
		$element->setImageAttribute('foregroundimagealternate', $style->getStyle('foregroundimagealternate'));
	}

}
