<?php

class Factory {
	private $mDb;

	public function __construct() {
		$this->mDb = new Database();
	}

	public function getFilteredObject($object, $filters = NULL, $order = NULL, $queryCond = 'AND') {
		$objects = $this->mDb->select($object, $filters, $order, $queryCond);
		$returnVal = array();
		foreach($objects as $obj) {
			$tempObj = $this->createObject($object);
			$tempObj->createTableEntity($obj);
			$tempObj->setCreatorFactory($this);
			//$tempObj
			$returnVal[$tempObj->getIdFieldValue()] = $tempObj;
		}
		return $returnVal;
	}

	public function getNewObject($object) {
		$obj = $this->createObject($object);
		$obj->setCreatorFactory($this);
		return $obj;
	}

	private function createObject($object) {
		if(file_exists(getcwd() . '/Db/Tables/' . $object . '.php')) {
			include_once(getcwd() . '/Db/Tables/' . $object . '.php');
			$object = new $object();
			$object->setCreatorFactory($this);
			return $object;
		}
		return FALSE;
	}
}