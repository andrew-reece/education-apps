<?php

########################################################
###													####
### FUNCTIONS USED BY ENTIRE CHIMP SYSTEM			####
### (exception for ajax functions, found in ajax/)	####
###													####
########################################################

//////////////
// checks whether a user (uid: $u) has already submitted responses to a particular survey (survey id: $s)
// called by logResults()
//////////////
function alreadySubmitted($u, $s) {

	$query = "SELECT * FROM survey_data WHERE participant_id = '$u' AND survey_id = '$s'";
	$result = mysql_query($query);
	
	if ($data = mysql_fetch_assoc($result)) {
		return TRUE;
	} else {
		return FALSE;
	}
}





//////////////
// in the case of registration for PIMINDCRE, assigns participant one of three groups (control, control-training, training) 
//////////////
function assignGroup($s) {

	global $this_group_num;
	
	// in the case that the study is PIMINDCRE, 
	// we need to assign a group (control, control-training, training) before showing the consent form, as consent form will be different
	// for different groups.  so we query for the last group assignment, and increment by 1, or reset to 1, depending on the current value
	if ($s == '3') {
		$group_query = "SELECT group FROM members_pimindcre ORDER BY id DESC LIMIT 1";
		$result = mysql_query($group_query);
		$row = mysql_fetch_assoc($result);
		$group_num = $row ['group'];
		$this_group_num = ($group_num < 3) ? $group_num + 1 : 1;
	} else {
		$this_group_num = 0;
	}
}




//////////////
// assigns participants to one of several survey orders available in some studies (so far, PICOM and PIMINDCRE)
// called by insertStudyMembers() and updateStudyMembers()
//////////////
function assignSurveyOrder($nickname) {
	if ($nickname == 'picom') {
		$query = "SELECT survey_order FROM members_picom ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$order_num = ($row) ? $row['survey_order'] : 0;
		
		//there are 120 possible combinations of the 5 questionnaires in PICOM
		//so, if the last survey_order number was 120, we need to reset at 1.  otherwise, just increment to the next combination order number
		$this_order_num = ($order_num == 120) ? 1 : $order_num + 1;
		
		
	} elseif ($nickname == 'pimindcre') {		//THIS PART NEEDS WORK, NEED TO SEED FACTORIALS FIRST!!! - NOV 23 2010
	
		//gets data from previous entry in table regarding which groups the participant was assigned to
		$query = "SELECT group, survey_order FROM members_pimindcre ORDER BY id DESC LIMIT 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$order_num = ($row) ? $row['survey_order'] : 0;
	
		//there are 120 possible combinations of the 5 questionnaires in PICOM
		//so, if the previous survey_order number was 120, we need to reset at 1.  otherwise, just increment to the next combination order number
		$this_order_num = ($order_num == 120) ? 1 : $order_num + 1;
		
	}
	
	return $this_order_num;
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
// checks at 'info.php' screen to see what the registration status is for this user, for this study (registered, removed, open, etc)
//////////////
function checkIfAlreadyRegistered($nick, $username) {
	$table = "members_$nick";
	$query = "SELECT $table.status, participants.id
			FROM participants, $table
			WHERE participants.username = '$username' AND $table.id = participants.id";
	$result = mysql_query($query);
	$data = mysql_fetch_array($result);
	return $data;
}





//////////////
// called when a user submits one survey -- we need to figure out what to do next, so we come to this checkpoint
// how it works:
//		first get number of survey/quest. elements involved in the current study
//		then, see how far along user is by comparing their position in these elements ($current) with the total number ($num_elements)
//		if they're equal, this means user is either at the end of one timepoint, or at the end of a study - adjust + return data accordingly
//		if $current < $num_elements, simply increment $current and then return to /studies/ to print up the next survey in the bunch
// called by /studies/index.php
//////////////
function checkpoint($snick, $id, $current, $surveyid) {
	
	$num_elements = getNumberOfSurveyElements($snick);
	
	if ($current == $num_elements) {
		$equal_tp = compareTimepoints($snick, $id, 'user-to-total');
		
		if ($equal_tp) {
			markStudyComplete($snick, $id);
			return 'study';	//participant has completed study
		} else {
			newTimepoint($snick, $id);
			return 'timepoint';	//participant has completed this timepoint 
		}
	} else {
		incrementStatus($snick, $current, $id);
		return 'survey';		//participant is ready to go onto next survey in this timepoint
	}
}




//////////////
// compares user's current timepoint w/ total timepoints in study
// called by checkpoint() and by switch statement in 'studies/index.php'
//////////////
function compareTimepoints($nick, $uid, $which) {

	$table = "members_$nick";
	//gets user's current timepoint for a given study (tracked by 'status' field in members_XX, ex 'members_picom')
	$user_tp = $table.".timepoint";
		
	if ($which == 'user-to-total') {	//gets total timepoints of study
		$studyfield = "timepoints";
		$study_tp = "study_index.timepoints";
	} elseif ($which == 'user-to-current') {	//gets current timepoint for a study
		$studyfield = "current_timepoint";
		$study_tp = "study_index.current_timepoint";
	}
	
	$query = "SELECT $study_tp, $user_tp FROM study_index, $table WHERE $table.id = '$uid' AND study_index.nickname = '$nick'";
	$result = mysql_query($query);
	$data = mysql_fetch_assoc($result);
	$study = $data[$studyfield];
	$user = $data['timepoint'];
	$equal = ($user == $study) ? TRUE : FALSE;
		
	return $equal;
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
// sets countdown timestamp once a survey has been completed in the picom study
// called by checkpoint()
//////////////
function getCompareCountdownTS($uid, $svid) {
	
	$query = "SELECT TS_compare_countdown FROM members_picom WHERE id = '$uid'";
	$result = mysql_query($query);
	$data = mysql_fetch_assoc($result) or die("QUERY: $query <br /> ERROR (getCompareCountdownTS()): ".mysql_error());
	return $data['TS_compare_countdown'];
}




//////////////
// gets user ID in table 'participants' from username field
//////////////
function getIDFromUsername($username) {
	$query = "SELECT id FROM participants WHERE username = '$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result) or die("No result found in getIDFromUsername() with username $username");
	return $row['id'];
}





//////////////
// queries for total number of study/questionnaire elements based on study nickname
//////////////
function getNumberOfSurveyElements($nick) {
	$query1 = "SELECT survey_elements FROM study_index WHERE nickname = '$nick'";
	$result = mysql_query($query1);
	$data = mysql_fetch_assoc($result);
	return $data['survey_elements'];
}





//////////////
// queries for study full name based on study id #
//////////////
function getStudyFullname($s) {

	$studyname = "SELECT name FROM study_index WHERE id = '$s'";
	$sn_result = mysql_query($studyname);
	$row = mysql_fetch_assoc($sn_result);
	return $row['name'];
}





//////////////
// queries for study nickname based on study id #
//////////////
function getStudyNickname($s) {

	$studyname = "SELECT nickname FROM study_index WHERE id = '$s'";
	$sn_result = mysql_query($studyname);
	$row = mysql_fetch_assoc($sn_result);
	return $row['nickname'];
}





//////////////
// queries for study status (registered, in progress, completed) of a particular user based on study nickname and user id
//////////////
function getStudyStatus($nickname, $id) {
	$table = "members_$nickname";
	$status_query = "SELECT status FROM $table WHERE id = '$id'";
	$result = mysql_query($status_query);
	$data = mysql_fetch_assoc($result);
	return $data["status"];
}




//////////////
// queries for full data packet for the particular study we want to print
// called from printSurvey()
//////////////
function getSurveyData($surveys, $step) {
	if ($step == -1) {
		die("Sorry, you have been removed from this study because you didn't complete all the requirements within the time provided.");
	} else {
		//we just take the Nth, or $step index of the $surveys array we just defined, to get the proper study to print
		//actually, $step-1 because the array index starts at 0 and the step count starts at 1
		$this_survey = $surveys[$step-1];

		$survey_list = implode("_",$surveys);
		$query = "SELECT * FROM survey_index WHERE id = '$this_survey'";
		$result = mysql_query($query);
		$data = mysql_fetch_assoc($result) or die("Surveys are $survey_list, this survey is $this_survey and survey count is ".count($surveys)."and step is $step");
		return $data;
	}
}





//////////////
// queries for assigned survey order based on study and participant id #
// called from getSurveyOrderParts()
//////////////
function getSurveyOrder($sn, $id) {
	$table = "members_$sn";
	$query = "SELECT survey_order FROM $table WHERE id = '$id'";
	$result = mysql_query($query) or die("getSurveyOrder() error ...".mysql_error());
	$row = mysql_fetch_assoc($result) or die("Error in getSurveyOrder(), mysql_error: ".mysql_error());
	$o = ($row) ? $row["survey_order"] : $query;

	return $o;
}




	
//////////////
// queries for assigned survey order based on study and participant id #
// called from printSurvey()
//////////////
function getSurveyOrderParts($sid, $nick, $i) {
	$order = getSurveyOrder($nick, $i);
	$query = "SELECT ordering FROM order_index WHERE study = '$sid' AND variant = '$order'";
	$result = mysql_query($query);
	$data = mysql_fetch_assoc($result) or die("study id is $sid and order is $order and nick is $nick and user id is $i ...Error in getSurveyOrderParts() ".mysql_error());
	return $data['ordering'];
}





//////////////
// queries for user's allotted time remaining either for an in-between survey break (picom) or for the entire timepoint (picom, pimindcre)
//////////////
function getTimeRemaining($id, $s, $sv, $scope='total') {

	//gets seconds count of current timestamp
	$date2 = new DateTime();
	$now = $date2->format('Y-m-d-H-i-s');
	$nows = explode("-", $now);
	$now_secs = mktime($nows[3],$nows[4],$nows[5],$nows[1],$nows[2],$nows[0]);
	
	
	if ($scope == 'segment') {	//for calculating time left on 10 min break between 'picom' surveys
	
		$query = "SELECT 
			time 
		FROM 
			survey_data
		WHERE 
			participant_id = '$id' AND 
			survey_id = '$sv'";
		$result = mysql_query($query);
		$data = mysql_fetch_assoc($result);
		$stamp = $data['time'];
		
		$date1 = new DateTime($stamp);
		$then = $date1->format('Y-m-d-H-i-s');
		
		setCompareCountdownTS($id, $sv);
		$now = str_replace(":","-",getCompareCountdownTS($id, $sv));
		$now = str_replace(" ","-",$now);
		
		$thens = explode("-", $then);
		$nows = explode("-", $now);
		
		//minutes are off by about 7.5 min
		$then_secs = mktime($thens[3],$thens[4],$thens[5],$thens[1],$thens[2],$thens[0]);
		
		$difference = $now_secs - $then_secs;
		
		if ($difference > 600) {
			die( "You are disqualified. From getTimeRemaining(). Difference: $difference <br />Then: $then <br />Now: $now<br />Now secs: $now_secs <br /> Then secs: $then_secs");
		} else {
			$minutes1 = floor($difference / 60);
			$seconds1 = ($difference % 60);
			$minutes = 9 - $minutes1;
			$seconds = 59 - $seconds1;
			//TEST
			//echo "Stamp: $stamp <br /> Difference: $difference <br />Then: $then <br />Now: $now<br />Now secs: $now_secs <br /> Then secs: $then_secs<br />Minutes: $minutes1 <br />Seconds: $seconds1 <br />Minutes & Seconds Left: $minutes & $seconds<br />";
			if ($seconds < 10) { $seconds = "0".$seconds; }
			$remaining = "0".$minutes.":".$seconds;
		}
			
	} elseif ($scope == 'total') {	//for calculating total time left in a timepoint (24 or 72 hours total - time elapsed)
	
		$nick = getStudyNickname($s);
		$table = "members_$nick";
		
		//query for timestamp when user first signed up for a study
		$query = "SELECT start_time FROM $table WHERE id = '$id'";
		$result = mysql_query($query);
		$data = mysql_fetch_assoc($result) or die("QUERY: $query <br /> ERROR: ".mysql_error());
		$start = $data['start_time'];
		
		//get seconds count of start time
		$date1 = new DateTime($start);
		$then = $date1->format('Y-m-d-H-i-s');
		$thens = explode("-", $then);
		$then_secs = mktime($thens[0],$thens[1],$thens[2],$thens[3],$thens[4],$thens[5]);
		
		$difference = $now_secs - $then_secs;
		$days = floor($difference / 86400);
		$hours = floor($difference/ 3600);
		$minutes = floor($difference / 60);
		$seconds = $difference % 60;
		
		$remaining = "$days days, $hours, hours, $minutes minutes, $seconds seconds";
	}
	
	return $remaining;
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
// increments user status for a particular study by 1, indicating they have moved on to the next survey in that study
// called by checkpoint()
//////////////
function incrementStatus($nick, $cur, $uid) {
	$new_status = $cur + 1;
	$table = "members_$nick";
	$query = "UPDATE $table SET status = '$new_status' WHERE id = '$uid'";
	mysql_query($query);
}




//////////////
// adds new participant to 'members_XX' table
// called by registerUser()
//////////////
function insertStudyMembers($nick, $fields, $values) {

	$table = "members_$nick";
	
	//if necessary, assigns participant to appropriate groups and adds this data to mysql query string
	if (($nick == 'picom') || ($nick == 'pimindcre')) {
		$order = assignSurveyOrder($nick);
		$fields .= ",survey_order";
		$values .= ",'$order'";
	}
	
	// REGISTERS USER AS SIGNED UP FOR A PARTICULAR STUDY IN study_members DB
	$query = "INSERT INTO $table ($fields) VALUES ($values)";
	mysql_query($query) or die("query is $query and error is: ".mysql_error());
}





//////////////
// enters participant's questionnaire answers/data into DB
//////////////
function logResults($data) {

	$uid = $data['u'];
	$survey = $data['sv'];
	
	//check to see if user has already submitted this survey's answers
	$repeat_submission = alreadySubmitted($uid, $survey);
	
	if ($repeat_submission) {
		return FALSE;	//repeat has been found, so we cancel logResults() and pop out an error message in studies/index.php
		
	} else {	//no repeat, so continue recording results in DB
		$fields = "participant_id,survey_id,";
		$values = "'$uid','$survey',";
		
		$keys = array_keys($data);
		$counter = 0;
		
		//search for POST vars (held in $data) that are question answers -- these are all in the form of "$q##", where # is either 1 or 2 digits
		//if match, then adds var name (ie. $q12) to fields string, and var value (ie. 3) to values string
		foreach ($keys as $k) {
			$rgx = "/^q[0-9]+$/";
			if (preg_match($rgx, $k)) {
				$counter++;
				$survey_fields .= "$k,";
				//check for omitted responses
				//in case of ambiguity questionnaire (survey id = 4), omitted value = 4; all others, omitted = 999
				if (!$data[$k] && $data['s'] == 4) {
					$val = 4;
				} elseif (!$data[$k]) {
					$val = 999;
				} else {
					$val = $data[$k];
				}
				$survey_values .= "'$val',";
				$running_total = $running_total + $data[$k];
			}
		}
		
		$average_score = $running_total / $counter;
		$fields .= "average,total,".$survey_fields;
		$values .= "'$average_score','$running_total',".$survey_values;
		//rtrim() removes last comma from end of fields & values strings
		
		$date = new DateTime();
		$now = $date->format('Y-m-d-H-i-s');
		$fields .= "time";
		$values .= "'$now'";
		
		$query = "INSERT INTO survey_data ($fields) VALUES ($values)";
		mysql_query($query) or die("QUERY: $query and ERRROR: ".mysql_error());
		
		return TRUE;
	}
}





//////////////
// assigns "99" value to study status once user has completed all timepoints and all tasks
//////////////
function markStudyComplete($nick, $uid) {
	$table = "members_$nick";
	$query = "UPDATE $table SET status = '99' WHERE id = '$uid'";
	mysql_query($query);
}





//////////////
// updates 'study_members' to reflect new timepoint (looks like: [studynickname]_timepoint+1, ex. picom_timepoint = picom_timepoint + 1)
// also sets 'nickname' field (usually referred to as $status) to 1 so when new tp begins, surveys begin anew
//////////////
function newTimepoint($nick, $uid) {
	$table = "members_$nick";
	$query = "UPDATE $table SET status = '1', timepoint = timepoint+1 WHERE id = '$uid'";
	mysql_query($query);
}





//////////////
// figures out which survey/questionnaire is needed, and prints it out in HTML
//////////////
function printSurvey($s, $sn, $id, $step) {

	$survey_parts = getSurveyOrderParts($s, $sn, $id);
	$surveys = explode(",", $survey_parts);
	$sdata = getSurveyData($surveys, $step);
	
	//assign vars from $sdata pack of survey information
	$survey_id = $sdata['id'];
	$nickname = $sdata['nickname'];
	$basepoint = $sdata['basepoint'];
	$direction = $sdata['direction'];
	$num_possible_answers = $sdata['choices'];
	$answer_blob = $sdata['choices_verbose'];
	$answers = explode(",", $answer_blob);
	$intro = $sdata['intro'];
	$cols = $num_possible_answers + 2; 		// add 2 for # and Statement columns
	
	// set head of survey with generic title and table settings
	$head = "<a name=\"surveytop\"></a>
			<h3>Survey #$step</h3> 
			<div id=\"msg\"></div>
			<b>$intro</b>
			<div id=\"q\">
			<form name=\"q\" method=\"POST\" action=\"/chimp/studies/\" onsubmit=\"return checkSurvey(this, $num_possible_answers, $s);\">
			<input type=\"hidden\" name=\"s\" value=\"$s\">
			<input type=\"hidden\" name=\"sv\" value=\"$survey_id\">			
			<input type=\"hidden\" name=\"u\" value=\"$id\">
			<input type=\"hidden\" name=\"c\" value=\"1\">
			<input type=\"hidden\" id=\"secondpass\" name=\"secondpass\" value=\"0\">
			<table id=\"surveytable\" cols=\"$cols\" border=\"1\">";
	
	// top row has column titles, specific to each survey
	$toprow = "	<tr>
		<td class=\"qhead\"> # </td>
		<td class=\"qhead\"> Statement </td>";
	foreach ($answers as $a) { $toprow .= "<td class=\"qhead\"> $a </td>"; }	
	$toprow .= "</tr>";
	
	echo $head.$toprow;
	
	$filename = "questions/$nickname.txt";
	$handle = fopen($filename, "r") or die("Function printSurvey() error: File wouldn't open with nickname $sn ...");
	$count = 1;
	while (!feof($handle)) {
		$statement = fgets($handle);
		
		//checks for reverse coding symbol; if re-coded, then knocks off * and changes $direction (which outputs the answer values)
		if (strpos($statement, '*') === 0) {
		
			$statement = ltrim($statement, "*");
			$temp_direction = $direction;
			
			if ($direction == 'up') {
			
				$direction = 'down';
				$temp_basepoint = $basepoint;
				$basepoint = $num_possible_answers;
				
			} elseif ($direction == 'down') {
				$direction = 'up';
				$temp_basepoint = $basepoint;
				$basepoint = 1;
			}
		}
		
		//on alternating lines of the survey table, we change the color, just for aesthetic purposes.  this adjusts the css class name.
		$colored_class = ($count % 2 == 0) ? "-colored" : "";
		
		//beginning each row with number and actual statement to be evaluated
		$thisrow = "<tr name=\"row$count\" class=\"qrow$colored_class\">
		<td class=\"qline\"> $count </td>
		<td class=\"qline\"> $statement </td>";
		
		 
		//$direction tells us whether the scoring is going from low to high, or high to low
		if ($direction == 'up') {
			//$basepoint tells us what number to start with
			if ($basepoint == 0) {
				for ($i = 0; $i < $num_possible_answers; $i++) {
					$thisrow .= "
					<td class=\"qpoint\"> <input name=\"q$count\" value=\"$i\" type=\"radio\"> </td>";
				}
			//difference is in $i < $num or $i <= $num, depending on whether we start at 1 or 0
			} elseif ($basepoint == 1) {
				for ($i = 1; $i <= $num_possible_answers; $i++) {
					$thisrow .= "
					<td class=\"qpoint\"> <input name=\"q$count\" value=\"$i\" type=\"radio\"> </td>";
				}
			}
		} elseif ($direction == 'down') {
			//On Ambiguity Scale, 4 is a score reserved for omissions, so we make a condition here that sets up for that exception
			if ($nickname == 'amb') {
				for ($i = $basepoint; $i >= 1; $i--) {
					if ($i != 4) {
						$thisrow .= "
						<td class=\"qpoint\"> <input name=\"q$count\" value=\"$i\" type=\"radio\"> </td>";
					}
				}
			//normal descending scoring system here
			} else {
				for ($i = $basepoint; $i >= 1; $i--) {
					$thisrow .= "
					<td class=\"qpoint\"> <input name=\"q$count\" value=\"$i\" type=\"radio\"> </td>";
				}
			}
		} else {
			for ($i = 0; $i < $num_possible_answers; $i++) {
					$thisrow .= "
					<td class=\"qpoint\"> <input name=\"q$count\" value=\"$i\" type=\"radio\">x $direction x</td>";
				}
		}
		$thisrow .= "</tr>\n";
		echo $thisrow;
		$count++;
		if($temp_direction != '') {
			$direction = $temp_direction;
			$temp_direction = '';
			$basepoint = $temp_basepoint;
		}
	}
	
	echo "</table>
		<br />
		<input id=\"qsubmit\" type=\"submit\" value=\"Submit Responses\">
		</form>
		</div>
		</body>
		</html>";
}








//////////////
// registers new user in all relevant databases
// called by register/	egistration_backend.php
//////////////
function registerUser($p_fields, $p_values, $m_fields, $study, $pmc_grp='') {

	$uname = $p_values[0];
	$md5pwd = $p_values[1];
	
	// feeds array values into string
	foreach ($p_values as $val) {
		$str_p_values .= "'$val',";
	}
	// knocks off last comma from string
	$str_p_values = rtrim($str_p_values, ",");

	// REGISTERS USER INFO IN participants DB
	$query = "INSERT INTO participants ($p_fields) VALUES ($str_p_values)";
	mysql_query($query) or die("QUERY: $query <br /> ERROR (registerUser()): ".mysql_error());

	$uid = getIDFromUsername($uname);
	
	$m_values = "'$uid', '1', '1'";
	
	if ($pmc_grp) {
		$m_fields .= ",group";
		$m_values .= ",'$pmc_grp'";
	}
	insertStudyMembers($study, $m_fields, $m_values);
	
	// Cookie expires when browser closes
	setcookie('chimpusername', $uname, false, '/chimp', 'dev.technicalspiral.com');
	setcookie('chimppassword', $md5pwd, false, '/chimp', 'dev.technicalspiral.com');
	
	return $uid;
	
}





//////////////
// takes a user off the participants roster for a study, marks it so that he can't sign up again
//////////////
function removeUserFromStudy($uname, $s) {
	$nick = getStudyNickname($s);
	$uid = getIDFromUsername($uname);
	$table = "members_$nick";
	$query = "UPDATE $table SET status = -1 WHERE id = '$uid'";
	mysql_query($query) or die("QUERY: $query <br /> ERROR: ".mysql_error());
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
// sets countdown timestamp once a survey has been completed in the picom study
// called by checkpoint()
//////////////
function setCompareCountdownTS($uid, $svid) {
	$date = new DateTime();
	$now = $date->format('Y-m-d-H-i-s');
	
	$query = "UPDATE members_picom SET TS_compare_countdown = '$now' WHERE id = '$uid'";
	mysql_query($query) or die("QUERY: $query <br /> ERROR (setCompareCountdownTS()): ".mysql_error());
}




//////////////
// updates a user's info in 'study_members' table to reflect having signed up for an additional study
// called in $step=5 section of register_backend.php
//////////////
function updateStudyMembers($nick, $id) {

	$table = "members_$nick";
	
	//if necessary, assigns participant to appropriate groups and adds this data to mysql query string
	if (($nick == 'picom') || ($nick == 'pimindcre')) {
		$order = assignSurveyOrder($nick);
	}
	
	$query = "UPDATE $table SET status = '1', survey_order = '$order' WHERE id = '$id'";
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

//
// VALIDATES FORM INPUT ACCORDING TO TYPE
//
	function validateFormInput($data,$key,$target) {	
		
		switch($target) {
		
		case "alpha": 	// a-z A-Z
		
			if (!empty($data) && !ctype_alpha($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters only. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}	
			break;
		
		
		case "name": 	// a-z A-Z plus space and not-first-character apostrophe
			$regexp = "/^[a-zA-Z\s]+\'?$/";
			
			if (!empty($data) && !preg_match($regexp, trim($data))) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters, and blank spaces or apostrophes ('). You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}	
			break;
		
		case "alphanumeric": 	// a-z A-Z 0-9
		
			if (!empty($data) && !ctype_alnum($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters and/or numbers. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;

		case 'digit': 	//  0-9
		
			if (!empty($data) && !ctype_digit($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of numbers only. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;

		case "text": 	// alphanumeric plus symbols , ' " . ! etc etc
			$regexp = "/^[^@\$\{\}\;\+\*=]+$/";
		
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span>is not valid. It cannot contain characters \"@\", \"\$\", \"(\", \")\", \";\", \"+\", or \"=\". You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
			
		case "email":
			$regexp = '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';

			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not a valid email address. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
		
		// USED FOR REGISTER USERNAME/PASSWORD
		case "password":
			$regexp = "/^\w*(?=\w*\d)(?=\w*[a-zA-Z])\w*$/";
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not a valid password. Passwords must contain at least one letter and one number.  You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
			
		case "phone":	// allows for straight digits or in +(123) 9871234987 format

			$regexp = "/^\+?\(?[0-9]{1,3}\)?[0-9]{8,15}$/";
			
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be in the format +(123)1234-5678, or in an unbroken string of numbers (total digits may vary). You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value. Please go back and re-enter. <br /><br />";
			}		
			break;
			
		case "date":
			if (!empty($data) && !checkdate($data[1],$data[2],$data[0])) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. You have entered <span class=\"error_detail\">\" $data[0]-$data[1]-$data[2] \"</span> for this value, which represents YYYY-MM-DD. Make sure you have selected a valid day for the appropriate month and/or year. Please go back and re-enter.<br /><br />";
			}
			break;
		case "non-validating":
			$error_msg = "";
			break;
		default: 
			$error_msg = "for $key there is nothing to validate!! <br /><br />";
			break;
		}
	
		if(!empty($error_msg)) { 
			$return_msg = $error_msg;
		} else {
			$return_msg = "";
		}
		
		return $return_msg;
	}	
?>