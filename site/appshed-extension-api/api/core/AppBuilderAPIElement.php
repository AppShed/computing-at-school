<?php

/**
 * @package AppBuilderAPI
 * @subpackage Core
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIElement extends AppBuilderAPIStyle {

	private $id;
	private $tagName;
	private $attributes;
	protected $editable = true;
	const HTML_TAG = 'div';

	/**
	 * Create a new item
	 * @param string $tagName 
	 */
	protected function __construct($tagName) {
		$this->id = static::id();
		$this->tagName = $tagName;
		$this->attributes = array();
	}
	
	/**
	 * Only for use in by appbuilder
	 * @param int $id 
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	protected function setAttribute($name, $value) {
		if ($value != null) {
			$this->attributes[$name] = $value;
			$this->myObj = null;
		}
	}
	
	public function getAttribute($name) {
		if(isset ($this->attributes[$name])){
			return $this->attributes[$name];
		}
		return false;
	}
	
	public function hasAttribute($name) {
		return isset($this->attributes[$name]);
	}

	protected function setImageAttribute($name, $url) {
		if ($url != null) {
			$this->attributes[$name] = basename($url);
			$this->attributes["{$name}url"] = $url;
			$this->images[$name] = null;
			$this->myObj = null;
		}
	}

	protected function unsetAttribute($name) {
		unset($this->attributes[$name]);
		$this->myObj = null;
	}
	
	protected function unsetImageAttribute($name) {
		unset($this->attributes[$name]);
		unset($this->attributes["{$name}url"]);
		$this->images[$name] = null;
		$this->myObj = null;
	}

	/* HTML Export */
	
	protected $classes = array();
	
	public function addClass($class) {
		$this->classes[] = $class;
	}
	
	public function getClasses() {
		return $this->classes;
	}
	
	protected function getClass() {
		return implode(' ', $this->classes);
	}
	
	protected function getIdType() {
		return "";
	}
	
	public function setEditable($editable) {
		$this->editable = $editable;
	}


	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = $xml->createElement(static::HTML_TAG, $this->getClass());
		$node->setAttribute('id', $this->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId());
		if(!$this->editable) {
			$node->setAttribute('data-editable', 'false');
		}
		return $node;
	}

	protected function getImageURL($name) {
		return $this->getAttribute("{$name}url");
	}
	
	/* JSON Export */

	protected $myObj;

	public function &getObj() {
		if (!$this->myObj) {
			$this->myObj = array(
				'id' => $this->id,
				'classes' => &$this->classes
			);
		}
		return $this->myObj;
	}

	const objType = 'unknown';

	public function getObjects(&$obj) {
		parent::getObjects($obj);
		$myObj = &$this->getObj();
		if ($myObj) {
			$myStyle = &$this->getStyleObj();
			if ($myStyle) {
				$myObj['style'] = $myStyle['id'];
				$obj['styles'][$myStyle['id']] = &$myStyle;
			}
			$obj[static::objType][$myObj['id']] = &$myObj;
		}
	}
	
	private $images = array();

	protected function &getImageObject($name) {
		if (!isset($this->images[$name])) {
			if (isset($this->attributes[$name])) {
				$image = false;
				foreach($this->images as &$i) {
					if($i['src'] == $this->attributes["{$name}url"]) {
						$image = &$i;
					}
				}
				if(!$image) {
					$image = array(
						'id' => self::id(),
						'name' => $this->attributes[$name],
						'src' => $this->attributes["{$name}url"],
						'type' => static::getExtension($this->attributes[$name]),
						'mime' => static::getMime($this->attributes[$name])
					);
				}
				$this->images[$name] = &$image;
			}
			else {
				$this->images[$name] = null;
			}
		}
		return $this->images[$name];
	}
	
	/* XML Export */

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml) {
		$node = $xml->createElement($this->tagName);
		$node->setAttribute('id', $this->id);
		$this->applyStyle($this);
		foreach ($this->attributes as $key => $value) {
			$node->setAttribute($key, $value);
		}
		return $node;
	}

	/**
	 *
	 * @param AppBuilderAPIElement $element 
	 */
	protected function applyStyle($element) {
		$this->style($this, $element);
	}

	protected function style($style, $element) {
		
	}
}
