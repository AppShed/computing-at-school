<?php

/**
 * @package AppBuilderAPI
 * @subpackage Core
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
abstract class AppBuilderAPIStyle {

	private static $ID = null;
	private $styleObj;
	private $styles = array();
	private $styleImages = array();
	
	public static function id() {
		if (self::$ID == null) {
			if (isset($_REQUEST['itemid'])) {
				$i = substr(str_replace('item', '', $_GET['itemid']), -3);
			}
			else {
				$i = '777';
			}
			//self::$ID = '7' . $i . substr(time(), -3) . '000';
			self::$ID = 0;
		}
		return "a" . self::$ID++;
	}

	private function setStyle($name, $value) {
		if ($value === null) {
			unset($this->styles[$name]);
		}
		else {
			$this->styles[$name] = $value;
		}
		$this->styleObj = null;
	}
	
	private function setImageStyle($name, $url) {
		$this->setStyle($name, $url);
		$this->styleImages[$name] = false;
	}
	
	protected function getStyleImageURL($name) {
		return $this->getStyle($name);
	}
	
	/**
	 * copy styles from $from to this
	 * @param AppBuilderAPIStyle $from 
	 */
	public function copyStyles($from) {
		$this->styles = $from->styles;
	}

	protected static function getExtension($name) {
		$pi = pathinfo($name);
		return $pi['extension'];
	}

	protected static function getMime($name) {
		$type = self::getExtension($name);
		switch ($type) {
			case 'png':
				return 'image/png';
			case 'gif':
				return 'image/gif';
			case 'jpg':
			case 'jpeg':
				return 'image/jpeg';
		}
	}

	public function getStyle($name) {
		return isset($this->styles[$name]) ? $this->styles[$name] : null;
	}
	
	/**
	 * @deprecated
	 * @param type $foregroundimage 
	 */
	public function setForegroundimage($foregroundimage) {
		$this->setImageStyle('foregroundimage', $foregroundimage);
	}

	public function setTitleColor($r, $g=null, $b=null) {
		$this->setStyle('titlecolor', $this->color($r, $g, $b) );
	}

	public function setSubtitleColor($r, $g=null, $b=null) {
		$this->setStyle('subtitlecolor', $this->color($r, $g, $b) );
	}
	
	public function setGlowColor($r, $g=null, $b=null) {
		$this->setStyle('glowcolor', $this->color($r, $g, $b) );
	}
	
	/**
	 * @deprecated;
	 * @param type $width 
	 */
	public function setWidth($width) {
		$this->setStyle('width', $width);
	}

	/**
	 * @deprecated
	 * @param type $titlealternate 
	 */
	public function setTitleAlternate($titlealternate) {
		$this->setStyle('titlealternate', $titlealternate);
	}
	
	/**
	 * @deprecated
	 * @param type $titlex 
	 */
	
	public function setTitleX($titlex) {
		$this->setStyle('titlex', $titlex);
	}
	
	/**
	 * @deprecated
	 * @param type $titley 
	 */
	
	public function setTitleY($titley) {
		$this->setStyle('titley', $titley);
	}
	
	/**
	 * @deprecated
	 * @param type $foregroundimagealternate 
	 */
	public function setForegroundImageAlternate($foregroundimagealternate) {
		$this->setImageStyle('foregroundimagealternate', $foregroundimagealternate);
	}

	private function color($r, $g=null, $b=null) {
		if ($g) {
			return "$r,$g,$b";
		}
		else {
			if (strpos($r, '#') === 0) {
				$hex = str_replace("#", "", $r);
				if (strlen($hex) == 3) {
					$r = hexdec(substr($hex, 0, 1));
					$g = hexdec(substr($hex, 1, 1));
					$b = hexdec(substr($hex, 2, 1));
					return "$r,$g,$b";
				}
				else if (strlen($hex) == 6) {
					$r = hexdec(substr($hex, 0, 2));
					$g = hexdec(substr($hex, 2, 2));
					$b = hexdec(substr($hex, 4, 2));
					return "$r,$g,$b";
				}
			}
			else {
				return $r;
			}
		}
	}
	
	private function shadowColor($c) {
		$vals = explode(',', $c);
		foreach ($vals as $key => $v) {
			if($v > 127) {
				$v -= ($v * 0.1);
			}
			else {
				$v += ($v * 0.1);
			}
			$vals[$key] = floor($v);
		}
		return implode(',', $vals);
	}

	public function setColor($r, $g=null, $b=null) {
		$this->setStyle('color', $this->color($r, $g, $b) );
	}
	
	public function setBorderColor($r, $g=null, $b=null) {
		$this->setStyle('bordercolor', $this->color($r, $g, $b) );
	}

	public function setUnderline($underline) {
		$this->setStyle('underline', $underline ? 'true' : 'false');
	}

	public function setSize($size) {
		$this->setStyle('size', $size);
	}

	public function setFontFamily($fontfamily) {
		$this->setStyle('fontfamily', $fontfamily);
	}

	/**
	 * @deprecated
	 * @param type $typeface 
	 */
	public function setTypeFace($typeface) {
		$this->setStyle('typeface', $typeface);
	}

	public function setHeight($height) {
		$this->setStyle('height', $height);
	}

	/**
	 * @deprecated
	 * @param type $format 
	 */
	public function setFormat($format) {
		$this->setStyle('format', $format);
	}
	
	/**
	 * @deprecated
	 * @param type $usecontent 
	 */

	public function setUseContent($usecontent) {
		$this->setStyle('usecontent', $usecontent);
	}

	
	public function setNowrap($nowrap) {
		$this->setStyle('nowrap', $nowrap);
	}
	
	/**
	 * @deprecated
	 * @param type $weight 
	 */
	public function setWeight($weight) {
		$this->setStyle('weight', $weight);
	}

	public function setAlign($align) {
		$this->setStyle('align', $align);
	}
	
	/**
	 * @deprecated
	 * @param type $defaultimage 
	 */
	public function setDefaultImage($defaultimage) {
		$this->setImageStyle('defaultimage', $defaultimage);
	}
	
	/**
	 * @deprecated
	 * @param type $checkedimage 
	 */
	public function setCheckedImage($checkedimage) {
		$this->setImageStyle('checkedimage', $checkedimage);
	}

	public function setColumns($columns) {
		$this->setStyle('cols', $columns);
	}
	
	public function setBold($bold) {
		$this->setStyle('bold', $bold ? 'true' : 'false');
	}
	
	public function setItalic($italic) {
		$this->setStyle('italic', $italic ? 'true' : 'false');
	}
	
	const TYPE_FILL = 'Fill';
	const TYPE_FIT = 'Fit';
	const TYPE_STRETCH = 'Stretch';
	const TYPE_TILE = 'Tile';
	const TYPE_CENTER = 'Center';
	
	const ATTACHMENT_HOR_LEFT = 1;
	const ATTACHMENT_HOR_CENTER = 2;
	const ATTACHMENT_HOR_RIGHT = 3;
	const ATTACHMENT_VER_TOP = 4;
	const ATTACHMENT_VER_CENTER = 8;
	const ATTACHMENT_VER_BOTTOM = 12;

	public function setListBackground($listbackground, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setImageStyle('listbackground', $listbackground);
		$this->setStyle('listbackgroundtype', $type);
		$this->setStyle('listbackgroundattachment', $attachment);
		$this->setStyle('listbackgroundsize', $size);
		$this->setStyle('listbackgroundcolor', $this->color($color, $g, $b));
	}
	
	public function setGalleryBackground($gallerybackground, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setImageStyle('gallerybackground', $gallerybackground);
		$this->setStyle('gallerybackgroundtype', $type);
		$this->setStyle('gallerybackgroundattachment', $attachment);
		$this->setStyle('gallerybackgroundsize', $size);
		$this->setStyle('gallerybackgroundcolor', $this->color($color, $g, $b));
	}

	public function setIconBackground($iconbackground, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setImageStyle('iconbackground', $iconbackground);
		$this->setStyle('iconbackgroundtype', $type);
		$this->setStyle('iconbackgroundattachment', $attachment);
		$this->setStyle('iconbackgroundsize', $size);
		$this->setStyle('iconbackgroundcolor', $this->color($color, $g, $b));
	}

	public function setAppsBackground($iconbackground, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setImageStyle('appsbackground', $iconbackground);
		$this->setStyle('appsbackgroundtype', $type);
		$this->setStyle('appsbackgroundattachment', $attachment);
		$this->setStyle('appsbackgroundsize', $size);
		$this->setStyle('appsbackgroundcolor', $this->color($color, $g, $b));
	}
	
	public function setSplash($splash, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setImageStyle('splash', $splash);
		$this->setStyle('splashtype', $type);
		$this->setStyle('splashattachment', $attachment);
		$this->setStyle('splashbackgroundsize', $size);
		$this->setStyle('splashcolor', $this->color($color, $g, $b));
	}
	
	public function setItemBackground($item_background, $type = null, $attachment = 0, $size = null, $color = null, $g = null, $b = null) {
		$this->setStyle('backgroundimage', $item_background);
		$this->setImageStyle('item_background', $item_background);
		$this->setStyle('item_backgroundtype', $type);
		$this->setStyle('item_backgroundattachment', $attachment);
		$this->setStyle('item_backgroundsize', $size);
		$this->setStyle('item_backgroundcolor', $this->color($color, $g, $b));
	}

	public function setHrAfter($has) {
		$this->setStyle('hr_after', $has ? 'true' : 'false');
	}
	
	public function getHrAfter() {
		return $this->getStyle('hr_after') == 'true';
	}
	
	public function setHrColor($r, $g=null, $b=null) {
		$this->setStyle('hrcolor', $this->color($r, $g, $b) );
	}

	public function setHrHeight($hrheight) {
		$this->setStyle('hrheight', $hrheight);
	}
	/**
	 * @deprecated
	 * @param type $header_image 
	 */
	public function setHeaderImage($header_image) {
		$this->setImageStyle('header_image', $header_image);
	}

	public function setHeaderColor($r, $g=null, $b=null) {
		$this->setStyle('headercolor', $this->color($r, $g, $b) );
	}

	public function setHeaderTextColor($r, $g=null, $b=null) {
		$this->setStyle('headertextcolor', $this->color($r, $g, $b) );
	}
	
	public function setHeaderDisplay($has) {
		$this->setStyle('header_display', ($has == 'false' || $has === false) ? 'false' : 'true');
	}

	public function setPaddingTop($paddingtop) {
		$this->setStyle('paddingtop', $paddingtop);
	}

	public function setPaddingBottom($paddingbottom) {
		$this->setStyle('paddingbottom', $paddingbottom);
	}

	public function setPaddingLeft($paddingleft) {
		$this->setStyle('paddingleft', $paddingleft);
	}

	public function setPaddingRight($paddingright) {
		$this->setStyle('paddingright', $paddingright);
	}

	public function setTitleSize($titlesize) {
		$this->setStyle('titlesize', $titlesize);
	}

	public function setSubtitleSize($subtitlesize) {
		$this->setStyle('subtitlesize', $subtitlesize);
	}
	
	public function setTitleFont($titlefont) {
		$this->setStyle('titlefont', $titlefont);
	}

	public function setSubtitleFont($subtitlefont) {
		$this->setStyle('subtitlefont', $subtitlefont);
	}
	
	public function setAutoCompleteColor($r, $g=null, $b=null) {
		$this->setStyle('autocompletecolor', $this->color($r, $g, $b) );
	}
	
	public function setAutoCompleteBackgroundColor($r, $g=null, $b=null) {
		$this->setStyle('autocompletebackgroundcolor', $this->color($r, $g, $b) );
	}
	
	public function setAutoCompleteHighlightColor($r, $g=null, $b=null) {
		$this->setStyle('autocompletehighlightcolor', $this->color($r, $g, $b) );
	}
	
	/* HTML Export */
	
	/**
	 * Get the css for this element
	 * @param AppBuilderAPICSSDocument $css 
	 */
	public function getCSS($css, &$data = array()) {
		$idselector = $css->getIdSelector($this->getIdType() . (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $this->getId());
		$isScreen = $this instanceof AppBuilderAPIScreen;
		$isItem = $this instanceof AppBuilderAPIItem;
		
		$css->addRule($idselector, 'text-align', $this->getStyle('align'));
		$css->addRule($idselector, 'font-family', $css->getFontValue($this->getStyle('fontfamily')));
		if($this->getStyle('color')) {
			$css->addRule($idselector, 'color', $css->getColorValue($this->getStyle('color')));
			$css->addRule($idselector." button", 'color', $css->getColorValue($this->getStyle('color')));
			$css->addRule($idselector." .item-icon-inner .title", 'color', $css->getColorValue($this->getStyle('color')));
		}
		
		$css->addRule(array($idselector, $css->getClassSelector('glow-back')), 'fill', $css->getColorValue($this->getStyle('glowcolor')));
		$css->addRule(array($css->getClassSelector('android'), $idselector, $css->getClassSelector('glow-back'), $css->getClassSelector('back-left')), 'background-color', $css->getColorValue($this->getStyle('glowcolor')));
		$css->addRule(array($idselector, $css->getClassSelector('glow-back'), $css->getClassSelector('back-right')), 'background-color', $css->getColorValue($this->getStyle('glowcolor')));
		$css->addRule(array($idselector, $css->getClassSelector('glow-back'), $css->getClassSelector('back-center')), 'background-color', $css->getColorValue($this->getStyle('glowcolor')));
		$c = $this->getStyle('glowcolor');
		if($c) {
			$css->addRule(array($idselector, $css->getClassSelector('glow')), 'background-color', "rgba($c, 0.5)");
		}
		
		$css->addRule($idselector, 'font-size', $css->getSizeValue($this->getStyle('size')));
		$this->getCSSImage($css, 'splash', $idselector.$css->getClassSelector('splash'));
		
		$bold = $this->getStyle('bold');
		if($bold == 'true') {
			$css->addRule($idselector, 'font-weight', 'bold');
		}
		else if($bold == 'false') {
			$css->addRule($idselector, 'font-weight', 'normal');
		}
		
		$italic = $this->getStyle('italic');
		if($italic == 'true') {
			$css->addRule($idselector, 'font-style', 'italic');
		}
		else if($italic == 'false') {
			$css->addRule($idselector, 'font-style', 'normal');
		}
		
		$underline = $this->getStyle('underline');
		if($underline == 'true') {
			$css->addRule($idselector, 'text-decoration', 'underline');
		}
		else if($underline == 'false') {
			$css->addRule($idselector, 'text-decoration', 'none');
		}
		if($this->getStyle('header_display') == 'false') {
			if($isScreen) {
				$css->addRule(array($idselector.$css->getClassSelector('screen'), $css->getClassSelector('header')), 'display', 'none');
				$css->addRule(array($idselector.$css->getClassSelector('screen'), $css->getClassSelector('items')), 'top', '0');
			}
			else {
				$css->addRule(array($idselector, $css->getClassSelector('screen'), $css->getClassSelector('header')), 'display', 'none');
				$css->addRule(array($idselector, $css->getClassSelector('screen'), $css->getClassSelector('items')), 'top', '0');
			}
		}
		
		$css->addRule($idselector, 'border-color', $css->getColorValue($this->getStyle('bordercolor')));
		
		$css->addRule(array($idselector, $css->getClassSelector('autocomplete')), 'color', $css->getColorValue($this->getStyle('autocompletecolor')));
		$css->addRule(array($idselector, $css->getClassSelector('autocomplete')), 'border-color', $css->getColorValue($this->getStyle('autocompletecolor')));
		$css->addRule(array($idselector, $css->getClassSelector('autocomplete')), 'background-color', $css->getColorValue($this->getStyle('autocompletebackgroundcolor')));
		$css->addRule(array($idselector, $css->getClassSelector('autocomplete'), $css->getClassSelector('completion').$css->getPseudoClassSelector('hover')), 'color', $css->getColorValue($this->getStyle('autocompletehighlightcolor')));
		
		$css->addRule(array($idselector, 'textarea'), 'border-color', $css->getColorValue($this->getStyle('bordercolor')));
		$css->addRule(array($idselector, 'input'), 'border-color', $css->getColorValue($this->getStyle('bordercolor')));
		$css->addRule(array($idselector, 'select'), 'border-color', $css->getColorValue($this->getStyle('bordercolor')));
		
		$css->addRule(array($idselector, $css->getClassSelector('title')), 'color', $css->getColorValue($this->getStyle('titlecolor')));
		$css->addRule(array($idselector, $css->getClassSelector('title')), 'font-size', $css->getSizeValue($this->getStyle('titlesize')));
		$css->addRule(array($idselector, $css->getClassSelector('title')), 'font-family', $css->getFontValue($this->getStyle('titlefont')));
		
		$css->addRule(array($idselector, $css->getClassSelector('text')), 'color', $css->getColorValue($this->getStyle('subtitlecolor')));
		$css->addRule(array($idselector, $css->getClassSelector('text')), 'font-size', $css->getSizeValue($this->getStyle('subtitlesize')));
		$css->addRule(array($idselector, $css->getClassSelector('text')), 'font-family', $css->getFontValue($this->getStyle('subtitlefont')));
		
		if($isScreen) {
			$this->getCSSImage($css, 'gallerybackground', array($idselector.$css->getClassSelector(array('screen', 'gallery')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'gallerybackground', array($idselector.$css->getClassSelector(array('screen', 'photo')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'listbackground', array($idselector.$css->getClassSelector(array('screen', 'list')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'iconbackground', array($idselector.$css->getClassSelector(array('screen', 'icon')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'appsbackground', array($idselector.$css->getClassSelector(array('screen', 'appsscreen')), $css->getClassSelector('items')));
		}
		else {
			$this->getCSSImage($css, 'gallerybackground', array($idselector, $css->getClassSelector(array('screen', 'gallery')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'gallerybackground', array($idselector, $css->getClassSelector(array('screen', 'photo')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'listbackground', array($idselector, $css->getClassSelector(array('screen', 'list')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'iconbackground', array($idselector, $css->getClassSelector(array('screen', 'icon')), $css->getClassSelector('items')));
			$this->getCSSImage($css, 'appsbackground', array($idselector, $css->getClassSelector(array('screen', 'appsscreen')), $css->getClassSelector('items')));
		}
		
		//if this is an item
		if($isScreen) {
			$this->getCSSImage($css, 'item_background', array($idselector.$css->getClassSelector(array('screen', 'list')), $css->getClassSelector('item')));
		}
		else if($isItem) {
			$this->getCSSImage($css, 'item_background', array($css->getClassSelector(array('screen', 'list')), $idselector.$css->getClassSelector('item')));
		}
		else {
			$this->getCSSImage($css, 'item_background', array($idselector, $css->getClassSelector(array('screen', 'list')), $css->getClassSelector('item')));
		}
		
		if($isScreen) {
			$css->addRule(array($idselector.$css->getClassSelector('screen'), $css->getClassSelector('header')), 'color', $css->getColorValue($this->getStyle('headertextcolor')));
			$css->addRule(array($css->getClassSelector('android'), $idselector.$css->getClassSelector('screen'), $css->getClassSelector('header'), $css->getClassSelector('back')), 'stroke', $css->getColorValue($this->getStyle('headertextcolor')));
		}
		else {
			$css->addRule(array($idselector, $css->getClassSelector('screen'), $css->getClassSelector('header')), 'color', $css->getColorValue($this->getStyle('headertextcolor')));
			$css->addRule(array($css->getClassSelector('android'), $idselector, $css->getClassSelector('screen'), $css->getClassSelector('header'), $css->getClassSelector('back')), 'stroke', $css->getColorValue($this->getStyle('headertextcolor')));
		}
		
		if($this->getStyle('headertextcolor')) {
			if($isScreen) {
				$css->addRule(array($idselector.$css->getClassSelector('screen'), $css->getClassSelector('header'), $css->getClassSelector('title')), 'text-shadow', "0px 1px 0px " . $css->getColorValue($this->shadowColor($this->getStyle('headertextcolor'))));
			}
			else {
				$css->addRule(array($idselector, $css->getClassSelector('screen'), $css->getClassSelector('header'), $css->getClassSelector('title')), 'text-shadow', "0px 1px 0px " . $css->getColorValue($this->shadowColor($this->getStyle('headertextcolor'))));
			}
		}
		if($isScreen) {
			$css->addRule(array($idselector.$css->getClassSelector('screen'), $css->getClassSelector('header')), 'background-color', $css->getColorValue($this->getStyle('headercolor')));
		}
		else {
			$css->addRule(array($idselector, $css->getClassSelector('screen'), $css->getClassSelector('header')), 'background-color', $css->getColorValue($this->getStyle('headercolor')));
		}
		
		$hrafter = $this->getStyle('hr_after');
		if($hrafter == 'true') {
			$width = $this->getStyle('hrheight');
			if(!$width) {
				$width = 1;
			}
			if($isItem) {
				$css->addRule($idselector.$css->getClassSelector('item'), 'border-bottom-width', $css->getSizeValue($width));
			}
			else {
				$css->addRule(array($idselector, $css->getClassSelector('item')), 'border-bottom-width', $css->getSizeValue($width));
			}
		}
		else if($hrafter == 'false') {
			if($isItem) {
				$css->addRule($idselector.$css->getClassSelector('item'), 'border-bottom-width', 0);
			}
			else {
				$css->addRule(array($idselector, $css->getClassSelector('item')), 'border-bottom-width', 0);
			}
		}
		if($isItem) {
			$css->addRule($idselector.$css->getClassSelector('item'), 'border-bottom-color', $css->getColorValue($this->getStyle('hrcolor')));
		}
		else {
			$css->addRule(array($idselector, $css->getClassSelector('item')), 'border-bottom-color', $css->getColorValue($this->getStyle('hrcolor')));
		}
		$css->addRule($idselector, 'padding-top', $css->getSizeValue($this->getStyle('paddingtop')));
		$css->addRule($idselector, 'padding-bottom', $css->getSizeValue($this->getStyle('paddingbottom')));
		$css->addRule($idselector, 'padding-left', $css->getSizeValue($this->getStyle('paddingleft')));
		$css->addRule($idselector, 'padding-right', $css->getSizeValue($this->getStyle('paddingright')));
	}
	
	private function getCSSImage($css, $name, $selector) {
		$src = $this->getStyleImageURL($name);
		if($src) {
			$type = $this->getStyle("{$name}type");
			$size = 'contain';
			$repeat = 'no-repeat';
			$attachment = $this->getStyle("{$name}attachment");
			$positionHor = 'center';
			$positionVer = 'center';

			switch($type) {
				case self::TYPE_STRETCH:
					$size = '100% 100%';
					break;
				case self::TYPE_TILE:
					$repeat = 'repeat';
				case self::TYPE_CENTER:
					$sizeS = $this->getStyle("{$name}size");
					if(is_array($sizeS)) {
						$size = "{$sizeS['width']}px {$sizeS['height']}px";
					}
					else {
						$size = 'auto';
					}
					break;
				case self::TYPE_FILL:
					$size = 'cover';
					break;
			}

			if($attachment) {
				$i = $attachment & 3;
				if($i == 1) {
					$positionHor = 'left';
				}
				else if($i == 3) {
					$positionHor = 'right';
				}
				$i = $attachment & 12;
				if($i == 4) {
					$positionVer = 'top';
				}
				else if($i == 12) {
					$positionVer = 'bottom';
				}
			}

			$css->addRule($selector, 'background-image', $css->getURLValue($src));
			$css->addRule($selector, 'background-repeat', $repeat);
			$css->addRule($selector, 'background-position', "$positionHor $positionVer");
			$css->addRule($selector, 'background-size', $size);
		}
		$css->addRule($selector, 'background-color', $css->getColorValue($this->getStyle("{$name}color")));
	}
	
	/* JSON Export */
	
	public function getObjects(&$obj) {
		$this->getStyleObj();
		foreach($this->styleImages as &$image) {
			$obj['images'][$image['id']] = &$image;
		}
	}
	
	protected function &getStyleObj() {
		if(!$this->styleObj) {
			if(count($this->styles) > 0) {
				$this->styleObj = array('id'=> static::id());
				foreach($this->styles as $key => $value) {
					if(isset($this->styleImages[$key])) {
						if($this->styleImages[$key] === false) {
							$this->getImageStyleObject($key);
						}
						if($this->styleImages[$key]) {
							$this->styleObj[$key] = $this->styleImages[$key]['id'];
						}
					}
					else {
						$this->styleObj[$key] = $value;
					} 
				}
			}
		}
		return $this->styleObj;
	}

	protected function &getImageStyleObject($name) {
		if ($this->styleImages[$name] === false) {
			if (isset($this->styles[$name])) {
				$image = false;
				foreach($this->styleImages as &$i) {
					if($i['src'] == $this->styles[$name]) {
						$image = &$i;
					}
				}
				if(!$image) {
					$image = array(
						'id' => static::id(),
						'name' => basename($this->styles[$name]),
						'src' => $this->styles[$name],
						'type' => static::getExtension($this->styles[$name]),
						'mime' => static::getMime($this->styles[$name])
					);
				}
				$this->styleImages[$name] = &$image;
			}
			else {
				$this->styleImages[$name] = null;
			}
		}
		return $this->styleImages[$name];
	}
	
}
