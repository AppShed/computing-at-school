<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIImageItem extends AppBuilderAPILinkingItem {

	private $imageSize;
	
	public function __construct($url) {
		parent::__construct('image');
		$this->setImage($url);
	}

	public function setImage($url) {
		$this->setImageAttribute('image', $url);
	}
	
	public function setImageSize($width, $height = null) {
		/*if(is_array($width)) {
			$this->imageSize = $width;
		}
		else {
			$this->imageSize = array('width' => $width, 'height' => $height);
		}*/
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . ' image';
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($xml->createImgElement($this->getImageURL('image'), 'image', $this->imageSize));
		return $node;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'image';
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
	protected function styles($style, $element) {
		$element->setAttribute('align', $style->getStyle('align'));
		$element->setAttribute('height', $style->getStyle('height'));
		$element->setAttribute('width', $style->getStyle('width'));
		$element->setAttribute('enablefavourite', $style->getStyle('enablefavourite'));
		$element->setAttribute('defaultstate', $style->getStyle('defaultstate'));
		$element->setAttribute('imagey', $style->getStyle('imagey'));
		$element->setAttribute('imagex', $style->getStyle('imagex'));
		$element->setAttribute('paddingbottom', $style->getStyle('paddingbottom'));
		$element->setAttribute('variable', $style->getStyle('variable'));
	}

}
