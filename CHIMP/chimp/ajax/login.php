<?php


function login($from, $username, $password='', $which='', $backup='') {

	require($backup."db/db_setup.php");

	// FROM register_user.php
	if ($from == 'register') {

		$query = "SELECT username FROM participants WHERE username = '$username'";

		$result = mysql_query($query);
		if($row = mysql_fetch_row($result)) {
			$return_text = "0_Sorry, this username is taken.  Please try another one.";
		} else {
			$return_text = "1_Username is available!";
		}
		
	// FROM login.php
	} elseif ($from == 'login') {
		$md5_pass = md5($password);
		
		$alnum = "/^[a-zA-Z0-9]+$/";
		if (!preg_match($alnum,$username) || !preg_match($alnum,$password)) {	
			// INVALID ENTRIES DETECTED
			
			// LOG ACTIVITY
			$message = "Invalid format for either usr or pwd, usr: $username, pwd: $password";
			writeLog($username, "LOGIN", "HIGH", $message);
			
			$return_text = "0_Username and/or password is invalid.";
		} else {
			$query = "SELECT id, username FROM participants WHERE username = '$username' AND password = '$md5_pass'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$row_count = mysql_num_rows($result);
			if ($row_count == 0) {		// NO MATCH
			
				// LOG ACTIVITY
				//$message = "Mismatched usr/pwd combo, usr: $username, pwd: $password";
				//writeLog($username, "LOGIN", "MEDIUM", $message);
			
				$return_text = "0_Sorry, this username and password pair do not match. Please try again.<br /><form id=\"login\" name=\"login\">
			<b>User Name:</b> <input name=\"username\" type=\"text\" size=\"12\"><br /><b>Password:</b> <input type=\"text\" name=\"password\" size=\"10\"><br /><input type=\"submit\" value=\"Log Into CHIMP\" onClick=\"ajax('login','login'); return false;\"></form><div id=\"current-text\"></div>Forgot your password? <a href=\"register/forgot.php\">Click here for assistance.</a></div><div id=\"current-text\"></div>";
			} else { 				// LOGIN SUCCESS!  NOW SET COOKIES
			
				//$access = $row['access_level'];
				$this_user = $row['username'];
				$uid = $row['id'];
				
			// !!!!
			////	 MAKE SURE TO SET NEW COOKIE INFO WHEN SWITCHING SITES
			// !!!!

				// Cookie expires when browser closes
				setcookie('chimpusername', $username, false, '/chimp', 'dev.technicalspiral.com');
				setcookie('chimppassword', $md5_pass, false, '/chimp', 'dev.technicalspiral.com');
				//setcookie('chimpaccesslevel', $access, false, '/chimp', 'dev.technicalspiral.com');
				
				// LOG ACTIVITY
				//$message = "Logged in, usr: $username, pwd: $password";
				//writeLog($username, "LOGIN", "LOW", $message);
			
				$text = getCurrentStudies($uid);
					
		$return_text = "1_Login successful! Welcome, <b>$this_user</b>.<br />Please select from your list of current studies to continue.<br />_$text";
			}
		}
	} elseif ($from == 'forgot') {		// FORGOTTEN PASSWORD REQUEST
		
		$which = $_GET['which'];
		
		// this is how we figure out if we've passed an email address (requesting username) or username (requesting password)
		$where = (strpos($username,"@")) ? "email" : "username";
		$query = "SELECT username, email FROM participants WHERE $where = '$username'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);

		
		if (!$row) {		// UNRECOGNIZED USERNAME
		
			// LOG ACTIVITY
			//$message = "Failed pwd retrieve attempt, usr: $username";
			//writeLog($username, "FORGOT PWD", "MEDIUM", $message);
			
			$return_text = "Sorry, that username is unrecognized. Username: $username and query is: $query";
		} else {
			
			$email = $row['email'];

			if ($which == 'password') {
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
			
				$query = "UPDATE participants SET password = '$md5_genpass' WHERE username = '$username'";
				mysql_query($query);
			
				// LOG ACTIVITY
				//$message = "New temporary password sent to $username at $email";
				//writeLog($username, "FORGOT PWD", "LOW", $message);
			
				$details = "Your new password is $gen_pass.  Please change it from your User Home Page control panel when you next log in.";
				require($backup."register/mailer.php");
				mailUser("forgot", $email, $username, $details);
				
				$return_text = "An email has been sent to your account with your new temporary password.";
			
			} elseif ($which == 'username') {
			
				$this_user = $row['username'];
				$details = "Your username is: $this_user  \n\r Please remember that you can only register one username per CHIMP participant.";
				require($backup."register/mailer.php");
				mailUser("forgot", $email, $username, $details);
			
				$return_text = "An email has been sent to your account reminding you of the username that you have already registered.";
			
			
			} else {
				$return_text = "Oops, an error occurred.  
				<br />
				Please notify the CHIMP team and let us know what you were trying to do when this happened (chimpresearch@gmail.com).
				<br /> Thanks!";
			}
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
		
	} elseif ($from == 'index') {
		$id_query = "SELECT id FROM participants WHERE username = '$username'";
		$result = mysql_query($id_query);
		$row = mysql_fetch_assoc($result);
		$return_text = getCurrentStudies($row['id']);
	}
	
	echo $return_text;

}



function getCurrentStudies($user_id) {
// HERE WE ARE GETTING DATA ON WHAT STUDIES THIS USER IS SIGNED UP FOR, TO DISPLAY ON HOME PAGE
			
			
	$currentstudies_query = "SELECT 
								picom, 
								picre, 
								pimindcre
							FROM 
								study_members
							WHERE 
								study_members.id = '$user_id'";
	$result = mysql_query($currentstudies_query);
	$row = mysql_fetch_assoc($result) or die("query is: $currentstudies_query");
	$these_names = array_keys($row);
			
	// FOR BOTH ROW & ROW2, IT'S AN ASSOC ARRAY SO I CAN'T USE INDICES LIKE ROW[0] TO REFERENCE
	// IT'S LAZY AND EXCESSIVE, BUT FOR NOW I USED array_keys on $row and $row2 and then I use their index to get the assoc name,
	// then I pass that var as the index on the $row or $row2 assoc arrays.  I'll fix it later.
		
	$studycount = 0;
	for ($i=0; $i<count($row); $i++) {
		$n = $these_names[$i];
		$isregistered = $row[$n];
				
		if ($isregistered > 0) {
			$name_query = "SELECT id, name FROM study_index WHERE nickname = '$n'";
			$result = mysql_query($name_query);
			$data = mysql_fetch_assoc($result);
			$this_name = $data['name'];		
			$this_study_id = $data['id'];
			$study_text .= "<li><a href=\"/chimp/studies/index.php?s=$this_study_id&u=$user_id&cont=1\">$this_name</a> </li>";	
					
			$studycount++;
		}
	}
			
	if ($studycount > 0) {		
		return "<ul>".$study_text."</ul>";
	} else {
		return "<span style=\"color:red;padding-left:20px;\">Not currently registered for any studies!</span>";
	}
}

// WHAT HAPPENS IF COOKIES ARE SET AND IT'S THE HOMEPAGE?
if (!$cookies_set && !$homepage) {
	$from = $_GET['from'];
	$username = $_GET['username'];
	$password = $_GET['password'];
	$which = $_GET['which'];
	$up = "../";
	login($from, $username, $password, $which, $up);
} 
?>