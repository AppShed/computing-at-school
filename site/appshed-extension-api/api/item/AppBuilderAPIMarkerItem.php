<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIMarkerItem extends AppBuilderAPILinkingItem {

	public function __construct($title, $subtitle, $longitude, $latitude) {
		parent::__construct('marker');
		$this->setTitle($title);
		$this->setSubTitle($subtitle);
		$this->setPosition($longitude, $latitude);
	}

	public function setTitle($title) {
		if (strlen($title) == 0) {
			$title = ' ';
		}
		$this->setAttribute('title', $title);
	}

	public function setSubTitle($title) {
		if (strlen($title) == 0) {
			$title = ' ';
		}
		$this->setAttribute('subtitle', $title);
	}

	public function setPosition($longitude, $latitude) {
		$this->setAttribute('longitude', $longitude);
		$this->setAttribute('latitude', $latitude);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " marker";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->setAttribute('data-latitude', $this->getAttribute('latitude'));
        $node->setAttribute('data-longitude', $this->getAttribute('longitude'));
		$node->appendChild($xml->createElement('div', array('class' => 'title', 'text' => $this->getAttribute('title'))));
		$subtitle = $this->getAttribute('subtitle');
		if (empty($subtitle)) {
			$xml->addClass($node, 'no-subtitle');
		}
		else {
			$node->appendChild($xml->createElement('div', array('class' => 'text', 'text' => $this->getAttribute('subtitle'))));
		}
		return $node;
	}

	public function getMarkerObject($xml, &$data) {
		return array(
			'title' => $this->getAttribute('title'),
			'subtitle' => $this->getAttribute('subtitle'),
			'latitude' => $this->getAttribute('latitude'),
			'longitude' => $this->getAttribute('longitude'),
			'html' => $xml->saveXML($this->getHTMLNode($xml, $data))
		);
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'location';
			$obj['latitude'] = $this->getAttribute('latitude');
			$obj['longitude'] = $this->getAttribute('longitude');
			$obj['title'] = $this->getAttribute('title');
			$obj['text'] = $this->getAttribute('subtitle');
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
		//$element->setAttribute(...);
	}

}
