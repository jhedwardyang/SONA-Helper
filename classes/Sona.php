<?php
class Sona{
	const tableName		=		"sona";
	
	const sonaID		=		"sonaID";
	const sonaUsername	=		"sonaUsername";
	const sonaPassword	=		"sonaPassword";
	const sonaStatus	=		"sonaStatus";
	const sonaPriority	=		"sonaPriority";
	const sonaCount		=		"sonaCount";
	const sonaPhoneStatus	=	"sonaPhoneStatus";
	const sonaPhone			=		"sonaPhone";
	
	const sonaStatusConfirmed	=	1;
	const sonaStatusCreated		=	2;
	const sonaStatusBanned		=	3;
	static $sonaStatusTypes		=	array("None", "Confirmed", "Created", "Banned");
	
	const sonaPriorityHighest	=	1;
	const sonaPriorityHigh		=	2;
	const sonaPriorityMedium	=	3;
	const sonaPriorityLow		=	4;
	const sonaPriorityLowest	=	5;
	static $sonaPriorityTypes	=	array("None", "Highest", "High", "Medium", "Low", "Lowest");
	
	const sonaPhoneStatusOn		=	1;
	const sonaPhoneStatusOff	=	0;
	
	public static function checkUsername($sonaUsername){
		global $db;
		$where = array(self::sonaUsername => $sonaUsername);
		$result = $db->select(self::tableName,array(self::sonaID),$where);
		$count = 0;
		while($row = mysql_fetch_array($result)){
			$count++;
		}
		if($count>0){
			return false;
		}else{
			return true;
		}
	}
	public static function updateSonaCount($sonaID, $sonaCount){
		global $db;
		$set = array(self::sonaCount => safe($sonaCount));
		$where = array(self::sonaID => $sonaID);
		return $db->update(self::tableName, $set, $where);
	}
	public static function getAllConfirmedUsers(){
		global $db;
		$where = array(self::sonaStatus => self::sonaStatusConfirmed);
		$rows = array(self::sonaID, self::sonaUsername, self::sonaPassword, self::sonaCount, self::sonaPhoneStatus, self::sonaPhone);
		$result = $db->select(self::tableName,$rows,$where,NULL,array(self::sonaPriority => Database::ORDER_BY_ASC, self::sonaID => Database::ORDER_BY_ASC));
		$return = array();
		while($row = mysql_fetch_array($result)){
			$row[self::sonaPassword] = Config::decrypt($row[self::sonaPassword]);
			array_push($return, $row);
		}
		return $return;
	}
	
	public static function addUser($sonaUsername,$sonaPassword,$sonaStatus=NULL,$sonaPriority=NULL,$sonaCount=NULL,$sonaPhoneStatus=NULL,$sonaPhone=NULL){
		global $db;
		if(empty($sonaStatus)) $sonaStatus = self::sonaStatusCreated;
		if(empty($sonaPriority)) $sonaPriority = self::sonaPriorityMedium;
		if(empty($sonaCount)) $sonaCount = 0;
		if(empty($sonaPhoneStatus)) $sonaPhoneStatus = self::sonaPhoneStatusOff;
		if(empty($sonaPhone)) $sonaPhone = "";
		
		$set = array();
		$set[self::sonaUsername] = safe($sonaUsername);
		$set[self::sonaPassword] = Config::encrypt(safe($sonaPassword));
		$set[self::sonaStatus] = safe($sonaStatus);
		$set[self::sonaPriority] = safe($sonaPriority);
		$set[self::sonaCount] = safe($sonaCount);
		$set[self::sonaPhoneStatus] = safe($sonaPhoneStatus);
		$set[self::sonaPhone] = safe($sonePhone);
		
		return $db->insert(self::tableName,$set);
	}
	
	public static function confirmUser($sonaID=NULL,$sonaUsername=NULL){
		if(empty($sonaID) && empty($sonaUsername)){
			return false;
		}else{
			global $db;
			if(empty($sonaUsername)){
				return $db->update(self::tableName, array(self::sonaStatus => self::sonaStatusConfirmed), array(self::sonaID => $sonaID));
			}else{//empty($sonaID)
				return $db->update(self::tableName, array(self::sonaStatus => self::sonaStatusConfirmed), array(self::sonaUsername => $sonaUsername));
			}
		}
	}
}``
?>