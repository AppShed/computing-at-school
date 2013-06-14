<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIGalleryImageItem extends AppBuilderAPILinkingItem {

	private $imageSize;
	private $thumbnailSize;
	private $disableInner = false;

	public function __construct($imageurl, $thumbnailurl = null) {
		parent::__construct('galleryimage');

		$this->setImage($imageurl);
		if ($thumbnailurl != null) {
			$this->setThumbnail($thumbnailurl);
		}
	}

	public function setImage($url) {
		$this->setImageAttribute('image', $url);
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
	
	public function getImageSize() {
		return $this->imageSize;
	}
	
	public function setThumbnailSize($width, $height = null) {
		if(is_array($width)) {
			$this->thumbnailSize = $width;
		}
		else if($width != null && $height != null) {
			$this->thumbnailSize = array('width' => $width, 'height' => $height);
		}
	}
	
	public function getThumbnailSize() {
		return isset($this->thumbnailSize) ? $this->thumbnailSize : $this->imageSize;
	}
	
	public function getDisableInner() {
		return $this->disableInner;
	}

	/**
	 * Set this to make this item only appear in the overview, but not fullscreen
	 * @param bool $disableInner
	 */
	public function setDisableInner($disableInner) {
		$this->disableInner = $disableInner;
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . ' gallery';
	}
	
	public function getId($plain = false) {
		if($plain) {
			return parent::getId();
		}
		return 'gallery' . parent::getId();
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($xml->createImgElement($this->getImageURL('image'), 'image'));
		return $node;
	}
	
	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'photo';
			$myImage = &$this->getImageObject('image');
			if ($myImage) {
				$obj['image'] = $myImage['id'];
			}
			$myThumbImage = &$this->getImageObject('thumbnail');
			if ($myThumbImage) {
				$obj['thumbnail'] = $myThumbImage['id'];
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
		
		$myThumbImage = &$this->getImageObject('thumbnail');
		if ($myThumbImage) {
			$obj['images'][$myThumbImage['id']] = $myThumbImage;
		}
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		parent::style($style, $element);
		$element->setAttribute('titlecolor', $style->getStyle('titlecolor'));
	}

}

class AppBuilderAPIGalleryImageItemOuter extends AppBuilderAPILinkingItem {
	
	const HTML_TAG = 'td';
	private $imageSize;
	
	public function __construct($imageurl) {
		parent::__construct('galleryimage');

		$this->setImage($imageurl);
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
		return parent::getClass() . ' photo';
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$imageDiv = $xml->createElement('div', array('class' => 'image-container'));
		$imageDiv->appendChild($xml->createImgElement($this->getImageURL('image'), 'image', $this->imageSize));
		$node->appendChild($imageDiv);
		return $node;
	}
}