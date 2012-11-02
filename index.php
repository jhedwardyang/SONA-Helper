<?php
require_once('includes/common.php');
$message = "";
if(isset($_GET['post']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['button'])){
	$username = safe($_POST['username']);
	$password = safe($_POST['password']);
	$agree = (isset($_POST['agree'])?safe($_POST['agree']):"");
	if($agree == 'on'){
		if(Sona::checkUsername($username)){
			if($username!="" && $password!=""){
				Sona::addUser($username,$password,Sona::sonaStatusConfirmed,Sona::sonaPriorityMedium,0);
				$message = "User successfully added!";
			}else{
				$message = "Seriously...?";
			}
		}else{
			$message = "You've already been registered.";
		}
	}else{
		$message = "Sorry, the terms and conditions are mandatory";
	}
}
?>
<html>
	<head>
		<title>SONA-Helper, Sponsered by TCCF</title>
	</head>
	<body>
	<?php if($message==""){?>
		<div>
			<h3>TERMS OF SERVICE</h3>
			<p style="font:inherit;width:1000px;">
Updated: November 2nd, 2012 at 2:57PM
<br />
<br />SONA-Helper ("S-H") provides a service of automatically checking your sbe.sona-systems.com (hereforth, the "SONA") account (collectively, the "Service"). Please read these terms and conditions prior to using the Service. By using the Service, you agree to be legally bound by these terms and conditions. If you do not agree with these terms, please do not use the Service.
<br />
<br />Please note that to process your requests for this Service, S-H may send requests to SONA on your behalf and using your user account details. All requests will have Service's headers however will include your account details.
<br />
<br />Privacy: S-H respects your privacy. We will only use information you provide to the Service to check your account or as otherwise described in this document. Nonetheless, we reserve the right at all times to disclose any information as necessary to satisfy any law, regulation or governmental request or avoid liability. When you complete forms online or otherwise provide us information in connection with the Service, you agree to provide current, complete, true and accurate information. You agree not to use a false or misleading name or a name that you are not authorized to use. If we in our sole discretion believe that any such information is untrue, inaccurate, not current or incomplete, S-H may refuse you access to the Service and pursue any appropriate legal remedies. By using the Service, you agree to be legally bound to all Terms and Conditions listed anywhere on this page.
<br />
<br />PLEASE NOTE, S-H WILL MAKE ALL EFFORTS TO PROTECT ALL DATA PROVIDED TO US, HOWEVER, WE PROVIDE NO GUARANTEE TO THE SAFETY OF YOUR INFORMATION.
<br />
<br />Account Termination: We reserve the right to terminate any account for any reason. 
<br />
<br />If you have any problems or concerns, please send an e-mail to edward[at]eyang[dot]ca and be sure to include:
<br />Proof of who you are
<br />How to contact you
<br />
<br />You understand and AGREE that neither S-H, its licensors nor its affiliates are responsible for, or capable of controlling registration of accounts, and S-H, its licensors and affiliates assume no liability for any defamatory, offensive, infringing or illegal content of any user of this service.
<br />
<br />S-H does not warrant that the service will be available or operate in an uninterrupted or error-free manner or that errors or defects will be correct. S-H does not warrant that information available on or through the service is appropriate, accurate or available for use in any particular jurisdiction, and accessing it from jurisdictions where their contents are illegal is expressly prohibited. Some jurisdictions do not allow exclusion of certain implied warranties, so the above exclusions may not apply to you.
<br />
<br />Limitation of Liability: You expressly understand and agree that neither S-H nor any of its licensors or affiliates shall be liable for any damages arising out of or in any way related to this agreement or your use of or inability to use the service, under any theory of liability or cause of action, even if S-H is advised of the possibility of such damages. This limitation applies, without limitation to damages intended to compensate you directly for any loss or injury; damages that do not flow directly from an action, but only from some of the consequences or result of such action (indirect or consequential damages); and any other miscellaneous damages and expenses, such as incidental, special, punitive, consequential or exemplary damages.
<br />
<br />S-H reserves the right to make modifications to this TOS without further notice.
			</p>
		</div>
		<form action="?post" method="post">
			<table>
				<tr>
					<td><label for="username">SONA Username: </label></td>
					<td><input id="username" type="text" name="username" /></td>
				</tr>
				<tr>
					<td><label for="password">SONA Password: </label></td>
					<td><input id="password" type="password" name="password" /></td>
				</tr>
				<tr>
					<td>I AGREE TO THE TERMS OF SERVICE</td>
					<td><input type="checkbox" name="agree" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="button" value="Register!" /></td>
				</tr>
			</table>
		</form>
	<?php }else{ ?>
		<div><?php echo $message; ?></div>
	<?php }?>
	</body>
</html>
