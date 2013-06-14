<?php
/**
 * @package AppBuilderAPI
 * @subpackage  Core
 * @author Fred Cox <fred@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */
class AppBuilderAPI {
	
	/**
	 * Return a string to response with, either xml, json or html
	 * You should always use this function to reply to the app server
	 * 
	 * @param AppBuilderAPIElement $screen the screen or app to render
	 * @param bool $header whether to set the appropriate response header
	 * @return array 
	 */
	public static function getScreenResponse($screen, $header = false) {
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] :
			(isset($_GET['type']) ? $_GET['type'] :
			(isset($_POST['type']) ? $_POST['type'] : 'jsonp'));
		if($type == 'xml') {
			return self::getScreenAsXML($screen, $header);
		}
		else {
			return self::getScreenAsJSONP($screen, $header);
		}
	}
	
	/* HTML Export */

	/**
	 * Get the html object for a screen
	 * @param AppBuilderAPIElement $screen
	 * @return array
	 * @internal used by appbuilder
	 */
	public static function getElementAsHTMLObj($screen, $settings = null, $objs = null) {
		if(!$settings) {
			$settings = array();
		}
		if(!isset($settings['emailPreview'])) {
			$settings['emailPreview'] = isset($_REQUEST['emailPreview']) ? $_REQUEST['emailPreview'] === 'true' : false;
		}
		if(!isset($settings['telPreview'])) {
			$settings['telPreview'] = isset($_REQUEST['telPreview']) ? $_REQUEST['telPreview'] === 'true' : false;
		}
		$settings['fetchscreen'] = $screen;
		$data['settings'] = $settings;
		
		$xml = self::getNewXMLDocument();
		$s = $screen->getHTMLNode($xml, $data);
		if(isset($objs)) {
			foreach($objs as $obj) {
				$obj->getHTMLNode($xml, $data);
			}
		}
		
		$data['settings']['main'] = (isset($data['settings']['prefix']) ? $data['settings']['prefix'] : '') . $screen->getId();
		$data['settings']['maintype'] = $screen instanceof AppBuilderAPIApp ? 'app' : 'screen';
		unset($data['settings']['fetchscreen']);
		unset($data['settings']['emailPreview']);
		unset($data['settings']['telPreview']);

		return $data;
	}

	/**
	 * Get the html object for a screen
	 * @param AppBuilderAPIElement $screen
	 * @internal used by appbuilder
	 * @return string
	 */
	public static function getElementAsHTMLJSONP($screen, $header = false, $callback = null) {
		$data = json_encode(self::getElementAsHTMLObj($screen));
		return self::makeJSONP($header, $callback, $data);
	}

	/* JSON Export */

	/**
	 * Get the json object for a screen
	 * @param AppBuilderAPIElement $screen
	 * @internal used by appbuilder
	 * @return array
	 */
	public static function getScreenAsObj($screen, $refreshAfter = 0) {
		$screenObj = &$screen->getObj();

		$obj = array(
			'main' => $screenObj['id'],
			'maintype' => $screen instanceof AppBuilderAPIApp ? 'app' : 'screen',
			'data' => array(
				'screens' => array(),
				'images' => array(),
				'items' => array(),
				'styles' => array(),
				'files' => array()
			),
			'refreshAfter' => $refreshAfter
		);

		$screen->getObjects($obj['data']);

		return $obj;
	}

	/**
	 * Get the json object for a screen
	 * @param AppBuilderAPIElement $screen
	 * @return string
	 * @internal used by appbuilder
	 */
	public static function getScreenAsJSONP($screen, $header = false, $callback = null, $refreshAfter = 0) {
		$data = json_encode(self::getScreenAsObj($screen, $refreshAfter));
		return self::makeJSONP($header, $callback, $data);
	}

	/* XML Export */

	/**
	 * Get a screen as xml
	 * @deprecated since version 2.0
	 * @param AppBuilderAPIScreen $screen
	 * @return string 
	 */
	public static function getScreenAsXML($screen, $header = false) {
		if ($header) {
			header('Content-type: text/xml');
		}
		return self::getScreenAsDOMNode($screen)->saveXML();
	}

	/**
	 * Get a screen as xml
	 * @deprecated since version 2.0
	 * @param AppBuilderAPIScreen $screen
	 * @return DOMDocument
	 */
	public static function getScreenAsDOMNode($screen) {
		$xml = self::getNewXMLDocument();
		$node = $screen->getNode($xml);
		$xml->appendChild($node);
		return $xml;
	}
	
	/**
	 * Helper function to receive files from app. Deals with xml sending the data in POST
	 * 
	 * @param type $filename
	 * @param type $variableName
	 * @return bool 
	 */
	public static function saveUpload($filename, $variableName) {
		if (isset($_FILES[$variableName])) {
			move_uploaded_file($_FILES[$variableName]['tmp_name'], $filename);
			return true;
		}
		else if (isset($_POST[$variableName])) {
			file_put_contents($filename, base64_decode($_POST[$variableName]));
			return true;
		}
		return false;
	}
	
	public static function autoCompleteReturn($values, $header = true, $callback = null) {
		return self::makeJSONP($header, $callback, json_encode($values));
	}

	/**
	 * Get a blank xml document
	 * @return DOMDocument 
	 */
	private static function getNewXMLDocument() {
		$xml = new AppBuilderAPIDOMDocument('1.0', 'UTF-8');
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = false;
		return $xml;
	}

	private static function makeJSONP($header, $callback, $data) {
		if ($callback == null) {
			$callback = isset($_GET['callback']) ? $_GET['callback'] : (isset($_POST['callback']) ? $_POST['callback'] : false);
		}
		if ($header) {
			header('Content-type: application/javascript');
		}
		if ($callback) {
			return "$callback(" . $data . ");";
		}
		else {
			return "console.log(" . $data . ");";
		}
	}

}
