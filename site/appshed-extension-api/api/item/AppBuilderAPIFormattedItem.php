<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIFormattedItem extends AppBuilderAPILinkingItem {

	private $html;
	private $rawinsert = false;

	public function __construct($html) {
		parent::__construct('html');
		$this->setHTML($html);
	}

	public function setHTML($html) {
		$this->html = $html;
	}

	public function set($html) {
		$this->html = $html;
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . ' html';
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$node->appendChild($htmlNode = $xml->createElement('div', 'html'));
		
		$html = $this->html;
		
		if($html != '') {
			$fragDoc = new DOMDocument();
			@$fragDoc->loadHTML('<?xml version="1.0" encoding="utf-8" standalone="yes"?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/ loose.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>' . $html . '</body></html>');
			if($fragDoc->hasChildNodes()) {
				$body = $fragDoc->getElementsByTagName('body')->item(0);
				if($body->hasChildNodes()) {
					for($i = 0;$i < $body->childNodes->length;$i++) {
						$iNode = $body->childNodes->item($i);
						$importedNode = $xml->importNode($iNode, true);
						$htmlNode->appendChild($importedNode);
					}

					$this->checkNode($xml, $htmlNode);
				}
			}
			
		}
		return $node;
	}

	/**
	 *
	 * @param DOMNode $node 
	 */
	private function checkNode($xml, $node) {
		if($node->hasChildNodes()) {
			for($i = 0;$i < $node->childNodes->length;$i++) {
				$this->checkNode($xml, $node->childNodes->item($i));
			}
		}
		else if($node instanceof DOMElement && !in_array($node->tagName, array('img', 'br'))) {
			$node->appendChild($xml->createTextNode(''));
		}
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['type'] = 'html';
			$obj['text'] = $this->html;
		}
		return $this->myObj;
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function setRaw() {
		$this->rawinsert = true;
	}

	/*
	 * @deprecated since version 2.0
	 */
	protected function styles($style, $element) {
		$element->setAttribute('height', $style->getStyle('height'));
		$element->setAttribute('color', $style->getStyle('color'));
		$element->setAttribute('enablefavourite', $style->getStyle('enablefavourite'));
		$this->color = $style->getStyle('color');
	}

	/**
	 *
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml) {
		$node = parent::getNode($xml);
		$contentNode = $xml->createElement('content');
		if (!$this->rawinsert) {
			$contentNode->appendChild($xml->createCDATASection($this->formatHTML($this->html, $this->color)));
		}
		else {
			$contentNode->appendChild($xml->createCDATASection($this->html));
		}
		$node->appendChild($contentNode);
		return $node;
	}

	/*
	 * @deprecated since version 2.0
	 */
	private function formatHTML($str, $color) {
		$head = '<!DOCTYPE HTML><html><body><div id="myiddivunique" style="color: rgb(' . $color . '); width:320px; ">';
		$bottom = '</div></body></html>';
		$str = $head . $str . $bottom;
		$str = str_replace('  ', '', $str);
		$str = str_replace("\n", '', $str);
		$str = str_replace("\t", '', $str);
		return $str;
	}

}
