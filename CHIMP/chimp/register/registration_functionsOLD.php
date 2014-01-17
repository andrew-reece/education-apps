<?php

function assignGroup($s) {

	global $this_group_num;
	
	// in the case that the study is PIMINDCRE, 
	// we need to assign a group (control, control-training, training) before showing the consent form, as consent form will be different
	// for different groups.  so we query for the last group assignment, and increment by 1, or reset to 1, depending on the current value
	if ($s == '3') {
		$group_query = "SELECT pimindcre_group FROM study_members ORDER BY id DESC LIMIT 1";
		$result = mysql_query($group_query);
		$row = mysql_fetch_assoc($result);
		$group_num = $row ['pimindcre_group'];
		$this_group_num = ($group_num < 3) ? $group_num + 1 : 1;
	} else {
		$this_group_num = 0;
	}
}


//used by registerUser() to assign participants to one of several survey orders available in some studies
function assignSurveyOrder($s) {
	if ($s == 'picom') {
		$query = "SELECT picom_survey_order FROM study_members ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$order_num = $row['picom_survey_order'];
		
		//there are 120 possible combinations of the 5 questionnaires in PICOM
		//so, if the last survey_order number was 120, we need to reset at 1.  otherwise, just increment to the next combination order number
		$this_order_num = ($order_num == 120) ? 1 : $order_num + 1;
		
		return array("picom_survey_order", "'$this_order_num'");
		
	} elseif ($s == 'pimindcre') {
	
		//gets data from previous entry in table regarding which groups the participant was assigned to
		$query = "SELECT pimindcre_group, pimindcre_survey_order FROM study_members ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$order_num = $row['pimindcre_survey_order'];
	
		//there are 120 possible combinations of the 5 questionnaires in PICOM
		//so, if the previous survey_order number was 120, we need to reset at 1.  otherwise, just increment to the next combination order number
		$this_order_num = ($order_num == 120) ? 1 : $order_num + 1;
		
		return array("pimindcre_survey_order", "$this_order_num");
	}
}


function changePassword($user, $oldpwd, $newpwd, $md5_old, $md5_new) {
	
	// CHECKS THAT USERNAME AND OLD PASSWORD MATCH
	$query = "SELECT email, username FROM participants WHERE username = '$user' AND password = '$md5_old'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$found = mysql_num_rows($result);
	mysql_free_result($result);
	
	if (!$found) {
		
		// LOGS ACTIVITY
		//$message = "$user failed to match usr/combo while attempting pwd change, with usr = $user, pwd = $oldpwd";
		//writeLog($user, "CHANGE_USER_PWD", "HIGH", $message);
		
		$error = "<div id=\"centered\"><br />The username and password do not match. 
		<br />Please go <a href=\"javascript: history.go(-1)\">back</a> and try again.  
		<br />You can also contact us for help at: chimpresearch AT gmail DOT com.</div>";
		
		return $error;
		die;
	}
	
	// CHECKS FOR PROPER PASSWORD COMPATIBILITY
	$regexp = "/^\w*(?=\w*\d)(?=\w*[a-zA-Z])\w*$/";
	if (!preg_match($regexp,$newpwd)) {	
	
		// LOGS ACTIVITY
		//$message = "$user attempted to enter an invalid string for new pwd.";
		//writeLog($user, "CHANGE_PASSWORD", "MEDIUM", $message);

		$error = "<br /><br /><br />Passwords must contain at least one letter and one number. 
		<br />Please go <a href=\"javascript: history.go(-1)\">back</a> and try again.  
		<br />You can also contact us for help at: chimpresearch AT gmail DOT com.";
		
		return $error;
		die;
	}

	// IF NO ERRORS, SETS NEW PASSWORD
	$query = "UPDATE participants SET password = '$md5_new' WHERE username = '$user'";
	mysql_query($query);
	
	// LOGS ACTIVITY
	//$message = "$user successfully changed pwd.";
	//writeLog($user, "CHANGE_PASSWORD", "LOW", $message);
	
	$email = $row['email'];
	$username = $row['username'];
	$details = "You just changed passwords.  Your new password is $newpwd.";
	
	require("mailer.php");
	mailUser("update", $email, $username, $details);
	
	return "<br /><br /><br />
		<div align=\"center\">Password changed successfully!
		<br />An email has been sent to your registered email address with the updated information.
		<br />To login to CHIMP with your new password, <a href=\"/chimp/register/logout.php\">click here</a>.</div></body></html>";

}


function deleteTempData($k) {
	$query = "DELETE FROM temp_register WHERE confirmation_code = '$k'";
	$result = mysql_query($query);
}


function duplicateCheck($uname, $mail, $h1, $h2, $h3) {
	// CHECK TO SEE IF USERNAME IS ALREADY TAKEN
	$username_query = "SELECT username, email FROM participants WHERE username = '$uname' OR email = '$mail'";
	$result = mysql_query($username_query);

	if ($row = mysql_fetch_assoc($result)) {
		// here we figure out if it's either the username or the email that flagged the alert, and we set the report accordingly
		if ($uname == $row['username']) {
			
			$HTML_title = "ERROR: USERNAME TAKEN";
			$HTML_body = "<br /><br /><br /><br /><br />
			<div align=\"center\">
			<span class=\"bluebold\">\"$uname\"</span> is already taken as a username.  
			<br />
			(You can use the \"Check Availability\" button on the registration page to see if your choice is available.)
			<br />
			If you are already registered and have forgotten your password, 
			<a href=\"#\" onclick=\"javascript:ajax('forgot','password','$uname','../');\">click here</a> to have a new password sent
			to the email address under which you originally registered.  
			<br />
			Otherwise, please go <a href=\"javascript: history.go(-1)\">back</a> and pick a different name.  Thank you.</div>
			<br /><br />
			<div id=\"response\" style=\"color:red\"></div>";
			
		} elseif ($mail == $row['email']) {
		
			$HTML_title = "ERROR: EMAIL ALREADY REGISTERED IN DATABASE";
			$HTML_body = "<br /><br /><br /><br /><br />
			<div align=\"center\">
			<span class=\"bluebold\">\"$mail\"</span> is already registered with another username in our database. Only one CHIMP account can be registered per email address.
			<br />
			If you have forgotten your username, but this is your email address, <a href=\"#\" onclick=\"javascript:ajax('forgot','username','$mail','../');\">click here</a> to have your username sent to this address.  
			<br />
			Otherwise, please go <a href=\"javascript: history.go(-1)\">back</a> and pick a different name.  Thank you.</div>
			<br /><br />
			<div id=\"response\" style=\"color:red; text-align: center;\"></div>";
		}
		die($h1.$HTML_title.$h2.$HTML_body.$h3); 
	}
	// END FUNCTION
}

function registerUser($key_string, $val_array, $study_key_string, $study, $pmc_grp) {

	$uname = $val_array[0];

	// feeds array values into string
	foreach ($val_array as $val) {
		$val_string .= "'$val',";
	}
	// knocks off last comma from string
	$val_string = rtrim($val_string, ",");

	// REGISTERS USER INFO IN participants DB
	$query = "INSERT INTO participants ($key_string) VALUES ($val_string)";
	mysql_query($query) or die("line 162".mysql_error());

	$get_id = "SELECT id FROM participants WHERE username = '$uname'";
	$result = mysql_query($get_id) or die("line 165".mysql_error());
	if($result) {
		$row = mysql_fetch_assoc($result);
		$id = $row['id'];
	} else {
		die('No result found in get_id query in registerUser function in registration_functions.php');
	}

	$study_val_string = "'$id', '1', $pmc_grp";
	
	//if necessary, assigns participant to appropriate groups and adds this data to mysql query string
	if (($study == 'picom') || ($study == 'pimindcre')) {
		$group_data = assignSurveyOrder($study);
		$study_key_string .= ",".$group_data[0];
		$study_val_string .= ",'$group_data[1]'";
	}
	
	// REGISTERS USER AS SIGNED UP FOR A PARTICULAR STUDY IN study_members DB
	$study_query = "INSERT INTO study_members ($study_key_string) VALUES ($study_val_string)";
	mysql_query($study_query) or die("error line 184<br />".$study_query."  groupdata1 = $group_data[1] and now the error: ".mysql_error());
	
}

function sendConfirmationEmail($uname, $pwd, $mail) {
	// SENDS CONFIRMATION EMAIL
	$details = "Username: $uname \n
	Password: $pwd \n";
	require("mailer.php");
	mailUser("first", $mail, $uname, $details);
}

function sendValidationEmail($uname, $pwd, $to, $code) {

	// Your subject
	$subject="CHIMP User Registration: Validation Email";

	// From
	$header="from: The CHIMP Team <chimpresearch@gmail.com>";

	// Your message
	$message="Welcome to CHIMP! \r\n \r\n";
	$message .= "Your username is: $uname and your password is: $pwd \r\n \r\n";
	$message.="Please click on this link to continue the registration process: \r\n";
	$message.="http://dev.technicalspiral.com/chimp/register/register_backend.php?step=2&key=$code \r\n";
	$message .= "Thanks so much!  \r\n Sincerely, \r\n The CHIMP Team (chimpresearch@gmail.com)";

	// send email
	$sentmail = mail($to,$subject,$message,$header);
}

function validate($data, $h1, $h2) {

	// VALIDATES EACH FORM INPUT BY DATATYPE (SEE: functions_library.php)
	for ($i=0;$i<count($data);$i++) {
		//TEST
		//$stuff .= $data[$i][0].$data[$i][1].$data[$i][2]."<br />";
		
		$validation_return = validateFormInput($data[$i][0],$data[$i][1],$data[$i][2]);
		
		// if $validation_return is not empty, it means an error was detected.  $error incrememts, error message is printed
		if(!empty($validation_return)) {
		
			$HTML_title = "ERROR: DATA SUBMISSION ERROR DETECTED";
			$error++;	
			if ($error == 1) {

				$HTML_body = "<span class=\"bluebold\"> 
				Errors in your registration info have detected.  Please note the following: </span> <br /><br />";
				// HTML closer is added later
				echo $h1.$HTML_title.$h2.$HTML_body;
			}
			echo $validation_return;
		}
	}

	// if validation finishes with no errors, DB entry proceeds, else die()
	if (!$error == 0) {
		die("<br />Please go <a href=\"javascript: history.go(-1)\">back</a> and re-enter data correctly. <br /> <br /> $HTML_closer");
	}
	// END VALIDATION
}


?>