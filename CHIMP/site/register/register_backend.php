<?php

// 		HANDLES USER REGISTRATION FOR CHIMP 
//
//
require("../headers/header_chunks.php");
require("../db/db_setup.php");
require("../lib.php");

// SET UNIVERSAL POST VARS
$keys = array_keys($_POST);		// this is for the validate() function

// if the email validation link has been clicked, then there is already an entry available in 'temp_register'
// so, we can get most saved-state vars from this database by matching $key to field 'confirmation_code'
if ($key = $_REQUEST['key']) {

	if ($_REQUEST['step']) {
		$step_update_query = "UPDATE temp_register SET step='".$_REQUEST['step']."' WHERE confirmation_code='$key'";
		$result = mysql_query($step_update_query);
	}
	
	$temp_data_query = "SELECT username, password, email, study, pimindcre_group, step FROM temp_register WHERE confirmation_code = '$key'";
	$result = mysql_query($temp_data_query);
	if ($result) {
		$row = mysql_fetch_assoc($result) or die(mysql_error());
		$username = $row['username'];
		$password = $row['password'];
		$email = $row['email'];
		$study = $row['study'];
		$pimindcre_group = $row['pimindcre_group'];
		$step = $row['step'];
	} else {
		die("Sorry, the link that brought you here is not valid.  Please go back to the confirmation email and try it again.");
	}
} elseif (isset($_POST['already_here'])) {	//we get this when an already-reg'd user starts registration process for a new study
	$step = 2;
	$uid = $_POST['already_here'];
	$study = $_POST['study'];
	assignGroup($study);
	
} elseif (isset($_POST['update_user_stats'])) {	//we get this after an already-reg'd user submits consent form for a new study
	$step = 5;
	$uid = $_POST['update_user_stats'];
	$study = $_POST['study'];
} else {	// if not, we know we're at step one, and $study is always passed via POST in this case

	$step = 1;
	$study = $_POST['study'];
}

if ($step == 1) {
	
	
	// CONTINUE WITH POST VARS
	$username = $_POST['uid'];
	$password = $_POST['pwd'];
	$email = $_POST['email'];
	
	// VALIDATE FORM DATA
	$email_blob = array($email,"email","email");
	$username_blob = array($username,"username","alphanumeric");
	$password_blob = array($password,"password","password");
	$all_info = array($email_blob, $username_blob, $password_blob);
	
	validate($all_info, $HTML_header_A, $HTML_header_B);
	duplicateCheck($username, $email, $HTML_header_A, $HTML_header_B, $HTML_closer);
	if ($study == 3) {
		assignGroup($study);
		$group_field = ", group";
		$group_value = ",'$this_group_num'";
	} else {
		$group_field = "";
		$group_value = "";		
	}
	
	// IF VALID, ENTER DATA INTO temp_register DB TABLE, SEND VALIDATION EMAIL W/ UNIQUE CODE
	$validate_code = md5(uniqid(rand()));
	$table = "temp_register";
	$field_names = "confirmation_code, username, password, email, study, step $group_field";
	$field_values = "'$validate_code', '$username', '$password', '$email', '$study', '1' $group_value";
	$temp_query = "INSERT INTO $table($field_names) VALUES ($field_values)";
	$result = mysql_query($temp_query);
	
	require("../headers/header.php");
	if ($result) {
		sendValidationEmail($username, $password, $email, $validate_code);
		echo "<br /><br />An email has been sent to the address you provided. <br />
		Please click on the link in the email to proceed with the registration process.  Thank you.
		$HTML_closer";
		die;
	} else {
		echo "<br /><br />Sorry, something went wrong -- please go back to the registration page and try again. <br />
		Should the problem persist, please contact us at chimpresearch@gmail.com and let us know about it.  Thank you.
		$HTML_closer";
		die;
	}
} elseif ($step == 2) {
	
	require("../headers/header.php");
	// PROVIDE APPROPRIATE CONSENT FORM
	echo "<h3>REGISTRATION - Step 2 of 3</h3>";
	require("../studies/consent.php");

	
} elseif ($step == 3) {
	require("../headers/header.php");
	require("register2.php");
	
} elseif ($step == 4) {

	// CONTINUE SETTING POST VARS
	$b_month = $_POST['birth_month'];
	$b_year = $_POST['birth_year'];
		$birthdate = $b_year."-".$b_month."-00";
	$gender = $_POST['gender'];
	$race = $_POST['race'];
	$other_race = $_POST['other_race'];
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
	$md5_password = md5($password);

	//
	// NEED TO VALIDATE SOME OF THESE FIELDS
	//	make blobs, call validate()
	//
	$instruments_blob = array($instruments,"instruments","name");
	$otherrace_blob = array($other_race, "other_race", "alpha");
	$all_info = array($instruments_blob, $otherrace_blob);

	validate($all_info, $HTML_header_A, $HTML_header_B);
	
	
	
	// SETS FIELD NAMES AND VALS FOR participants DB
	$participants_fields = "username, password, email, birthdate, gender, race, hand, artistic, instruments, profession, ed_level, discipline, highest_ed";
	$participants_values = array($username,$md5_password, $email, $birthdate, $gender, $race, $hand, $artist, $instruments, $profession, $edinfo_degrees, $edinfo_major, $highest_degree);
	
	$snick = getStudyNickname($study);

	// HERE WE PASS INFO FOR BOTH participants AND study_members DATA
	// WE NEED TO EXTRACT MORE INFO IN ORDER TO SUCCESSFULLY POPULATE THE study_members DB, SEE '/chimp/lib.php' FOR MORE
	$members_fields = "id, status, timepoint";
	$uid = registerUser($participants_fields, $participants_values, $members_fields, $snick, $pimindcre_group);
	deleteTempData($key);
	sendConfirmationEmail($username, $password, $email);


	if ($study < 3) {
		$timelimit = 24;
	} elseif ($study == 3) {
		$timelimit = 72;
	}
	
	require("../headers/header.php");
	echo "<br /><br /><br /><br />
	<div align=\"center\">
	Thank you, $username, you are now a registered user.  A confirmation email has been sent to the email address you submitted.  
	<br /><br />
	Please be advised that you have $timelimit hours to complete this portion of the study, starting now.  <br />
	This should give you plenty of time to complete the necessary tasks.  <br />
	If you do not complete all the tasks in this portion of the study within the alloted time period, 
	you will not be able to continue to participate in this survey.  <br /><br />
	At this point, you may choose whether to proceed directly to the first task, or return to the CHIMP home page and begin at a later time.<br />
	If you wish to begin at a later time, simply click the link of the appropriate study name 
	in your 'List of Current Studies' on the right side of the page.<br /><br />
	To proceed directly to the project you've signed up for, <a href=\"/chimp/studies/index.php?s=$study&u=$uid&cont=1\">click here</a>.
	<br />
	To return to the CHIMP home page, <a href=\"/chimp/\">click here</a>.
	</div>$HTML_closer";


	// LOGS ACTIVITY: UPLOAD
	//$message = "New user $username registered";
	//writeLog($username, "REG_NEW_USER", "MEDIUM", $message);
} elseif ($step == 5) {

	$name = getStudyNickname($study);
	updateStudyMembers($name, $uid);
	$username = getUsernameFromID($uid);
	
	require("../headers/header.php");
	echo "<br /><br /><br /><br />
		<div align=\"center\">
		Thank you, $username, you are now registered for this study.  
		<br /><br />
		To proceed directly to the project you've signed up for, <a href=\"/chimp/studies/index.php?s=$study&u=$uid\">click here</a>.
		<br />
		To return to the CHIMP home page, <a href=\"/chimp/\">click here</a>.
		</div>
		</body>
		</html>";
}

?> 