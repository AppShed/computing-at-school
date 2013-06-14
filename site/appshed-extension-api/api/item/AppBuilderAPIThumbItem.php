<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIThumbItem extends AppBuilderAPILinkingItem {

	private $imageSize;
	
	public function __construct($title, $subtitle, $url = null) {
		parent::__construct('thumblink');
		$this->setTitle($title);
		$this->setSubTitle($subtitle);
		$this->setThumbnail($url);
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
	
	public function setImageSize($width, $height = null) {
		if(is_array($width)) {
			$this->imageSize = $width;
		}
		else {
			$this->imageSize = array('width' => $width, 'height' => $height);
		}
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " thumb";
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
			$imageDiv = $xml->createElement('div', array('class' => 'image-container'));
			$node->appendChild($imageDiv);
			$imageDiv->appendChild($xml->createImgElement($this->getImageURL('thumbnail'), 'image', $this->imageSize));
		}
		$node->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		$node->appendChild($xml->createElement('div', array('class' => 'text', 'text' => $this->getAttribute('subtitle'))));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'thumb';
			$obj['title'] = $this->getAttribute('title');
			$obj['text'] = $this->getAttribute('subtitle');
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
		$element->setAttribute('variable', $style->getStyle('variable'));
		$element->setAttribute('defaultstate', $style->getStyle('defaultstate'));
		$element->setAttribute('titlesize', $style->getStyle('titlesize'));
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
		$element->setAttribute('subtitlesize', $style->getStyle('subtitlesize'));
		$element->setAttribute('subtitlecolor', $style->getStyle('subtitlecolor'));
		$element->setAttribute('titlefont', $style->getStyle('titlefont'));
		$element->setAttribute('subtitlefont', $style->getStyle('subtitlefont'));
		$element->setAttribute('titlefont', $style->getStyle('titlefont'));
		$element->setAttribute('subtitlefont', $style->getStyle('subtitlefont'));
	}

}
