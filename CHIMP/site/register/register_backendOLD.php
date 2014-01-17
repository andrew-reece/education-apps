<?php

// 		HANDLES USER REGISTRATION FOR CHIMP 
//
//
require("ureg_HTML_headers.php");
require("../db/db_setup.php");
require("writelog.php");
require("../functions_library.php");

// SET POST VARS
$study = $_POST['study'];
$username = $_POST['uid'];
$password = $_POST['pwd'];
$md5_password = md5($password);
$email = $_POST['email'];
$b_month = $_POST['birth_month'];
$b_year = $_POST['birth_year'];
	$birthdate = $b_year."-".$b_month."-00";
$gender = $_POST['gender'];
$race = $_POST['race'];
$hand = $_POST['hand'];
$artist = $_POST['artist'];
$instruments = $_POST['instruments'];
$ed_pos = $_POST['educational_position'];
	if ($ed_pos == 'student') {
		$profession = "student";
	} elseif ($ed_pos == 'educator') {
		$profession = "educator";
	} elseif ($ed_pos == 'neither') {
		$profession = $_POST['profession'];
	}
$edinfo_major = $_POST['edinfo_major'];
$edinfo_degrees = $_POST['edinfo_degrees'];
$highest_degree = $_POST['highest_degree'];

//
$email_blob = array($email,"email","email");
$username_blob = array($username,"username","alphanumeric");
$password_blob = array($password,"password","password");

$all_info = array($email_blob,$username_blob,$password_blob);
$keys = array_keys($_POST);

// VALIDATES EACH FORM INPUT BY DATATYPE (SEE: functions_library.php)
for ($i=0;$i<count($all_info);$i++) {
	//TEST
	//$stuff .= $all_info[$i][0].$all_info[$i][1].$all_info[$i][2]."<br />";
	
	$validation_return = validateFormInput($all_info[$i][0],$all_info[$i][1],$all_info[$i][2]);
	
	// if $validation_return is not empty, it means an error was detected.  $error incrememts, error message is printed
	if(!empty($validation_return)) {
	
		$HTML_title = "ERROR: DATA SUBMISSION ERROR DETECTED";
		$error++;	
		if ($error == 1) {

			$HTML_body = "<span class=\"bluebold\"> Errors in your registration info have detected.  Please note the following: </span> <br /><br />";
			// HTML closer is added later
			echo $HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body;
		}
		echo $validation_return;
	}
}


// if validation finishes with no errors, DB entry proceeds, else die()
if (!$error == 0) {
	die("<br />Please go <a href=\"javascript: history.go(-1)\">back</a> and re-enter data correctly. <br /> <br /> $HTML_closer");
}
// END VALIDATION


// CHECK TO SEE IF FIRSTNAME-SURNAME COMBO IS IN STUDENT REGISTRY
// IF SO, ASSIGNS STUDENT_ID
$inreg_query = "SELECT participant_id, username FROM participants WHERE username = '$username'";
$result = mysql_query($inreg_query);

if ($row = mysql_fetch_assoc($result)) {
	$dup_username = $row['username'];
	$dup_id = $row['participant_id'];

	$HTML_title = "ERROR: USERNAME TAKEN";
	$HTML_body = "<br /><br /><br /><br /><br />
	<div align=\"center\">
	<span class=\"bluebold\">\"$username\"</span> is already taken as a username.  
	<br />
	(You can use the \"Check Availability\" button on the registration page to see if your choice is available.)
	<br />
	If are already registered and have forgotten your password, <a href=\"send_pass.php?username=$dup_username\">click here</a> to have it sent to the email address which you originally registered under.  
	<br />
	Otherwise, please go <a href=\"javascript: history.go(-1)\">back</a> and pick a different name.  Thank you.</div>";
	die($HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body.$HTML_closer); 
}

// REGISTERS USER INFO IN DATABASE
$query = "INSERT INTO participants (study, username,password,email, birthdate, gender, race, hand, artistic, instruments, profession, ed_level, discipline, highest_ed) 
VALUES ('$study','$username','$md5_password', '$email', '$birthdate', '$gender', '$race', '$hand', '$artist', '$instruments', '$profession', '$edinfo_degrees', '$edinfo_major', '$highest_degree')";
mysql_query($query) or die(mysql_error());

// SENDS CONFIRMATION EMAIL
$details = "Username: $username \n
Password: $password \n";
require("mailer.php");
mailUser("first", $email, $username, $details);

// OUTPUT HTML
$HTML_title = "USER REGISTRATION COMPLETE";
echo $HTML_header_A.$HTML_title.$HTML_header_B;

//TEST
//echo $validation_return."<br /><br />";
//echo $query;

echo "<br /><br /><br /><br />";
echo "<div align=\"center\">Thank you, $username, you are now a registered user.  Your login info has been sent to the email address you submitted.  <br /><br />To login to CHIMP Institute, <a href=\"../index.html\">click here</a>.</div>$HTML_closer";


// LOGS ACTIVITY: UPLOAD
$message = "New user $username registered";
writeLog($username, "REG_NEW_USER", "MEDIUM", $message);
?> 