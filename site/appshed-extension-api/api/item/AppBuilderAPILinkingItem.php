<?php

/**
 * @package AppBuilderAPI
 * @subpackage Items
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
abstract class AppBuilderAPILinkingItem extends AppBuilderAPIItem {

	/**
	 * Screen this item links to
	 * @var AppBuilderAPIScreen
	 */
	protected $screen;
	protected $elementId;
	protected $tab;
	protected $app;
	private $filename;
	private $jsonp;
	private $htmllink;
	private $variables = array();
	protected $postVariables = array();
	private $showArrow = true;
	private $weblinktype;
	private $backlink = false;
	private $js;
	private $prefixed = true;

	private function prepareURL($url, $post = true) {
		$que = strpos($url, '?') === false;
		foreach ($this->variables as $element) {
			$v = $element->getAttribute('variable');
			$url .= ($element->post ? "{{$v}}" : ($que ? '?' : '&') . "$v={{$v}}");
			$que = false;
		}
		if ($post) {
			foreach ($this->postVariables as $element) {
				$v = $element->getAttribute('variable');
				$url .= ($element->post ? "{{$v}}" : ($que ? '?' : '&') . "$v={{$v}}");
				$que = false;
			}
		}
		return $url;
	}

	public function setShowArrow($shown) {
		$this->showArrow = $shown;
	}
	
	public function setPrefixed($prefixed) {
		$this->prefixed = $prefixed;
	}

	public function setFileLink($type, $url = null, $filename = null) {
		$this->clearLink();
		if (empty($url)) {
			$pi = pathinfo($type);
			$url = $type;
			$type = strtolower(isset($pi['extension']) ? $pi['extension'] : 'default');
		}
		if (in_array($type, array('pdf', 'excel', 'doc', 'ppt', 'mp4', 'mp3', 'wav'))) {
			$this->setAttribute('linktype', $type);
			$this->setAttribute('href', $url);
			$this->filename = $filename;
		}
	}

	public function setVideoLink($url) {
		$this->clearLink();
		$this->setAttribute('linktype', 'video');
		$this->setAttribute('href', $url);
	}

	/**
	 * Make this item link to $screen
	 *
	 * @param AppBuilderAPIScreen $screen
	 */
	public function setScreenLink($screen, $elementId = null) {
		$this->clearLink();
		$this->setAttribute('linktype', $this->getLinkType($screen));
		$this->screen = $screen;
		$this->elementId = $elementId;
	}

	/**
	 * @version HTML Export
	 * @param int $id
	 */
	public function setExternalScreenLink($id) {
		$this->clearLink();
		$this->setAttribute('linktype', 'screen');
		$this->setAttribute('href', $id);
	}

	/**
	 * Make this item link to $tab
	 *
	 * @param AppBuilderAPITab $tab
	 */
	public function setTabLink($tab) {
		$this->clearLink();
		$this->setAttribute('linktype', 'tab');
		$this->tab = $tab;
	}

	/**
	 * @version HTML Export
	 * @param int $id
	 */
	public function setExternalTabLink($id) {
		$this->clearLink();
		$this->setAttribute('linktype', 'tab');
		$this->setAttribute('href', $id);
	}

	public function setToggleLink($titleAlternate, $variableName) {
		$this->clearLink();
		$this->setAttribute('linktype', 'toggle');
		$this->setAttribute('titlealternate', $titleAlternate);
		$this->setAttribute('defaultstatus', 'false');
		$this->setAttribute('variable', $variableName);
	}

	public function setWebLink($url, $type = 'normal') {
		$this->clearLink();
		$this->setAttribute('linktype', 'web');
		$this->setAttribute('href', $url);
		$this->weblinktype = $type;
	}

	public function setYoutubeLink($url) {
		$this->clearLink();
		$this->setAttribute('linktype', 'youtube');
		$this->setAttribute('href', $url);
	}

	public function setVimeoLink($url) {
		$this->clearLink();
		$this->setAttribute('linktype', 'vimeo');
		$this->setAttribute('href', $url);
	}

	public function setPhoneLink($phone) {
		if (preg_match('/^\+?[\d ]+$/', $phone)) {
			$this->clearLink();
			$phone = str_replace(' ', '', $phone);
			$this->setAttribute('linktype', 'phone');
			$this->setAttribute('href', $phone);
		}
	}

	public function setTwitterLink($username) {
		$this->clearLink();
		$this->setAttribute('linktype', 'twitter');
		$this->setAttribute('href', $username);
	}
	
	public function setRefreshLink($clickOnly = true) {
		$this->clearLink();
		$this->setAttribute('linktype', 'refresh');
		$this->setAttribute('refreshtype', $clickOnly);
	}

	public function setJsCodeLink($js) {
		$this->clearLink();
		$this->setAttribute('linktype', 'jscode');
		$this->js = $js;
	}

	public function setFacebookLink($facebookUrl) {
		$this->clearLink();
		$this->setAttribute('linktype', 'facebook');
		$this->setAttribute('href', $facebookUrl);
	}

	/**
	 * Make this item link to $app
	 *
	 * @param AppBuilderAPIApp $app
	 */
	public function setAppLink($app) {
		$this->clearLink();
		$this->setAttribute('linktype', 'app');
		if ($app instanceof AppBuilderAPIApp) {
			$this->app = $app;
		}
		else {
			$this->setAttribute('href', $app);
		}
	}

	public function setEmailLink($emailto, $emailsubject, $emailbody) {
		$this->clearLink();
		$this->setAttribute('linktype', 'email');
		$this->setAttribute('href', null);
		$this->setAttribute('emailto', $emailto);
		$this->setAttribute('emailsubject', $emailsubject);
		$this->setAttribute('emailbody', $emailbody);
	}

	public function setRemoteLink($link) {
		$this->clearLink();
		$this->setAttribute('linktype', 'items');
		$noQ = strpos($link, '?') === false;
		$this->setAttribute('xmlsrc', $link . ($noQ ? '?type=xml' : '&type=xml'));
		$this->jsonp = $link . ($noQ ? '?type=jsonp' : '&type=jsonp');
		$this->htmllink = $link . ($noQ ? '?type=html' : '&type=html');
	}

	public function setLoginLink($href) {
		$this->clearLink();
		$this->setAttribute('linktype', 'login');
		$this->setAttribute('href', $href);
	}

	public function setRegisterLink($href) {
		$this->clearLink();
		$this->setAttribute('linktype', 'register');
		$this->setAttribute('href', $href);
	}

	public function setRemoteXMLLink($xml, $jsonp = null, $html = null) {
		$this->clearLink();
		$this->setAttribute('linktype', 'items');
		$this->setAttribute('xmlsrc', $xml);
		$this->jsonp = $jsonp;
		$this->htmllink = $html;
	}

	public function addVariables() {
		foreach (func_get_args() as $element) {
			$this->variables[] = $element;
		}
	}

	public function addPostVariables() {
		$piece = array();
		$variables = $this->getAttribute('postvariables');
		if ($variables) {
			$piece = explode(',', $variables);
		}
		foreach (func_get_args() as $element) {
			$piece[] = $element->getAttribute('variable');
			$this->postVariables[] = $element;
		}
		$this->setAttribute('postvariables', implode(',', $piece));
	}

	public function setBackLink($screenId = null) {
		$this->clearLink();
		$this->backlink = $screenId;
		$this->setAttribute('linktype', 'back');
	}
	
	public function setBBMLink() {
		$this->clearLink();
		$this->setAttribute('linktype', 'bbm');
		$this->setAttribute('href', 'none');
	}

	private function clearLink() {
		$this->setAttribute('linktype', null);
		$this->setAttribute('href', null);
		$this->setAttribute('xmlsrc', null);
		$this->setAttribute('emailto', null);
		$this->setAttribute('emailsubject', null);
		$this->setAttribute('emailbody', null);
		$this->app = null;
		$this->tab = null;
		$this->screen = null;
		$this->elementId = null;
		$this->jsonp = null;
		$this->weblinktype = null;
		$this->filename = null;
		$this->backlink = false;
		$this->js = null;
	}

	public function setPost() {
		$this->setAttribute('method', 'post');
	}

	public function setGet() {
		$this->setAttribute('method', 'get');
	}

	private function getLinkType($screen) {
		switch (get_class($screen)) {
			case 'AppBuilderAPIListScreen' :
				$linktype = 'items';
				break;
			case 'AppBuilderAPIGalleryScreen' :
				$linktype = 'gallery';
				break;
			case 'AppBuilderAPIMapScreen' :
				$linktype = 'map';
				break;
			case 'AppBuilderAPIIconScreen' :
				$linktype = 'gallery';
				break;
			case 'AppBuilderAPIToggleItem' :
				$linktype = 'toggle';
				break;
			default:
				$linktype = 'items';
				break;
		}
		return $linktype;
	}

	public function hasScreenLink() {
		return $this->screen != null;
	}

	/* HTML Export */

	/**
	 * Get the html node for this element
	 * @param AppBuilderAPIDOMDocument $xml
	 * @return DOMElement
	 */
	public function getHTMLNode($xml, &$data) {
		$node = parent::getHTMLNode($xml, $data);
		$this->applyLink($xml, $node, $data);
		return $node;
	}
	
	protected function applyLink($xml, $node, &$data) {
		$this->applyLinkToNode($xml, $node, $data);
	}
	
	protected function applyLinkToNode($xml, $node, &$data) {
		switch ($linktype = $this->getAttribute('linktype')) {
			case 'login':
				$node->setAttribute('data-linktype', 'login');
				$node->setAttribute('data-href', $this->getAttribute('href'));
				break;
			case 'register':
				$node->setAttribute('data-linktype', 'register');
				$node->setAttribute('data-href', $this->getAttribute('href'));
				break;
			case 'jscode':
				$this->showArrow = false;
				$node->setAttribute('data-linktype', 'jscode');
				$node->setAttribute('data-href', $this->getIdType() . (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->getId());
				break;
			case 'items':
			case 'gallery':
			case 'map':
				if ($this->jsonp || $this->getAttribute('xmlsrc')) {
					$node->setAttribute('data-linktype', 'remote');
					$node->setAttribute('data-href', $this->prepareURL($this->jsonp));
				}
				else if ($this->screen) {
					$node->setAttribute('data-linktype', 'screen');
					$this->screen->getHTMLNode($xml, $data, true);
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->screen->getId());
					if (!empty($this->elementId)) {
						$node->setAttribute('data-element', $this->elementId);
					}
					//$s->setAttribute('data-parent-type', 'item');
					//$s->setAttribute('data-parent', $node->getAttribute('id'));
				}
				break;
			case 'tab':
				if (!empty($this->tab)) {
					$node->setAttribute('data-linktype', $linktype);
					$this->tab->getHTMLNode($xml, $data, true);
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->tab->getId());
					break;
				}
				else {
					$node->setAttribute('data-linktype', $linktype);
					$node->setAttribute('data-href', $this->getAttribute('href'));
					break;
				}
			case 'refresh':
				$node->setAttribute('data-linktype', 'refresh');
				$node->setAttribute('data-refreshtype', $this->getAttribute('refreshtype') ? 'any':'button' );
				break;
			case 'app':
				if (!empty($this->app)) {
					$node->setAttribute('data-linktype', $linktype);
					$this->app->getHTMLNode($xml, $data, true);
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->app->getId());
					break;
				}
			case 'screen':
				$node->setAttribute('data-linktype', $linktype);
				$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->getAttribute('href'));
				break;
			case 'email':
				if ($data['settings']['emailPreview']) {
					$screen = new AppBuilderAPIListScreen('Email');
					$screen->setId('email' . $this->getId());
					$screen->setUpdated(false);
					$screen->setEditable(false);
					$screen->setBack(isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : true);
					$screen->setFetchId($data['settings']['fetchscreen'] ? $data['settings']['fetchscreen']->getId() : null);

					$screen->addChild(new AppBuilderAPIInputItem('email_to', $this->getAttribute('emailto'), 'To'));
					$screen->addChild(new AppBuilderAPIInputItem('email_subject', $this->getAttribute('emailsubject'), 'Subject'));
					$screen->addChild(new AppBuilderAPITextAreaItem('email_body', $this->getAttribute('emailbody'), 'Message'));

					$send = new AppBuilderAPIButtonItem('Send');
					$send->setWebLink('mailto:{email_to}?subject={email_subject}&body={email_body}', 'external');
					$screen->addChild($send);
					$screen->getHTMLNode($xml, $data, true);
					$node->setAttribute('data-linktype', 'screen');
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $screen->getId());
				}
				else {
					$node->setAttribute('data-linktype', 'web');
					$node->setAttribute('data-href', 'mailto:' . $this->getAttribute('emailto') . '?subject=' . urlencode($this->getAttribute('emailsubject')) . '&body=' . urlencode($this->getAttribute('emailbody')));
					$node->setAttribute('data-weblinktype', 'external');
				}
				break;
			case 'phone':
				if ($data['settings']['telPreview']) {
					$screen = new AppBuilderAPIListScreen('Phone');
					$screen->setId('phone' . $this->getId());
					$screen->setUpdated(false);
					$screen->setScrolling(false);
					$screen->setEditable(false);
					$screen->setBack(isset($data['settings']['currentscreen']) ? $data['settings']['currentscreen'] : true);
					$screen->setFetchId($data['settings']['fetchscreen'] ? (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $data['settings']['fetchscreen']->getId() : null);
					$screen->addClass('phone-screen');
					$screen->setHeader(false);
					$screen->setTabs(false);

					$top = new AppBuilderAPILinkItem($this->getAttribute('href'));
					$top->addClass('top');
					$screen->addChild($top);

					$end = new AppBuilderAPILinkItem('End');
					$end->addClass('bottom');
					$end->setBackLink();
					$screen->addChild($end);

					$screen->getHTMLNode($xml, $data, true);
					$node->setAttribute('data-linktype', 'screen');
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $screen->getId());
				}
				else {
					$node->setAttribute('data-linktype', 'web');
					$node->setAttribute('data-href', 'tel:' . $this->getAttribute('href'));
					$node->setAttribute('data-weblinktype', 'confirm');
					$node->setAttribute('data-confirm-message', 'Click to make a call to ' . $this->getAttribute('href'));
					$node->setAttribute('data-okbtn', 'Call');
				}
				break;
			//file
			case 'pdf':
			case 'excel':
			case 'doc':
			case 'ppt':
			case 'mp4':
			case 'mp3':
			case 'wav':
			case 'video':
				$node->setAttribute('data-linktype', 'file');
				$node->setAttribute('data-href', $this->getAttribute('href'));
				if ($this->filename) {
					$node->setAttribute('data-filename', $this->filename);
				}
				break;
			case 'web':
			case 'youtube':
			case 'vimeo':
			case 'twitter':
			case 'facebook':
				$node->setAttribute('data-linktype', $linktype);
				$node->setAttribute('data-href', $this->getAttribute('href'));
				if ($this->weblinktype) {
					$node->setAttribute('data-weblinktype', $this->weblinktype);
				}
				break;
			case 'back':
				$node->setAttribute('data-linktype', $linktype);
				if ($this->backlink) {
					$node->setAttribute('data-href', (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->backlink);
				}
				break;
			case 'bbm':
				$node->setAttribute('data-linktype', 'bbm');
				break;
			case 'toggle':
			case 'app':
			default:
			//
		}

		if ($node->hasAttribute('data-linktype')) {
			$xml->addClass($node, 'link');
			if ($this->showArrow) {
				$node->appendChild($xml->createElement('div', 'link-arrow'));
			}
			else {
				$xml->addClass($node, 'link-no-arrow');
			}
			$node->setAttribute('x-blackberry-focusable', 'true');
		}
	}
	
	public function getJavascript(&$javascripts) {
		if($this->getAttribute('linktype') == 'jscode') {
			$javascripts[$this->getIdType() . (isset($data['settings']['prefix']) && $this->prefixed ? $data['settings']['prefix'] : '') . $this->getId()] = $this->js;
		}
	}

	/* JSON Export */

	public function &getObj() {
		if (!$this->myObj) {
			$obj = &parent::getObj();
			$obj['linktype'] = $this->getAttribute('linktype');
			$obj['showarrow'] = $this->showArrow;
			$obj['prefixed'] = $this->prefixed;
			switch ($obj['linktype']) {
				case 'items':
				case 'gallery':
				case 'map':
					if ($this->jsonp || $this->getAttribute('xmlsrc')) {
						$obj['linktype'] = 'remotexml';
						$obj['href'] = json_encode(array(
							'xml' => $this->prepareURL($this->getAttribute('xmlsrc'), false),
							'jsonp' => $this->prepareURL($this->jsonp)
								));
					}
					else if ($this->screen) {
						$obj['linktype'] = 'screen';
						$screenObj = &$this->screen->getObj();
						$obj['href'] = $screenObj['id'];

						$screenObj['parentType'] = 'item';
						$screenObj['parent'] = $obj['id'];
					}
					else {
						$obj['linktype'] = 'none';
					}
					break;
				case 'email':
					$email = array(
						'to' => $this->getAttribute('emailto'),
						'subject' => $this->getAttribute('emailsubject'),
						'body' => $this->getAttribute('emailbody')
					);
					$obj['href'] = json_encode($email);
					break;
				//file
				case 'pdf':
				case 'excel':
				case 'doc':
				case 'ppt':
				case 'mp4':
					$obj['linktype'] = 'file';
					$obj['href'] = $this->getAttribute('href');
				//toggle
				case 'toggle':
					$obj['linktype'] = 'none';
					break;
				case 'tab':
				case 'video':
				case 'web':
				case 'youtube':
				case 'vimeo':
				case 'phone':
				case 'twitter':
				case 'facebook':
				case 'app':
				case 'register':
				case 'login':
					$obj['href'] = $this->getAttribute('href');
					break;
				case 'back':
				case 'bbm':
					break;
				case 'refresh':
					$obj['refreshtype'] = $this->getAttribute('refreshtype');
					break;
				default:
					$obj['linktype'] = 'none';
			}
		}
		return $this->myObj;
	}

	public function getObjects(&$obj) {
		parent::getObjects($obj);
		if ($this->screen) {
			$screenObj = &$this->screen->getObj();
			$myObj = &$this->getObj();
			if(isset($myObj['screen'])) {
				$screenObj['back'] = $myObj['screen'];
			}
			$this->screen->getObjects($obj);
		}
	}

	/* XML Export */

	/*
	 * @deprecated since version 2.0
	 */
	public function getNode($xml) {
		$node = parent::getNode($xml);
		if ($this->screen) {
			$node->appendChild($this->screen->getNode($xml, false));
		}
		return $node;
	}

}
