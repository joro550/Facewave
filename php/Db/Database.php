<?php
/**
 *	This file will do everything with the database i.e. handle queries
**/

class Database {

	private $mConnection;
	private $mDb;

	private $mUser;
	private $mPass;
	private $mHost;
	private $mQueryCreator;

	private static $mSqli;

	private $mSqlQuery;

	protected static function getDb() {

		if(!isset(Database::$mSqli)) {
			$host = '127.0.0.1';;
			$user = 'root';
			$pass = '';
			$db = 'facewave';

			Database::$mSqli = new mysqli($host, $user, $pass, $db);
			if(Database::$mSqli->connect_error) {
				throw new Exception('Could not connect to the database');
			}
		}

		return Database::$mSqli;
	}

	protected function getQueryCreator() {
		if($this->mQueryCreator == NULL) {
			$this->mQueryCreator = new QueryCreator();
		}
		return $this->mQueryCreator;
	}

	public function select($table, $where = NULL, $order = NULL, $queryCond = 'AND') {
		$sql = $this->getQueryCreator()->select("*")->from($table)->where($where, $queryCond)->order($order)->getQueryString();
		$result = Database::getDb()->query($sql);
		if($result === FALSE) {
			throw new Exception('Query Failed ' . Database::getDb()->error);
		}
		$results = array();
		while($r = $result->fetch_assoc()) {
			$results[] = $r;
		}
		return $results;
	}

	public function describe($table) {
		$table = strtolower($table);
		$result = Database::getDb()->query('DESCRIBE ' . $table);
		$results = array();
		while($r = $result->fetch_assoc()) {
			$results[] = $r;
		}
		return $results;
	}

	public function update($table, $what) {
		$table = strtolower($table);
		$sql = "INSERT INTO `{$table}` ";
		if(is_array($what)) {
			$sql .= ' (' . implode(', ', array_keys($what)) . ')';
			$vals = array();
			$sql .= ' VALUES ';
			foreach($what as $key => $val) {
				$val = Database::cleanString($val);
				$vals[] = "'" . $val . "'";
			}
			$sql .= '(' . implode(', ', $vals) . ')';

			$sql .= ' ON DUPLICATE KEY UPDATE ';
			$keys = array_keys($what);
			$vals = array();
			foreach($what as $key => $val) {
				$vals[] = "`{$key}` = ('{$val}')";
			}
			$sql .= implode(', ', $vals);
		}
		$result = Database::getDb()->query($sql);
		return TRUE;
	}

	public function delete($what, $where) {

	}

	public function count($where, $what = '*', $condition = null, $group = null) {
		$where = strtolower($where);
		//Let's clean the sting
		$where = Database::cleanString($where);
		$what = Database::cleanString($what);
		//Selecting the stuff :D 
		$sql = "SELECT COUNT(`{$what}`) as `count` FROM `$where` ";
		if($condition != null) {
			$sql .= "WHERE ";
			if(is_array($condition)) {
				$whereClause = array();
				foreach($condition as $key => $val) {
					$whereClause[] = "`{$key}` = '{$val}' ";
				}
				$sql .= implode(" AND ", $whereClause);
			} else return false; 
		}
		if($group != NULL) {
			$sql .= "GROUP BY `{$group}`";
		}

		// echo $sql . "\n";
		$dbResult = Database::getDb()->query($sql);
		
		$results = array();
		while($r = $dbResult->fetch_assoc()) {
			$results[] = $r;
		}

		if(count($results) == 1) {
			$results = array_pop($results);
			return $results['count'];
		} else {
			$results;
		}
	}

	public function query($sql) {
		$result = Database::getDb()->query($sql);
		if($result === FALSE) {
			throw new Exception('Query Failed' . Database::getDb()->error);
		}

		$results = array();
		while($r = $result->fetch_assoc()) {
			$results[] = $r;
		}
		return $results;
	}

	public static function cleanString($string) {
		if(is_array($string)) {
			for($i = 0; $i < count($string); $i ++) {
				$string[$i] = Database::getDb()->real_escape_string($string[$i]);
			}
		} else {
			$string = Database::getDb()->real_escape_string($string);
		}
		return $string;
	}

	private function getCondition($condition) {
		switch ($condition) {
			case '~':
				return 'LIKE';
				break;
			
			default:
				return '=';
				break;
		}

	}
}

