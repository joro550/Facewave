<?php

class TableEntity {
	private $mIdField;
	protected $mDbData;
	private $mCreatorFactory;
	protected $mQueryCreator;

	protected $mDb;

	public function getCreatorFactory() {
		return $this->mCreatorFactory;
	}

	public function setCreatorFactory($fact) {
		$this->mCreatorFactory = $fact;
	}

	public function getQueryCreator() {
		if($this->mQueryCreator == NULL) {
			$this->mQueryCreator = new QueryCreator();
		}
		return $this->mQueryCreator;
	}

	public function getCount($what, $condition = null, $group = null) {
		if(!$condition != null) {
			if(is_array($condition)) {
				$condition['deleted'] = '0';
			} 
		}
		return $this->mDb->count(get_class($this), $what, $condition, $group);
	}

	public function __construct($idFieldName = 'id') {
		$this->mIdField = $idFieldName;
		$this->mDb = new Database();
		$this->createTableEntity();
	}

	public function getIdFieldValue() {
		return $this->mDbData[$this->mIdField];
	}

	public function getIdField() {
		return $this->mIdField;
	}

	public function openById($id) {
		$object = $this->mDb->select(get_class($this), array($this->mIdField => $id, `deleted` => 0));
		$object = array_pop($object);
		$this->createTableEntity($object);
	}

	public function commit() {
		$update = $this->mDb->update(get_class($this), $this->mDbData);
		if(!isset($this->mDbData[$this->mIdField]) || $this->mDbData[$this->mIdField] == '') {
			$object = $this->mDb->query("SELECT * FROM " . get_class($this) . " ORDER BY {$this->mIdField} DESC LIMIT 1");
			$object = array_pop($object);
			$this->createTableEntity($object);
		}
		return $update;
	}

	public function __call($method, $param) {
		$method = strtolower($method);
		$action = substr($method, 0, 3);
		$data = substr($method, 3);
		if(array_key_exists($data, $this->mDbData)) {
			switch($action) {
				case 'get':
					return $this->mDbData[$data];
					break;
				case 'set':
					if(is_array($param)){
						$this->mDbData[$data] = $param[0];
					} else {
						$this->mDbData[$data] = $param;
					}
					break;
			}
		}
		return FALSE;
	}

	public function createTableEntity($values = array()) {
		$fields = $this->mDb->describe(get_class($this));
		foreach($fields as $field) {
			if(isset($values[$field['Field']])) {
				$this->mDbData[$field['Field']] = $values[$field['Field']];
			} else {
				$this->mDbData[$field['Field']] = '';
			}
		}
	}

	public function getTableEntity($field = null) {
		if(!is_null($field)) {
			$returnArray = array();
			if(is_array($field)) {
				foreach($field as $f) {
					if(isset($this->mDbData[$f])) {
						$returnArray[$f]  = $this->mDbData[$f];
					}
				}
			} else {
				if(isset($this->mDbData[$field])) {
					$returnArray[$field] = $this->mDbData[$field];
				}
			}
			return $returnArray;
		} else {
			return $this->mDbData;
		}
	}

	public function search($searchString) {
		foreach($this->mDbData as $key => $value) {
			$searchArray[$key] = "~%{$searchString}%";
		}
		$results = $this->getCreatorFactory()->getFilteredObject(get_class($this), $searchArray, NULL, 'OR');	
		return $results;
	}

	protected function runQuery($query) {
		//DO NOT USE THIS FUNCTION LIGHTLY! - only if there is no other way to do something!!!
		return $this->mDb->query($query);
	}

	public function delete() {
		$this->setDeleted(1);
		return $this->commit();
	}
}

