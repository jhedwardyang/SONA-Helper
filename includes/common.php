<?php
$classes = array_merge(glob("../classes/*.php"),glob("classes/*.php"));
foreach($classes as $class){
	require_once($class);
}
$db = new Database();

function print_arr($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}
function safe($unsafeString){
	$safeString = mysql_real_escape_string($unsafeString);
	return $safeString;
}
?>