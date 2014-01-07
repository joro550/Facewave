<?php


class QueryCreator {
	public function select($table, $what, $where = NULL, $order = NULL) {
		$sql = 'SELECT ';
		if(is_array($what)) {
			$whatArray = array();
			$i = 1;
			foreach($what as $w) {
				if(substr($w, 0, 6) == 'SELECT') {
					$whatArray[] = "({$w}) AS '{$i}'";
					$i++;
				} else {
					$whatArray[] = $w;
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
				// $key = $this->cleanString($key);
				// $val = $this->cleanString($val);

				if(is_array($val)) {
					$whereClause[] = "`{$key}` IN (" . implode($val, ", ") . ")";
				} else {
					// $condition = substr($val, 0);
					// $condition = $this->getCondition($val[0]);
					// if($condition != '=') {
					// 	$val = substr($val, 1);
					// }
					$whereClause[] = "`{$key}` = '{$val}'";
					$sql .= implode(" AND ", $whereClause);
				}
			}
		}

		return $sql;
	}

	public function update($table, $what) {

	}

	public function insert($table, $what) {

	}

	public function count($where, $what = '*', $condition = null) {
		//Let's clean the sting
		// $where = $this->cleanString($where);
		// $what = $this->cleanString($what);
		//Selecting the stuff :D 
		$sql = "SELECT COUNT({$what}) as `count` FROM $where ";
		if($condition != null) {
			$sql .= "WHERE ";
			if(is_array($condition)) {
				$whereClause = array();
				foreach($condition as $key => $val) {
					if(is_array($val)) {
						$whereClause[] = "{$key} IN (" . implode(', ', $val) . ')';
					} else {
						$whereClause[] = "{$key} = {$val} ";
					}
				}
				$sql .= implode(" AND ", $whereClause);
			} else return false; 
		}
		// echo $sql;
		return $sql;
	}




}