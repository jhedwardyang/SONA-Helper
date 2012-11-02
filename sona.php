<?php
require_once('includes/common.php');
include('Mail.php');
$users = Sona::getAllConfirmedUsers();
$isOn = 1; // 1 = on, 0 = off
$allUsers = "";
$totalUsersChecked = 0;
$totalEmailsSent = 0;
echo "Cron Started...<br />";

$smtp = Mail::factory('smtp', array('host' => Config::$emailhost, 'auth' => true, 'username' => Config::$emailuser, 'password' => Config::$emailpass));


$urlLogin = "http://sbe.sona-systems.com/default.aspx";
$urlToPull = "http://sbe.sona-systems.com/all_exp.aspx";

$cookieFile = 'cookie.txt';


$regexViewstate = '/__VIEWSTATE\" value=\"(.*)\"/i';
$regexEventVal  = '/__EVENTVALIDATION\" value=\"(.*)\"/i';


/************************************************
 * utility function: regexExtract
*    use the given regular expression to extract
*    a value from the given text;  $regs will
*    be set to an array of all group values
*    (assuming a match) and the nthValue item
*    from the array is returned as a string
************************************************/
function regexExtract($text, $regex, $regs, $nthValue)
{
	if (preg_match($regex, $text, $regs)) {
	 $result = $regs[$nthValue];
	}
	else {
	 $result = "";
	}
	return $result;
}


foreach($users as $user){
	$username = $user[Sona::sonaUsername];
	$password = $user[Sona::sonaPassword];
	$sonaCount = $user[Sona::sonaCount];
	
	$totalUsersChecked++;
	
	$ch = curl_init();
	
	//first cURL to get __VIEWSTATE and __EVENTVALIDATION
	curl_setopt($ch, CURLOPT_URL, $urlLogin);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = curl_exec($ch);
	
	$viewstate = regexExtract($data,$regexViewstate,$regs,1);
	$eventval = regexExtract($data, $regexEventVal,$regs,1);
	
	$postData = '__VIEWSTATE='.rawurlencode($viewstate)
	          .'&__EVENTVALIDATION='.rawurlencode($eventval)
	          .'&'.rawurlencode('ctl00$ContentPlaceHolder1$userid').'='.$username
	          .'&'.rawurlencode('ctl00$ContentPlaceHolder1$pw').'='.$password
	          .'&'.rawurlencode('ctl00$ContentPlaceHolder1$default_auth_button').'='.'Log In'
	          ;
	
	          
	//second cURL to get __VIEWSTATE and __EVENTVALIDATION, TO GET COOKIE
	curl_setOpt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_URL, $urlLogin);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);     
	$data = curl_exec($ch);
	
	//third cURL to get DATA about studies
	curl_setOpt($ch, CURLOPT_POST, FALSE);
	curl_setopt($ch, CURLOPT_URL, $urlToPull);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
	$data = curl_exec($ch);
	
	preg_match_all("/Timeslots Available/",$data,$available);
	$count = count($available[0]);
	Sona::updateSonaCount($user[Sona::sonaID], $count);
	if($count!=$sonaCount && $count>0){
		echo "Checking user: ".$username."<br />";
		$totalEmailsSent++;
		$to = $username."@mylaurier.ca";
		$allUsers.=$username." ";
		$subject = $count." Stud".($count==1?"y":"ies")." available on SONA.";
		$message="http://sbe.sona-systems.com/

-Compliments of E
";
		$headers = array('From' => Config::$emailfrom, 'To' => $to, 'Subject' => $subject);
		if($isOn==1){
			$mail = $smtp->send($to,$headers,$message);
			echo "Email sent to: ".$username."<br />";
			if(PEAR::isError($mail)) echo "!!E:".$mail->getMessage()."<br />";
			if($user[Sona::sonaPhoneStatus] == Sona::sonaPhoneStatusOn){
				$to = $user[Sona::sonaPhone];
				$mail = $smtp->send($to,$headers,$message);
				if(PEAR::isError($mail)) echo "!!E:".$mail->getMessage()."<br />";
				echo "Text sent to: ".$username."<br />";
			}
		}
	}elseif($count==$sonaCount){
		echo "User skipped: ".$username."<br />";
	}
	curl_close($ch);
}
$to = "jh.edwardyang@gmail.com";
$subject = "Cron Succesfully Run";
$message = $totalUsersChecked." User(s) Checked
".$totalEmailsSent." Email(s) Sent
Emails Sent To: ".$allUsers."
";
$headers = array('From' => Config::$emailfrom, 'To' => $to, 'Subject' => $subject);
if($isOn==1) //$mail = $smtp->send($to,$headers,$message);
if(PEAR::isError($mail)) echo "!!E:".$mail->getMessage()."<br />";
echo "Cron Ended...<br />";
?>