<?php

require("ureg_db_setup.php");
require("writelog.php");

$from = $_GET['from'];
$username = $_GET['username'];

// FROM register_user.php
if ($from == 'register') {

	$query = "SELECT username FROM user_access WHERE username = '$username'";

	$result = mysql_query($query);
	if($row = mysql_fetch_row($result)) {
		$return_text = "0_Sorry, this username is taken.  Please try another one.";
	} else {
		$return_text = "1_Username is available!";
	}
	
// FROM login.php
} elseif ($from == 'login') {

	$password = $_GET['password'];
	$remember = $_GET['remember'];
	$md5_pass = md5($password);
	
	$alnum = "/^[a-zA-Z0-9]+$/";
	if (!preg_match($alnum,$username) || !preg_match($alnum,$password)) {	
		// INVALID ENTRIES DETECTED
		
		// LOG ACTIVITY
		$message = "Invalid format for either usr or pwd, usr: $username, pwd: $password";
		writeLog($username, "LOGIN", "HIGH", $message);
		
		$return_text = "0_Username and/or password is invalid.";
	} else {
		$query = "SELECT username, access_level FROM user_access WHERE username = '$username' AND password = '$md5_pass'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$row_count = mysql_num_rows($result);
		if ($row_count == 0) {		// NO MATCH
		
			// LOG ACTIVITY
			$message = "Mismatched usr/pwd combo, usr: $username, pwd: $password";
			writeLog($username, "LOGIN", "MEDIUM", $message);
		
			$return_text = "0_Username and password do not match.";
		} else { 				// LOGIN SUCCESS!  NOW SET COOKIES
		
			$access = $row['access_level'];
			$this_user = $row['username'];
			
		// !!!!
		////	 MAKE SURE TO SET NEW COOKIE INFO WHEN SWITCHING SITES
		// !!!!
			if ($remember) {
	            //Set cookie to last 1 year
	            setcookie('chimpusername', $username, time()+60*60*24*365, '/chimp', 'dev.technicalspiral.com');
	            setcookie('chimppassword', $md5_pass, time()+60*60*24*365, '/chimp', 'dev.technicalspiral.com');
	            setcookie('chimpaccesslevel', $access, time()+60*60*24*365, '/chimp', 'dev.technicalspiral.com');
	        } else {
	            // Cookie expires when browser closes
	            setcookie('chimpusername', $username, false, '/chimp', 'dev.technicalspiral.com');
	            setcookie('chimppassword', $md5_pass, false, '/chimp', 'dev.technicalspiral.com');
	            setcookie('chimpaccesslevel', $access, false, '/chimp', 'dev.technicalspiral.com');
	        }
			
			// LOG ACTIVITY
			$message = "Logged in, usr: $username, pwd: $password";
			writeLog($username, "LOGIN", "LOW", $message);
			
			$return_text = "1_Login successful! Loading user home page for $this_user...";
		}
	}
} elseif ($from == 'forgot') {		// FORGOTTEN PASSWORD REQUEST

	$query = "SELECT password, email FROM user_access WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$return = mysql_num_rows($result);
	mysql_free_result($result);
	
	$email = $row['email'];
	
	if (!$return) {		// UNRECOGNIZED USERNAME
	
		// LOG ACTIVITY
		$message = "Failed pwd retrieve attempt, usr: $username";
		writeLog($username, "FORGOT PWD", "MEDIUM", $message);
		
		$return_text = "Sorry, that username is unrecognized.";
	} else {
	
		// GENERATE NEW TEMPORARY PASSWORD
		// sets 8 character alphanumeric password
		$gen_pass = "";
		srand((double) microtime() * 1000000);
		for($i=0;$i<12;$i++) {
			$char = chr(rand(0,255));
			if (preg_match("/[a-zA-Z0-9]?/",$char)) {
				$gen_pass .= $char;
			} else {
				$i--;
			}
		}
		// to change the number of characters in the password , change the last parameter in substr()
		$gen_pass = substr(base64_encode($gen_pass), 0, 8);
		$md5_genpass = md5($gen_pass);
		
		$query = "UPDATE user_access SET password = '$md5_genpass' WHERE username = '$username'";
		mysql_query($query);
		
		// LOG ACTIVITY
		$message = "New temporary password sent to $username at $email";
		writeLog($username, "FORGOT PWD", "LOW", $message);
		
		$details = "Your new password is $gen_pass.  Please change it from your User Home Page control panel when you next log in.";
		require("mailer.php");
		mailUser("forgot", $email, $username, $details);
		
		$return_text = "An email has been sent to your account with your new temporary password.";
	}
} elseif ($from == 'newaccess') {			// USER ACCESS LEVEL UPGRADE REQUEST

	$new_access = $_GET['new_access'];
	
	// LOG ACTIVITY
	$message = "User requests access level upgrade to Level $new_access";
	writeLog($username, "LVL_UPGRADE_REQ", "LOW", $message);
		
	$details = "User $username requests access level upgrade to Level $new_access.";
	require("mailer.php");
	mailUser("request", "dharmahound@gmail.com", $username, $details);
		
	$return_text = "An email has been sent to the System Administrator with your request.  
	<br>You will be notified by email when your request has been reviewed.  Thank you.";
}
echo $return_text;

?>