<?php

/**
 * Helper Class for the HTML Export
 * @package AppBuilderAPI
 * @subpackage  Core
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPICSSDocument {

	private $rules = array();
	private $css = "";

	public function addRule($selector, $name, $value) {
		if ($value !== null && $value !== '') {
			if (is_array($selector)) {
				$selector = implode(' ', $selector);
			}
			if (!isset($this->rules[$selector])) {
				$this->rules[$selector] = array();
			}
			$this->rules[$selector][$name] = $value;
		}
	}

	public function addCSS($css, $idselector = null) {
		if(!empty($css)) {
			$idselectorPattern = preg_quote($idselector);
			 $css = preg_replace_callback("/(^|}|,[.:#@ \s]|(@media.+?{))\s*(({$idselectorPattern}[.:#@ \s])?([^}@]*?))({|(?=,))/s", function($matches) use ($idselector) {
				if ($matches[4]) {
					return $matches[0] . "\n";
				}
				else {
					if (strpos($matches[5], '@') !== false) {
						return "{$matches[1]} {$matches[5]} {$matches[6]}\n";
					}
					return "{$matches[1]}\n{$idselector} {$matches[5]} {$matches[6]}\n";
				}
			}, $css);
			$this->css .= "/* Custom CSS */\n$css/* End Custom CSS */\n";
		}
	}

	public function getIdSelector($id) {
		return "#" . $id;
	}

	public function getClassSelector($class) {
		if (is_array($class)) {
			$class = implode('.', $class);
		}
		return "." . $class;
	}

	public function getPseudoClassSelector($class) {
		if (is_array($class)) {
			$class = implode(':', $class);
		}
		return ":" . $class;
	}

	public function getURLValue($src) {
		if ($src) {
			return "url($src)";
		}
		return null;
	}

	public function getColorValue($c) {
		if ($c) {
			return "rgb($c)";
		}
		return null;
	}

	public function getFontValue($font) {
		if ($font) {
			return "'$font'";
		}
		return null;
	}

	public function getSizeValue($s, $unit = 'px') {
		if ($s) {
			return $s . $unit;
		}
		return null;
	}

	public function toString() {
		$str = $this->css;
		foreach ($this->rules as $selector => $rules) {
			$str .= "$selector {\n";
			foreach ($rules as $name => $value) {
				$str .= "\t$name: $value;\n";
			}
			$str .= "}\n";
		}
		return $str;
	}

	public function toSplashString() {
		$str = $this->css;
		foreach ($this->rules as $selector => $rules) {
			if (strpos($selector, "splash") !== false) {
				$str .= "$selector {\n";
				foreach ($rules as $name => $value) {
					$str .= "\t$name: $value;\n";
				}
				$str .= "}\n";
			}
		}
		return $str;
	}

}