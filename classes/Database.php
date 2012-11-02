<?php
class Database{
	var $db_link;
	
	
	const ORDER_BY_DESC	=	"DESC";
	const ORDER_BY_ASC	=	"ASC";
	
	function __construct(){
		$this->db_link = mysql_connect(Config::$db_host,Config::$db_user,Config::$db_pass) or die("Error N01: Could not connect.".mysql_error());
		mysql_select_db(Config::$db_name) or die("Error N02: Could not connect.".mysql_error());
	}
	
	public static function safe($unsafeString){
		$safeString = mysql_real_escape_string($unsafeString);
		return $safeString;
	}
	
	public static function safer($unsafeString){
		$safeString = mysql_real_escape_string(htmlspecialchars(trim($unsafeString)));
		return $safeString;
	}
	
	public function select($tableName, $rows=NULL, $where=NULL, $group_by=NULL, $order_by=NULL, $limit=NULL){
		if(!empty($tableName)){
			$query = "SELECT ";

			//rows
			$isFirst = true;
			if(!empty($rows)){
				if(is_array($rows)){
					foreach($rows as $k){
						if($isFirst) $isFirst=false;
						else $query.= ", ";
						$query.= self::safe($k)." ";
					}
				}else{
					$query.= $rows." ";
				}
			}else{
				$query.= "* ";
			}
			
			$query.= "FROM `".self::safe($tableName)."` ";
			
			//where
			$isFirst = true;
			if(!empty($where)){
				$query.= "WHERE ";
				if(is_array($where)){
					foreach($where as $k=>$v){
						if($isFirst) $isFirst=false;
						else $query.= "AND ";
						$query.= self::safe($k)."='".self::safe($v)."' ";
					}
				}else{
					$query.= self::safe($where);
				}
			}
			
			//group_by
			$isFirst = true;
			if(!empty($group_by)){
				$query.= "GROUP BY ";
				foreach($group_by as $k){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k);	
				}
			}
			
			//order_by
			$isFirst = true;
			if(!empty($order_by)){
				$query.= "ORDER BY ";
				foreach($order_by as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k)." ".self::safe($v);
				}
			}
			
			//limit
			if(!empty($limit)){
				$query.= "LIMIT ".self::safe($limit);
			}
			
			return self::runQuery($query);
		}else{
			return false;
		}
	}
	
	public function update($tableName, $set, $where, $order_by=NULL, $limit=NULL){
		if(!empty($tableName)){
			$query = "UPDATE `".self::safe($tableName)."` ";
	
			//set
			$isFirst = true;
			if(!empty($set)){
				$query.= "SET ";
				foreach($set as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k)."='".self::safe($v)."' ";
				}
			}
	
			//where
			$isFirst = true;
			if(!empty($where)){
				$query.= "WHERE ";
				foreach($where as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= "AND ";
					$query.= self::safe($k)."='".self::safe($v)."' ";
				}
			}
	
			//order_by
			$isFirst = true;
			if(!empty($order_by)){
				$query.= "ORDER BY ";
				foreach($order_by as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k)." ".self::safe($v);
				}
			}
	
			//limit
			if(!empty($limit)){
				$query.= "LIMIT ".self::safe($limit);
			}
	
			return self::runQuery($query);
		}else{
			return false;
		}
	}
	
	public function insert($tableName, $set){
		if(!empty($tableName)){
			$query = "INSERT INTO `".self::safe($tableName)."` ";
		
			
			//set
			$isFirst = true;
			if(!empty($set)){
				$query.= "SET ";
				foreach($set as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k)." = '".self::safe($v)."' ";
				}
			}
			
			return self::runQuery($query);
		}else{
			return false;
		}
	}
	
	public function delete($tableName, $where, $order_by=NULL, $limit=NULL){
		if(!empty($tableName)){
			$query = "DELETE FROM `".self::safe($tableName)."` ";
		
			//where
			$isFirst = true;
			if(!empty($where)){
				$query.= "WHERE ";
				foreach($where as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= "AND ";
					$query.= self::safe($k)."='".self::safe($v)."' ";
				}
			}
		
			//order_by
			$isFirst = true;
			if(!empty($order_by)){
				$query.= "ORDER BY ";
				foreach($order_by as $k=>$v){
					if($isFirst) $isFirst=false;
					else $query.= ", ";
					$query.= self::safe($k)." ".self::safe($v);
				}
			}
		
			//limit
			if(!empty($limit)){
				$query.= "LIMIT ".self::safe($limit);
			}
		
			return self::runQuery($query);
		}else{
			return false;
		}		
	}
	
	public function runQuery($query){
		$result = mysql_query($query,$this->db_link) or die("Error N03: Could not query. (Query: ".$query.") ".mysql_error());
		if($result) return $result;
		else return false;
	} 
	
	function __destruct(){
		if(isset($this->db_link)){
			mysql_close($this->db_link);
		}else{
			mysql_close();
		}
	}
}
?>