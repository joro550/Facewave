<?php


class QueryCreator {
	public function select($table, $what, $where = NULL, $order = NULL, $queryCond = 'AND') {
		$table = strtolower($table);
		$sql = 'SELECT ';
		if(is_array($what)) {
			$whatArray = array();
			foreach($what as $key => $val) {
				if(substr($key, 0, 6) == 'SELECT') {
					$whatArray[] = "({$key}) AS '{$val}'";
				} else {
					$whatArray[] = $key;
				}
			}
			$sql .= implode(", ", $whatArray);
		} else {
			$sql .= $what;
		}
		$sql .= " FROM `{$table}` WHERE ";

		if($where == NULL) {
			$sql .= '1';
		} else {
			$whereClause = array();
			foreach($where as $key => $val) {
				$key = Database::cleanString($key);
				$val = Database::cleanString($val);

				if(is_array($val)) {
					$whereClause[] = "`{$key}` IN (" . implode($val, ", ") . ")";
				} else {
					$condition = substr($val, 0);
					$condition = $this->getCondition($val[0]);
					if($condition != '=') {
						$val = substr($val, 1);
					}
					$whereClause[] = "`{$key}` {$condition} '{$val}'";
				}
			}
			$sql .= implode(" {$queryCond} ", $whereClause);
		}
		// echo $sql . "\n";
		return $sql;
	}

	public function update($table, $what) {

	}

	public function insert($table, $what) {

	}

	public function count($where, $what = '*', $condition = null, $group = null) {
		//Let's clean the sting
		$where = Database::cleanString($where);
		$what = Database::cleanString($what);
		//Selecting the stuff :D 
		$sql = "SELECT COUNT({$what}) as `count` FROM $where ";
		if($condition != null) {
			$sql .= "WHERE ";
			if(is_array($condition)) {
				$whereClause = array();
				foreach($condition as $key => $val) {
					$whereClause[] = "{$key} = {$val} ";
				}
				$sql .= implode(" AND ", $whereClause);
			} else return false; 
		}
		if($group != NULL) {
			$sql .= " GROUP BY `{$group}`";
		}
		return $sql;
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