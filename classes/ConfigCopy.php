<?php
/* 
class Config{	
 	static $db_host		=		"localhost";
 	static $db_user		=		"db_username";
 	static $db_pass		=		"db_password";
 	static $db_name		=		"db_name";
 	static $full_dir	=		"/public_html/";
 	static $web_dir		=		"http://www.example.com/";
	static $emailhost	=		"mail.example.com";
	static $emailuser	=		"sona@example.com";
	static $emailpass	=		"password";
	static $emailfrom	=		"Sona Helper <sona@example.com>";
	
	public static function encrypt($string, $key='EXAMPLEHASH'){
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
	}
	public static function decrypt($encrypted, $key='EXAMPLEHASH'){
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}
}
*/
?>