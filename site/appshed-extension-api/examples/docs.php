<?php

/**
 * @package AppBuilderAPI
 * @subpackage examples
 * @author Vitaliy Pitvalo <vitaliy@ekreative.com>
 * @copyright Copyright (c) 2011, ekreative
 * @version 1.0
 */

class AppBuilderModelDocs {

	private $docKey = false;
	private $spreadsheetAdapter = false;
	private $filterOptions;
	private $errors = array();
	private $aroundme = false;

	public function __construct($config = array()) {
		parent::__construct($config);
//		$this->filterOptions = array('', '<', '>', '=', '!=', '<=','>=','contains','=');
		$this->filterOptions = array('', '<', '>', '=', '!=', '<=', '>=', 'aroundme');
		$this->includeZend();
	}

	public function getAppScreen() {
		$this->getParams();
		$this->getDocKey();
		$mainscreen = new AppBuilderAPIListScreen(JRequest::getVar('title'));

		if ($this->docKey) {
			$this->includeZend();
			$this->getSpreadsheetAdapter();
			if (JRequest::getVar('task') == 'processPostdatajson') {
				$this->processPostdata();
			}
			$doc = $this->getDocument();
			if (count($this->errors) == 0) {
				$itemsCount = 0;
				foreach ($doc as $entry) {
					$row = array();
					foreach ($entry->getCustom() as $customEntry) {
						$row[$customEntry->getColumnName()] = $customEntry->getText();
					}

					if ($this->aroundme > 0) {
						if ($this->checkDistance($row)) {
							$item = $this->addItem($row);
							if (is_object($item)) {
								$this->addLink($row, $item);
								$mainscreen->addChild($item);
								$itemsCount++;
							}
						}
					}
					else {
						$item = $this->addItem($row);
						if (is_object($item)) {
							$this->addLink($row, $item);
							$mainscreen->addChild($item);
							$itemsCount++;
						}
					}
				}

				if ($itemsCount == 0) {
					$mainscreen->addChild(new AppBuilderAPITextItem('No results...'));
				}
			}
			else {
				$mainscreen->addChild(new AppBuilderAPITextItem('Error, ' . implode(', ', $this->errors)));
			}
		}
		else {
			$mainscreen->addChild(new AppBuilderAPITextItem('Error, No results...'));
		}
		return AppBuilderAPI::getScreenAsObj($mainscreen);
	}

	private function checkDistance(&$row) {
		$mapModel = AppBuilderHelper::getMapModel();
		$center = array('lat' => JRequest::getVar('userlat', null), 'lng' => JRequest::getVar('userlng', null));
		$d = $mapModel->distanceOrt($center, $row);
		$row['distancetopointfromyou'] = $d;
		return $d <= $this->aroundme;
	}

	public function processPostdata() {
		$rowData = array();
		$this->getParams();
		$this->getDocKey();
		$fields = JRequest::getVar('columnscache', serialize(array()));
		$fields = unserialize($fields);
		$mainscreen = new AppBuilderAPIListScreen(JRequest::getVar('title'));
		$mainscreen = new AppBuilderAPIListScreen(JRequest::getVar('title'));



		if ($this->docKey) {
			$this->includeZend();
			$this->getSpreadsheetAdapter();
			if (is_array($fields)) {
				foreach ($fields as $value) {
					$val = JRequest::getVar($value, false);
					if (is_string($val)) {
						$rowData[$value] = $val;
					}
					if ($value == 'date') {
						$rowData[$value] = gmdate('d-m-Y H:i:s');
					}
				}
			}

			if ($this->insertRow($rowData)) {
				$mainscreen->addChild(new AppBuilderAPITextItem('Success'));
			}
			else {
				$mainscreen->addChild(new AppBuilderAPITextItem('Error, while adding'));
			};
		}
		else {
			$mainscreen->addChild(new AppBuilderAPITextItem('Error..'));
		}

		return AppBuilderAPI::getScreenAsObj($mainscreen);
	}

	private function insertRow($rowData) {
		$query = new Zend_Gdata_Spreadsheets_CellQuery();
		$query->setSpreadsheetKey($this->docKey);
		$rowArray = $rowData;
		try {

			$entry = $this->spreadsheetAdapter->insertRow($rowArray, $this->docKey, 1);
			if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
				return true;
			}
		}
		catch (Exception $exc) {
			$this->errors[] = 'No write premissoin';
		}
		return false;
	}

	private function googleLogin() {
		$user = AppBuilderLoginHelper::getParam('gmail-email', null);
		$pass = AppBuilderLoginHelper::getParam('gmail-password', null);
		$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
		$http = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
		return $http;
	}

	private function getSpreadsheetAdapter() {
		if ($this->spreadsheetAdapter === false) {
			$http = $this->googleLogin();
			$this->spreadsheetAdapter = new Zend_Gdata_Spreadsheets($http);
		}
	}

	private function getAroundMeQuery($distance) {
		$this->aroundme = $distance;
		$mapModel = AppBuilderHelper::getMapModel();
		$center = array(
			'lat' => JRequest::getVar('userlat', null),
			'lng' => JRequest::getVar('userlng', null)
		);
		$bounds = $mapModel->getBounds($center, $distance);
		$filters[] = 'lat > ' . $bounds['minLat'];
		$filters[] = 'lat < ' . $bounds['maxLat'];
		$filters[] = 'lng > ' . $bounds['minLng'];
		$filters[] = 'lng < ' . $bounds['maxLng'];

		return implode(' AND ', $filters);
	}

	private function getFilterString() {
		$filters = array();
		$filtersArray = unserialize(JRequest::getVar('flters', null));
		if (is_bool($filtersArray)) {
			$filtersArray = array();
		}


		$titles = unserialize(JRequest::getVar('columnscache', null));
		if (is_bool($titles)) {
			$titles = array();
		}

		foreach ($filtersArray as $option) {
			$type = $this->filterOptions[$option['configindex']];

			if ($type == 'aroundme') {
				$filters[] = $this->getAroundMeQuery($option['value']);
			}
			else {
				if (isset($titles[$option['titleindex']])) {
					$title = $titles[$option['titleindex']];
					$value = $option['value'];

					if (ctype_digit($value)) {
						$filters[] = $title . " " . $type . " " . $value . ' ';
					}
					else {
						if ($type == 'like') {
							$filters[] = $title . " " . $type . ' %' . $value . '% ';
						}
						else {
							$filters[] = $title . " " . $type . ' "' . $value . '" ';
						}
					}
				}
			}
		}

		$str = implode(' AND ', $filters);
		return $str;
	}

	private function getTitles() {
		$this->getDocKey();
		$titles = array();
		if ($this->docKey) {
			$this->includeZend();
			$this->getSpreadsheetAdapter();
			foreach ($this->getDocument() as $entry) {
				if (count($this->errors) == 0) {

					foreach ($entry->getCustom() as $customEntry) {
						$titles[] = $customEntry->getColumnName();
					}
					break;
				}
				else {
					JRequest::setVar('errors', implode(', ', $this->errors));
				}
			}
		}
		if (count($titles) > 0) {
			JRequest::setVar('columnscache', serialize($titles));
		}
		return $titles;
	}

	private function getDocument() {

		$this->getSpreadsheetsList();

		$listFeed = new stdClass();
		$listFeed->entries = array();
		if ($this->spreadsheetAdapter instanceof Zend_Gdata_Spreadsheets) {
			try {
				$query = new Zend_Gdata_Spreadsheets_ListQuery();
				$query->setSpreadsheetKey($this->docKey);
				$filter = $this->getFilterString();
				$query->setSpreadsheetQuery($filter);
				$listFeed = $this->spreadsheetAdapter->getListFeed($query);
			}
			catch (Exception $exc) {
				$this->errors[] = 'No read premissoin or other error';
			}
		}
		return $listFeed;
	}

	private function getPostFilters() {
		$filtersArray = array();
		$titles = JRequest::getVar('titles', array());
		$filters = JRequest::getVar('filtersi', array());
		$values = JRequest::getVar('values', array());
		foreach ($values as $key => $value) {
			$value = trim($value);
			$filter = trim($filters[$key]);
			if (!empty($value) && !empty($filter)) {
				$filtersArray[] = array('titleindex' => $titles[$key], 'configindex' => $filter, 'value' => $value);
			}
		}
		if (count($filtersArray) == 0) {
			$filtersArray = unserialize(JRequest::getVar('flters', null));
			if (is_bool($filtersArray)) {
				$filtersArray = array();
			}
		}
		JRequest::setVar('flters', serialize($filtersArray));
		return $filtersArray;
	}

	public function getFilterConfig() {
		$optios = new stdClass();
		$optios->titles = unserialize(JRequest::getVar('columnscache', serialize(array())));
		$optios->options = $this->filterOptions;
		$optios->state = $this->getPostFilters();
		return $optios;
	}

	public function getPrewievScreen() {
		$this->getTitles();
		$actiontype = JRequest::getVar('button', null);
		if ($actiontype == null) {
			$this->getParams();
		}

		if ($actiontype == 'Save') {
			$this->getPostFilters();
			$this->saveParams();
		}
		if ($actiontype == 'apply') {
			$this->getPostFilters();
			$this->saveParams();
		}
	}

	public function saveParams() {
		$identifier = JRequest::getVar('identifier', null);
		$db = AppBuilderDatabaseHelper::getDB();
		$db->setQuery("SELECT * FROM #__appbuilder_docssimpleext WHERE `key`=\"{$identifier}\" AND deleted=FALSE");
		$scheet = $db->loadObject();
		if ($scheet == null) {
			jimport('joomla.utilities.date');
			$scheet = new stdClass();
			$scheet->created = AppBuilderHelperModels::getMySQLDate();
			$scheet->id = 0;
			$scheet->key = $identifier;
		}
		$scheet->sheeturl = JRequest::getVar('sheeturl', null);
		$scheet->title = JRequest::getVar('title', null);
		$scheet->flters = JRequest::getVar('flters', null);
		$scheet->columnscache = JRequest::getVar('columnscache', null);

		$this->getDocKey($scheet->sheeturl);

		if ($scheet->id > 0) {
			if (!$db->updateObject('#__appbuilder_docssimpleext', $scheet, 'id')) {
				AppBuilderHelper::getLog()->addError("database docs", array('message' => $db->getErrorMsg()));
			}
		}
		else {
			if (!$db->insertObject('#__appbuilder_docssimpleext', $scheet, 'id')) {
				AppBuilderHelper::getLog()->addError("database docs", array('message' => $db->getErrorMsg()));
			}
		}
	}

	public function getParams() {
		$identifier = JRequest::getVar('identifier', null);
		$db = AppBuilderDatabaseHelper::getDB();
		$query = "SELECT * FROM #__appbuilder_docssimpleext WHERE `key`=\"{$identifier}\" AND deleted=0";
		$db->setQuery($query);
		$scheet = $db->loadObject();
		if ($scheet !== null) {
			JRequest::setVar('sheeturl', $scheet->sheeturl);
			JRequest::setVar('title', $scheet->title);
			JRequest::setVar('flters', $scheet->flters);
			JRequest::setVar('columnscache', $scheet->columnscache);
		}
		else {
			JRequest::setVar('firstload', true);
		}
	}

	private function getSpreadsheetsList() {
		try {
			$feed = $this->spreadsheetAdapter->getSpreadsheetFeed();
			$feed->title;
			$feed->totalResults;
			foreach ($feed as $entry) {
				//print_r(get_class_methods($entry));
				//echo $entry->getTitle();
			}
		}
		catch (Exception $e) {
			die('ERROR: ' . $e->getMessage());
		}
	}


	private function getDocKey() {
		
		$url = 'https://docs.google.com/spreadsheet/ccc?key=0Ate1qJYdD8andHJaUjM4VGRfNG5LTU40NDk1ckJzb2c#gid=0';
		
		$data = parse_url($url, PHP_URL_QUERY);
		$arr = array();
		parse_str($data, $arr);
		if (isset($arr['key'])) {
			$this->docKey = $arr['key'];
		}
	}

	private function addItem(&$row) {

		$d = isset($row['distancetopointfromyou']) ? ' ' . AppBuilderHelper::getMapModel()->getTextLength($row['distancetopointfromyou']) : '';

		if (isset($row['type']) && !empty($row['type'])) {
			switch ($row['type']) {
				case 'text':
					if ($this->rq($row, array('text'))) {
						return new AppBuilderAPITextItem($row['text'] . $d);
					}

					break;
				case 'thumb':
					if ($this->rq($row, array('title', 'subtitle', 'image'))) {
						return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d, $row['image']);
					}
					if ($this->rq($row, array('title', 'subtitle'))) {
						return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d);
					}
					break;
				case 'image':
					if ($this->rq($row, array('image'))) {
						return new AppBuilderAPIImageItem($row['image']);
					}
					break;
				case 'html':
					if ($this->rq($row, array('text'))) {
						return new AppBuilderAPIFormattedItem($row['text'] . $d);
					}
					break;
				case 'link':
					if ($this->rq($row, array('title'))) {
						return new AppBuilderAPILinkItem($row['title'] . $d);
					}

					break;
				case 'text':
					if ($this->rq($row, array('text'))) {
						$row['linktype'] = 'screen';
						return new AppBuilderAPITextItem($row['text'] . $d);
					}

					break;

				default:
					break;
			}
		}
		else {
			if ($this->rq($row, array('title', 'subtitle', 'image', 'text'))) {
				$row['linktype'] = 'text';
				return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d, $row['image']);
			}
			if ($this->rq($row, array('title', 'subtitle', 'image'))) {
				return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d, $row['image']);
			}
			if ($this->rq($row, array('title', 'subtitle', 'text'))) {
				$row['linktype'] = 'text';
				return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d);
			}
			if ($this->rq($row, array('title', 'subtitle'))) {
				return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d);
			}
			if ($this->rq($row, array('image'))) {
				return new AppBuilderAPIImageItem($row['image'] . $d);
			}
			if ($this->rq($row, array('title', 'text'))) {
				$row['linktype'] = 'text';
				return new AppBuilderAPIThumbItem($row['title'], $row['subtitle'] . $d);
			}
			if ($this->rq($row, array('text'))) {
				return new AppBuilderAPITextItem($row['text'] . $d);
			}
		}
	}

	private function addLink($row, $item) {
		if (in_array(get_class($item), array('AppBuilderAPITextItem')))
			return;

		switch (isset($row['linktype']) ? $row['linktype'] : $this->getLinktype($row)) {
			case 'url':
				$item->setWebLink(isset($row['href']) ? $row['href'] : '');
				break;
			case 'youtube':
				$item->setYoutubeLink(isset($row['href']) ? $row['href'] : '');
				break;
			case 'vimeo':
				$item->setVimeoLink(isset($row['href']) ? $row['href'] : '');
				break;
			case 'video':
				$item->setVideoLink(isset($row['href']) ? $row['href'] : '');
				break;
			case 'text':
				$item->setScreenLink($articleScreen = new AppBuilderAPIListScreen($row['title']));
				$articleScreen->addChild(new AppBuilderAPIFormattedItem($row['text']));
				break;
			default:
				break;
		}
	}

	private function getLinktype($row) {

		if (isset($row['href']) && !empty($row['href'])) {
			if (is_int(strpos($row['href'], 'youtube.com'))) {
				return 'youtube';
			}
			if (is_int(strpos($row['href'], 'vimeo.com'))) {
				return 'vimeo';
			}
		}
		return null;
	}

	private function rq($row, $fields) {
		foreach ($fields as $field) {
			if (!$this->cf($row, $field)) {
				return false;
			};
		}
		return true;
	}

	private function cf($row, $name) {
		return (isset($row[$name]) && !empty($row[$name]) );
	}

}

$includePath = array();
$includePath[] = get_include_path();
$includePath[] = './../../../../../libraries/';
$includePath[] = './../../../../../libraries/Zend/';
$includePath[] = './../../../../../libraries/Zend/Gdata/';
$includePath = implode(PATH_SEPARATOR, $includePath);
set_include_path($includePath);
require_once './../../../../../libraries/Zend/Loader.php';
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
