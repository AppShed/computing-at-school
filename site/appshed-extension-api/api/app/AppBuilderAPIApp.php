<?php

/**
 * @package AppBuilderAPI
 * @subpackage App
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPIApp extends AppBuilderAPIElement {

	private $tabs = array();
	private $node;
	private $updated = true;
	private $name;
	private $description;
	private $previewUrl;
	private $webviewUrl;
	private $icon;
	private $fetchUrl;
	private $ads;
	private $css;
	private $hasSplash = true;
	private $login = false;
	private $register = false;
	private $js;
	private $flag;

	public function __construct($title = null, $imgURL = null) {
		parent::__construct('app');
		$this->setName($title);
		$this->setIcon($imgURL);
	}

	public function setName($name) {
		$this->name = $name;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function setPreviewUrl($url) {
		$this->previewUrl = $url;
	}
	
	public function setWebviewUrl($url) {
		$this->webviewUrl = $url;
	}
	
	public function setSecureScreens($loginScreenId,$registerScreenId) {
		$this->login = $loginScreenId;
		$this->register = $registerScreenId;
	}

	public function setIcon($src) {
		$this->icon = $src;
		$this->setImageAttribute('image', $src);
	}
	
	public function setFlag($flag) {
		$this->flag = $flag;
	}

	/**
	 * Add a tab to this app
	 * @param AppBuilderAPITab $tab 
	 */
	public function addTab($tab) {
		$this->tabs[] = $tab;
	}

	/* HTML Export */

	protected function getClass() {
		return "app";
	}

	protected function getIdType() {
		return "app";
	}

	public function setUpdated($updated) {
		$this->updated = $updated;
	}

	public function setFetchUrl($fetch) {
		$this->fetchUrl = $fetch;
	}

	public function showAds($ads) {
		$this->ads = $ads;
	}

	public function setCSSText($css) {
		$this->css = $css;
	}
	
	public function setHasSplash($hasSplash) {
		$this->hasSplash = $hasSplash;
	}
	
	public function setJavascript($js) {
		$this->js = $js;
	}

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml 
	 * @param array $data
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data, $link = false) {
		if (!$this->node && !$link) {
			$css = new AppBuilderAPICSSDocument();
			
			if($this->css) {
				$idselector = $css->getIdSelector($this->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId());
				$css->addCSS($this->css, $idselector);
			}
			
			$this->getCSS($css, $data);

			$this->node = parent::getHTMLNode($xml, $data);
			if ($this->name) {
				$this->node->setAttribute('data-name', $this->name);
			}
			
			if ($this->description) {
				$this->node->setAttribute('data-description', $this->description);
			}
			
			if ($this->flag) {
				$this->node->setAttribute('data-flag', $this->flag);
			}
			
			if ($this->previewUrl) {
				$this->node->setAttribute('data-preview-url', $this->previewUrl);
			}
			
			if ($this->webviewUrl) {
				$this->node->setAttribute('data-webview-url', $this->webviewUrl);
			}
			
			if ($this->icon) {
				$this->node->setAttribute('data-icon', $this->icon);
				$idselector = $css->getIdSelector($this->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId());
				$css->addRule(array(".android .phone-navigator $idselector.app .app-navigator .screen .header .back"), 'background-image', $css->getURLValue($this->icon));
				$css->addRule(array(".blackberry .phone-navigator $idselector.app .app-navigator .screen .header"), 'background-image', $css->getURLValue($this->icon));
			}

			if ($this->fetchUrl) {
				$this->node->setAttribute('data-fetch-url', $this->fetchUrl);
			}

			$this->node->appendChild($navigator = $xml->createElement('div', 'app-navigator'));
			$navigator->appendChild($navinner = $xml->createElement('div', 'app-navigator-inner'));
			$navinner->appendChild($xml->createElement('div', 'app-navigator-inner-sides'));
			$navinner->appendChild($xml->createElement('div', 'app-navigator-inner-sides'));

			if ($this->ads) {
				$xml->addClass($navigator, 'ads');
				$this->node->appendChild($xml->createElement('div', 'ad-holder'));
			}

			$this->node->appendChild(($tabbarOuter = $xml->createElement('div', 'tab-bar')));
			$tabbarOuter->appendChild(($tabbar = $xml->createElement('table')));
			$tabbar->appendChild(($tabbarinner = $xml->createElement('tr', 'tar-bar-inner')));

			//take the first tab and insert its screen directly into the navigator
			/* $firstTab = array_shift($this->tabs);
			  $tabbar->appendChild($tabNode = $firstTab->getHTMLNode($xml, &$data));
			  $xml->addClass($tabNode, 'selected');
			  if($firstTab->hasScreenLink()) {
			  $navigator->appendChild($firstTab->getScreen()->getHTMLNode($xml, &$data));
			  } */

			foreach ($this->tabs as $tab) {
				$tabbarinner->appendChild($tab->getHTMLNode($xml, $data));
				$tab->getCSS($css, $data);
			}

			//put that tab back on the beginning
			//array_unshift($this->tabs, $firstTab);

			$data['app'][(isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId()] = array(
				'html' => "<style scoped>" . $css->toString() . "</style>" . $xml->saveXML($this->node),
				'splashhtml' => $this->hasSplash ? "<style scoped>" . $css->toSplashString() . "</style><div class=\"splash\" id=\"app" . $this->getId() . "\"></div>" : null,
				'updated' => $this->updated === true ? time() : $this->updated,
				'secure' => array('login'=>$this->login,'register'=>$this->register),
				'css' => '',
				'javascript' => $this->js);
		}
		return $this->node;
	}
	
	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['name'] = $this->name;
			$obj['description'] = $this->description;
			$myImage = &$this->getImageObject('image');
			if ($myImage) {
				$obj['image'] = $myImage['id'];
			}
			$mySplashImage = &$this->getImageStyleObject('splash');
			if ($mySplashImage) {
				$obj['splash'] = $mySplashImage['id'];
			}
			$obj['css'] = $this->css;
		}
		return $this->myObj;
	}

	const objType = 'apps';

	public function getObjects(&$obj) {
		parent::getObjects($obj);
		$myObj = &$this->getObj();
		
		$myImage = &$this->getImageObject('image');
		if ($myImage) {
			$obj['images'][$myImage['id']] = $myImage;
		}
		$mySplashImage = &$this->getImageStyleObject('splash');
		if ($mySplashImage) {
			$obj['images'][$mySplashImage['id']] = $mySplashImage;
		}
		
		$ordering = 1;
		foreach ($this->tabs as $child) {
			$childObj = &$child->getObj();
			if ($childObj) {
				$childObj['app'] = $myObj['id'];
				$childObj['ordering'] = $ordering++;
			}
			$child->getObjects($obj);
		}
	}

}
