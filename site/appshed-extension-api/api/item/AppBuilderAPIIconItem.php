<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIIconItem extends AppBuilderAPILinkingItem {

	const HTML_TAG = 'td';
	private $imageSize;
	
	public function __construct($title, $imageurl) {
		parent::__construct('galleryimage');
		$this->setTitle($title);
		$this->setImage($imageurl);
	}

	public function setTitle($title) {
		$this->setAttribute('title', $title);
	}

	public function setImage($url) {
		$this->setImageAttribute('image', $url);
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
		return parent::getClass() . ' icon';
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($this->getInner($xml, $data));
		return $node;
	}
	
	protected function getInner($xml, &$data) {
		$inner = $xml->createElement('div', 'item-icon-inner');
		$inner->appendChild($xml->createImgElement($this->getImageURL('image'), 'image', $this->imageSize));
		$inner->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		return $inner;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'icon';
			$obj['title'] = $this->getAttribute('title');
			$myImage = &$this->getImageObject('image');
			if ($myImage) {
				$obj['image'] = $myImage['id'];
			}
		}
		return $this->myObj;
	}

	public function getObjects(&$obj) {
		parent::getObjects($obj);

		$myImage = &$this->getImageObject('image');
		if ($myImage) {
			$obj['images'][$myImage['id']] = $myImage;
		}
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function style($style, $element) {
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
	}

}
