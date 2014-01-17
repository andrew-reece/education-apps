<?php

########################################################
###													####
### FUNCTIONS USED BY CHIMP REGISTRATION SYSTEM		####
### (mostly used by /register/register_backend.php	####
###													####
########################################################

//////////////
// in the case of registration for PIMINDCRE, assigns participant one of three groups (control, control-training, training) 
//////////////
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




//////////////
// assigns participants to one of several survey orders available in some studies (so far, PICOM and PIMINDCRE)
// called by insertStudyMembers() and updateStudyMembers()
//////////////
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




//////////////
//changes participant password (first validates info, and finally sends confirm email after change)
//////////////
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




//////////////
// deletes data from 'temp_register' table when participant is confirmed and moved into 'participants' table
//////////////
function deleteTempData($k) {
	$query = "DELETE FROM temp_register WHERE confirmation_code = '$k'";
	$result = mysql_query($query);
}




//////////////
// checks for either duplicate username or duplicate email (2 users names attempted for same email)
// spits out an error if either is detected
//////////////
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




//////////////
// queries for study nickname based on study id #
//////////////
function getStudyNickname($s) {
	// SETS FIELD NAMES AND VALS FOR study_members DB
	$studyname = "SELECT nickname FROM study_index WHERE id = '$s'";
	$sn_result = mysql_query($studyname);
	$row = mysql_fetch_assoc($sn_result);
	return $row['nickname'];
}




//////////////
// queries for username based on participant id #
//////////////
function getUsernameFromID($id) {
	$query = "SELECT username FROM participants WHERE id = '$id'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	return $row['username'];
}





//////////////
// adds new participant to 'study_members' table, along with relevant data about which study he is signed up for
// called by registerUser()
//////////////
function insertStudyMembers($s, $fields, $values) {

	//if necessary, assigns participant to appropriate groups and adds this data to mysql query string
	if (($s == 'picom') || ($s == 'pimindcre')) {
		$group_data = assignSurveyOrder($s);
		$fields .= ",".$group_data[0];
		$values .= ",'$group_data[1]'";
	}
	
	// REGISTERS USER AS SIGNED UP FOR A PARTICULAR STUDY IN study_members DB
	$study_query = "INSERT INTO study_members ($fields) VALUES ($values)";
	mysql_query($study_query);
}




//////////////
// registers new user in all relevant databases
//////////////
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
	mysql_query($query);

	$get_id = "SELECT id FROM participants WHERE username = '$uname'";
	$result = mysql_query($get_id);
	if($result) {
		$row = mysql_fetch_assoc($result);
		$id = $row['id'];
	} else {
		die('No result found in get_id query in registerUser function in registration_functions.php');
	}

	$study_val_string = "'$id', '1', $pmc_grp";
	
	insertStudyMembers($study, $study_key_string, $study_val_string);
	
	return $id;
	
}





//////////////
// sends confirmation email after some change to user status
//////////////
function sendConfirmationEmail($uname, $pwd, $mail) {
	// SENDS CONFIRMATION EMAIL
	$details = "Username: $uname \n
	Password: $pwd \n";
	require("mailer.php");
	mailUser("first", $mail, $uname, $details);
}





//////////////
// sends initial email to new user after step 1 of registration process
// ## WE SHOULD PROBABLY MOVE THIS INTO 'mailer.php' FUNCTIONS ##
//////////////
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





//////////////
// updates a user's info in 'study_members' table to reflect having signed up for an additional study
// called in $step=5 section of register_backend.php
//////////////
function updateStudyMembers($field, $id) {

	$first_set = "$field = '1'";
	$second_set = "";
	
	//if necessary, assigns participant to appropriate groups and adds this data to mysql query string
	if (($field == 'picom') || ($field == 'pimindcre')) {
		$group_data = assignSurveyOrder($field);
		$second_set = ",$group_data[0] = '$group_data[1]'";
	}
	
	$query = "UPDATE study_members SET $first_set $second_set WHERE id = '$id'";
	mysql_query($query);
}





//////////////
// validates form input using old function from 'functions_library.php'
// called in $step=1 and $step=3 on 'register_backend.php', maybe elsewhere too
//////////////
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