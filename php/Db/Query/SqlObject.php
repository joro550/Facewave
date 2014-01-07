<?php


class SqlObject {
	private $mSql;

	public function __construct($query, $what) {
		if(method_exists($this, $query))  {
			$this->{$query}($what);
		}	
	}

	private function select($what) {
		$queryString = 'SELECT ';
		if(is_array($what)) {
			$whatArray = array();
			foreach($what as $key => $val) {
				$key = Database::cleanString($key);
				$val = Database::cleanString($val);

				if(substr($key, 0, 6) == 'SELECT') {

					$whatArray[] = "(`{$key}`) AS '{$val}'";
				} else if($val != '') {
					$whatArray[] = "`{$key}` AS '{$val}'";
				} else {
					$whatArray[] = "{$key}";
				}	
			}
			$queryString .= implode(", ", $whatArray);
		} else {
			$what = Database::cleanString($what);

			$queryString .= $what;
		}

		$this->mSql = $queryString;

		return $this;
	}

	public function from($tables) {
		$queryString = ' FROM ';
		if(is_array($tables)) {
			implode(", ", $tables);
		} else {
			$tables = strtolower($tables);
			$queryString .= "`{$tables}` ";
		}

		$this->mSql .= $queryString;
		return $this;
	}

	public function getQueryString() {
		return $this->mSql;
	}

	public function where($where = null, $queryCond) {
		$queryString = ' WHERE ';
		if($where == NULL) {
			$queryString .= '1';
		} else {
			if(is_array($where)) {
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
				$queryString .= implode(" {$queryCond} ", $whereClause);
			} else {
				$queryString .= $where;
			}
		}
		$this->mSql .= $queryString;
		return $this;
	}

	public function whereAnd($where) {
		return $this->where($where, 'AND');
	}

	public function whereOr($where) {
		return $this->where($where, 'OR');
	}

	public function group($group) {
		$this->mSql .= " GROUP BY `{$group}`";
		return $this;
	}

	public function order($order) {
		if($order != null) {
			$this->mSql .= " ORDER BY `{$order}`";
		}
		return $this;
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