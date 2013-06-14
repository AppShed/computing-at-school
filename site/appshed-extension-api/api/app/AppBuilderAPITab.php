<?php

/**
 * @package AppBuilderAPI
 * @subpackage App
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPITab extends AppBuilderAPILinkingItem {
	
	private $title;
	private $imgURL;
	private $node;
	
	const HTML_TAG = 'td';
	
	public function __construct($title, $imgURL = null) {
		parent::__construct('tab');
		$this->setTitle($title);
		$this->setImage($imgURL);
		$this->setShowArrow(false);
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setImage($url) {
		$this->imgURL = $url;
		$this->setImageAttribute('image', $url);
	}
	
	public function getScreen() {
		return $this->screen;
	}
	
	/* HTML Export */

	protected function getClass() {
		return implode(' ', $this->classes) . " tab";
	}
	
	protected function getIdType() {
		return "tab";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml 
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data, $link = false) {
		if(!$this->node || !$link) {
			$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
			$data['settings']['currentscreen'] = false;
			
			$this->node = parent::getHTMLNode($xml, $data);
			
			$data['settings']['currentscreen'] = $s;

			$this->node->appendChild($inner = $xml->createElement('div', 'tab-inner'));

			$inner->appendChild($xml->createElement('div', array('class'=>'label', 'text'=>$this->title)));
			$inner->appendChild($xml->createImgElement($this->imgURL, 'icon'));
		}
		return $this->node;
	}
	
	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['name'] = $this->title;
			$myImage = &$this->getImageObject('image');
			if ($myImage) {
				$obj['image'] = $myImage['id'];
			}
		}
		return $this->myObj;
	}
	
	const objType = 'tabs';
	
	public function getObjects(&$obj) {
		parent::getObjects($obj);
		$myImage = &$this->getImageObject('image');
		if ($myImage) {
			$obj['images'][$myImage['id']] = $myImage;
		}
	}
}
