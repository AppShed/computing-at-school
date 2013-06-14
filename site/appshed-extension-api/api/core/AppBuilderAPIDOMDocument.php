<?php
/**
 * Helper Class for the HTML Export
 * Provides extensions to DOMDocument to easily create elements
 * @package AppBuilderAPI
 * @subpackage  Core
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIDOMDocument extends DOMDocument {

	/**
	 * Create a new domelement with either a class or array of attributes given
	 * @param string $tag
	 * @param string|array $attributes
	 * @return DOMElement 
	 */
	public function createElement($tag, $attributes = null) {
		$node = parent::createElement($tag);
		$text = false;
		if (is_string($attributes)) {
			$node->setAttribute('class', $attributes);
		}
		else if (is_array($attributes)) {
			foreach ($attributes as $key => $value) {
				if ($key == 'text') {
					$node->appendChild($this->createTextNode($value));
					$text = true;
				}
				else {
					$node->setAttribute($key, $value);
				}
			}
		}
		if(!$text && !in_array($tag, array('img', 'br', 'input'))) {
			$node->appendChild($this->createTextNode(''));
		}
		return $node;
	}

	/**
	 * Add a class to the element
	 * @param DOMElement $element
	 * @param string $class 
	 */
	public function addClass($node, $class) {
		$c = $node->getAttribute('class');
		$c .= " $class";
		$node->setAttribute('class', $c);
	}
	
	public function createImgElement($src, $attributes, $imageSize = null) {
		if(empty($src)) {
			$tag = 'div';
		}
		else {
			$tag = 'img';
			if(is_array($attributes)) {
				$attributes['src'] = $src;
			}
			else {
				$attributes = array(
					'class' => $attributes,
					'src' => $src
				);
			}
			if($imageSize) {
				$attributes = array_merge($attributes, $imageSize);
			}
		}
		return $this->createElement($tag, $attributes);
	}
}
