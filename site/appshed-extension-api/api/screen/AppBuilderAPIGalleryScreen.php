<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIGalleryScreen extends AppBuilderAPIScreen {

	const TYPE = 'photo';
	
	public function __construct($title, $cols = 3) {
		parent::__construct($title);
		$this->setColumns($cols);
	}
	
	/**
	 * Add $item as a child of this screen
	 * @param AppBuilderAPIGalleryImageItem $item 
	 */
	public function addChild($item) {
		parent::addChild($item);
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " photo";
	}
	
	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml 
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data, $link = false) {
		if(!$this->node || !$link) {
			$this->innerScreen = new AppBuilderAPIGalleryScreenInner($this->title);
			$this->innerScreen->copyStyles($this);
			$this->innerScreen->setId($this->getId());
			$this->innerScreen->setFetchId($data['settings']['fetchscreen'] ? $data['settings']['fetchscreen']->getId() : $this->getId());
			$this->innerScreen->setFetchUrl($this->fetchUrl);
			$this->innerScreen->setUpdated(false);
			$this->innerScreen->children = array_filter($this->children, function ($child) {
				return !$child->getDisableInner();
			});
			$this->innerScreen->setBack($this);

			$this->node = parent::getHTMLNode($xml, $data);
			$this->innerScreen->getHTMLNode($xml, $data);
		}
		return $this->node;
	}
	
	/**
	 *
	 * @param DOMElement $items
	 * @param DOMDocument $xml
	 * @param array $data 
	 */
	protected function addHTMLChildren($items, $xml, &$data, $css, &$javascripts) {
		$items->appendChild($itemsInner = $xml->createElement('div', 'items-inner'));
		$itemsInner->appendChild($table = $xml->createElement('table'));
		$table->appendChild($row = $xml->createElement('tr'));
		
		$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
		$data['settings']['currentscreen'] = $this->getId();
		
		$i = 0;
		$cols = $this->getStyle('cols');
		if(!$cols) {
			$cols = 3;
		}
		foreach ($this->children as $child) {
			$child->getCSS($css, $data);
			$img = $child->getImageURL('thumbnail');
			if(empty($img)) {
				$img = $child->getImageURL('image');
			}
			$gi = new AppBuilderAPIGalleryImageItemOuter($img);
			$gi->copyStyles($child);
			foreach($child->getClasses() as $class) {
				$gi->addClass($class);
			}
			$gi->setImageSize($child->getThumbnailSize());
			$id = $child->getId(true);
			$gi->setId($id);
			if(!$child->getDisableInner()) {
				$gi->setScreenLink($this->innerScreen, '#' . $child->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $child->getId());
			}
			$c = $gi->getHTMLNode($xml, $data);
			if ($c) {
				if($i == $cols) {
					$table->appendChild($row = $xml->createElement('tr'));
					$i = 0;
				}
				$i++;
				$row->appendChild($c);
			}
		}
		while($i < $cols) {
			$row->appendChild($xml->createElement('td'));
			$i++;
		}
		
		$data['settings']['currentscreen'] = $s;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'photo';
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	protected function style($style, $element) {
		$element->setImageAttribute('backgroundimage', $this->getStyle('gallerybackground'));
		$element->setAttribute('cols', $this->getStyle('cols'));
	}

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml, $root = true) {
		$node = parent::getNode($xml);

		$mapNode = new AppBuilderAPIElement('gallery');
		$mapNode->setAttribute('type', 'gallery');
		$mapNode->setAttribute('title', $this->title);
		$this->applyStyle($mapNode);
		$mapNode = $mapNode->getNode($xml);
		$mapNode->appendChild($node);

		$itemsNode = new AppBuilderAPIElement('items');
		$this->applyStyle($itemsNode);
		$itemsNode = $itemsNode->getNode($xml);
		$itemsNode->appendChild($mapNode);

		$node = $itemsNode;

		if ($root) {
			$rootNode = $this->getRootNode($xml);
			$rootNode->appendChild($node);
			$node = $rootNode;
		}

		return $node;
	}

}

class AppBuilderAPIGalleryScreenInner extends AppBuilderAPIScreen {
	const TYPE = 'photo';
	
	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " gallery";	
	}
	
	public function getId() {
		return 'gallery' . parent::getId();
	}
}
