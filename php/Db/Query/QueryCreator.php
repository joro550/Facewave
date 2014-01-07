<?php


class QueryCreator {
	private $mSqlObject;
	
	public function __construct() {
		
	}

	public function select($what) {
		if($this->mSqlObject != null) {
			unset($this->mSqlObject); 	
		}
		$this->mSqlObject = new SqlObject('select', $what);
		return $this->mSqlObject;
	}

	public function getQuery()  {
		return $this->mSqlObject->getQueryString();
	}

	public function count($what) {
		if(is_array($what)) {
			$countArray = array();
			foreach($what as $key => $val) {
				$countArray[] = "COUNT(`{$key}`) as `{$val}`";
			}
			$queryString = implode(", ", $countArray);
		} else {
			$queryString = "COUNT(`{$what}`) as `count`";
		}
		return $queryString;
	}

}