<?php

/**
 * @package AppBuilderAPI
 * @subpackage Screens
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
abstract class AppBuilderAPIScreen extends AppBuilderAPIElement {

	protected $children = array();
	private $back = true;
	private $tab = false;
	protected $title;
	private $header = true;
	private $tabs = true;
	protected $node;
	private $updated = true;
	protected $scrolling = true;
	protected $fetchId = false;
	protected $fetchUrl = false;
	protected $status = false;
	protected $secured = false;
	private $css;

	const TYPE = 'screen';

	public function __construct($title) {
		parent::__construct('items');
		$this->setTitle($title);
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setBackEnabled($enabled) {
		$this->back = $enabled;
	}

	public function setBack($back) {
		$this->back = $back;
	}

	public function setTab($tab) {
		$this->tab = $tab;
	}

	public function setScrolling($scr) {
		$this->scrolling = $scr;
	}

	public function setUpdated($updated) {
		$this->updated = $updated;
	}

	public function setFetchId($fetch) {
		$this->fetchId = $fetch;
	}

	public function setFetchUrl($fetch) {
		$this->fetchUrl = $fetch;
	}

	/**
	 * make screen the secured, if passed group then
	 * only users from this group can have an access
	 * 
	 * @param type $group 
	 */
	
	public function setSecured($group='any') {
		$this->secured = $group;
	}
	
	public function setCSSText($css) {
		$this->css = $css;
	}

	public function getCSSText() {
		return $this->css;
	}

	/**
	 * Add $item as a child of this screen
	 * @param AppBuilderAPIItem $item 
	 */
	public function addChild($item) {
		$this->children[] = $item;
	}
	
	/**
	 * can be float or false to hide header
	 * @param type $header 
	 */
	public function setHeader($header) {
		$this->header = $header;
	}
	
	/**
	 * can be float or false to hide tabs
	 * @param type $tabs 
	 */
	public function setTabs($tabs) {
		$this->tabs = $tabs;
	}
	
	/**
	 * can be float or black or normal
	 * @param type $status 
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/* HTML Export */

	protected function getClass() {
		return parent::getClass() . " screen";
	}

	protected function getIdType() {
		return "screen";
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml 
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data, $link = false) {
		if (!$this->node || !$link) {
			$css = new AppBuilderAPICSSDocument();
			if ($this->css) {
				$idselector = $css->getIdSelector($this->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId());
				$css->addCSS($this->css, $idselector);
			}
			$javascripts = array();
			$this->node = $this->getHTMLNodeBase($xml, $data, $css, $javascripts);
			if($this->secured){
				$this->node->setAttribute('data-secured', $this->secured);
			}
			$data['screen'][(isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId()] = array(
				'html' => "<style scoped>" . $css->toString() . "</style>" . $xml->saveXML($this->node),
				'updated' => $this->updated === true ? time() : $this->updated,
				'css' => '',
				'secured'=> $this->secured,
				'javascripts' => $javascripts);
		}
		return $this->node;
	}

	protected function getHTMLNodeBase($xml, &$data, $css, &$javascripts = array()) {
		$this->node = parent::getHTMLNode($xml, $data);

		$this->node->setAttribute('data-screentype', static::TYPE);
		if($this->secured){
			$xml->addClass($this->node, 'secured');
		}
		$this->getCSS($css, $data);
		if ($this->tabs === 'float') {
			$this->node->setAttribute('data-tabs', 'float');
		}
		else if (!$this->tabs) {
			$this->node->setAttribute('data-tabs', 'hide');
		}

		if ($this->header === 'float') {
			$xml->addClass($this->node, 'float-header');
		}
		else if (!$this->header) {
			$xml->addClass($this->node, 'hide-header');
		}
		
		if($this->status === 'float') {
			$this->node->setAttribute('data-status', 'float');
		}
		else if($this->status === 'black') {
			$this->node->setAttribute('data-status', 'black');
		}
		
		if ($this->tab) {
			$this->node->setAttribute('data-tab', $this->tab);
		}

		if ($this->fetchId) {
			$this->node->setAttribute('data-fetch-id', (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->fetchId);
		}

		if ($this->fetchUrl) {
			$this->node->setAttribute('data-fetch-url', $this->fetchUrl);
		}
		
		$this->node->appendChild($navbar = $xml->createElement('div', 'header'));
		$navbar->setAttribute('x-blackberry-focusable', 'true');
		$navbar->appendChild($xml->createElement('div', 'background'));
		if ($this->back || $this->back === 0) {
			$navbar->appendChild($back = $xml->createElement('div', array('class' => 'back', 'data-linktype' => 'back', 'text' => 'Back')));
			if ($this->back instanceof AppBuilderAPIScreen) {
				$this->back->getHTMLNode($xml, $data, true);
				$back->setAttribute('data-href', (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->back->getId());
			}
			else if ($this->back !== true) {
				$back->setAttribute('data-href', (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->back);
			}
			$back->setAttribute('x-blackberry-focusable', 'true');
		}
		$navbar->appendChild($title = $xml->createElement('div', 'title'));
		
		$title->appendChild($xml->createElement('span', array('text' => $this->title)));

		$items = $xml->createElement('div', 'items' . ($this->scrolling ? ' scrolling' : ''));
		$this->node->appendChild($items);
		$headButtons = $this->addHTMLChildren($items, $xml, $data, $css, $javascripts);
		if (is_array($headButtons)) {
			foreach ($headButtons as $b) {
				$c = $b->getHTMLNode($xml, $data);
				if ($c) {
					$navbar->appendChild($c);
				}
			}
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
		$s = isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : null;
		$data['settings']['currentscreen'] = $this->getId();
		$headButtons = array();
		foreach ($this->children as $child) {
			if (get_class($child) != 'AppBuilderAPIHeaderButtonItem') {
				$c = $child->getHTMLNode($xml, $data);
				if ($c) {
					$itemsInner->appendChild($c);
				}
				$child->getCSS($css, $data);
				$child->getJavascript($javascripts);
			}
			else {
				$headButtons[] = $child;
			}
		}
		$data['settings']['currentscreen'] = $s;
		return $headButtons;
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['name'] = $this->title;
			$obj['tabs'] = $this->tabs;
			$obj['header'] = $this->header;
			$obj['css'] = $this->css;
			if (!$this->back) {
				$obj['back'] = false;
			}
		}
		return $this->myObj;
	}

	const objType = 'screens';

	public function getObjects(&$obj) {
		parent::getObjects($obj);
		$myObj = &$this->getObj();
		$ordering = 1;
		foreach ($this->children as $child) {
			$childObj = &$child->getObj();
			if ($childObj) {
				$childObj['screen'] = $myObj['id'];
				$childObj['ordering'] = $ordering++;
			}
			$child->getObjects($obj);
		}
	}

	/* XML Export */

	/**
	 * Get the xml element for this item
	 * @param DOMDocument $xml
	 * @return DOMElement
	 * @deprecated since version 2.0
	 */
	public function getNode($xml, $root = true) {
		$node = parent::getNode($xml);
		foreach ($this->children as $key => $child) {
			$n = $child->getNode($xml);
			$node->appendChild($n);
			if ($child->getHrAfter() && !($this->children[$key + 1] instanceof AppBuilderAPIHrItem)) {
				$hr = new AppBuilderAPIHrItem();
				$hr->setColor($child->getStyle('hrcolor'));
				$hr->setHeight($child->getStyle('hrheight'));
				$node->appendChild($hr->getNode($xml));
			}
		}
		return $node;
	}

	/*
	 * @deprecated since version 2.0
	 */
	protected function getRootNode($xml) {
		$rootNode = new AppBuilderAPIElement('screen');
		$rootNode->setAttribute('updatedate', gmdate('j/n/Y H:i:s'));
		$rootNode->setAttribute('tabid', static::tabId());
		$rootNode->setAttribute('title', $this->title);
		$this->applyStyle($rootNode);
		$rootNode = $rootNode->getNode($xml);
		return $rootNode;
	}

	/*
	 * @deprecated since version 2.0
	 */
	public static function tabId() {
		if (isset($_REQUEST['tab_id'])) {
			return $_REQUEST['tab_id'];
		}
		else {
			return static::id();
		}
	}

}
