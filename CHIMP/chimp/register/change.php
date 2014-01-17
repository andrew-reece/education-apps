<?php
// FOR CHANGING PASSWORD OR USER DETAILS


$javascript = "<script type=\"text/javascript\" src=\"ureg_ajax.js\"></script>
<script type=\"text/javascript\" src=\"ureg_formcheck.js\"></script>";

require("ureg_HTML_headers.php");
require("ureg_db_setup.php");
require("writelog.php");

$nature = $_REQUEST['serve'];

if ($nature == 'pwd') {			// CHANGE PASSWORD [PAGE]

	$HTML_title = "AGAMA STUDENT REGISTRY: Change Password";
	echo $HTML_header_A.$HTML_title.$HTML_header_B;
	
	echo "<br /><br /><br />";
	echo "<div align=\"center\"><a href=\"user_home.php\">RETURN TO USER HOME PAGE</a>
	<br /><br />
	To change your password, please type in first your old password, then your new desired password.  
	<br />Remember that passwords must be between 8 and 15 characters, and must contain at least one letter and one number.  
	<br />Passwords are case-sensitive.";
	echo "<br /><br /><br />";
	echo "<form name=\"change\" method=\"POST\" action=\"change.php\">
	<table width=\"35%\">
		<tr>
			<td>
				Username: 
			</td>
			<td align=\"right\">
				<input type=\"text\" name=\"user\">
			</td>
		</tr>
		<tr>
			<td>
				Old password: 
			</td>
			<td align=\"right\">
				<input type=\"text\" name=\"old_password\">
			</td>
		</tr>
		<tr>		
			<td>
				<div id=\"verify\"></div>
			</td>
		</tr>
		<tr>
			<td>
				New password: 
			</td>
			<td align=\"right\">
				<input type=\"text\" name=\"password\">
			</td>
		</tr>
		<tr>
			<td>
				Confirm new password: 
			</td>
			<td align=\"right\">
				<input type=\"text\" name=\"confirm_password\">
			</td>
		</tr>
	</table>
	<br />
	<input type=\"hidden\" name=\"serve\" value=\"change_p\">
	<input type=\"submit\" id=\"submitter\" value=\"Make Changes\" onClick=\"return checkForm('change');\">
	</form>
	</div>
	$HTML_closer";

} elseif ($nature == 'det') {	//CHANGE USER DETAILS [PAGE]

	require("cookie_check.php");
	if ($access == 1) {
		$access_title = "Volunteer";
	} elseif ($access == 4) {
		$access_title = "Teacher/Administrator";
	} elseif ($access == 7) {
		$access_title = "Sysadmin";
	} else {
		$access_title = "Unknown";
	}
	$query = "SELECT firstname, surname, email FROM user_access WHERE username = '$user'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	
	
	$HTML_title = "AGAMA STUDENT REGISTRY: Change User Details";
	echo $HTML_header_A.$HTML_title.$HTML_header_B;
	
	echo "<br /><br /><br />";
	echo "<div align=\"center\">
	<b>CHANGE USER DETAILS</b>
	<br /><br />
	To change user info such as name, email, etc., make the desired adjustments in the boxes below and click \"Make Changes\".  
	<br />To request a new user access level, select your desired new level from the drop-down menu box below, then click \"Submit Request\".
	<br /><br />
	<b><u>Change Personal Info</u></b>
	<br />
	
	<form name=\"change_details\" method=\"POST\" action=\"change.php\">
	<table cols=\"2\"  style=\"font-weight:bold;\">
		<tr>
			<td align=\"right\"> First name: </td>
			<td> <input type=\"text\" name=\"firstname\" value=\"".$row['firstname']."\"> </td>
		</tr>
		<tr>
			<td align=\"right\">Surname: </td>
			<td><input type=\"text\" name=\"surname\" value=\"".$row['surname']."\"></td>
		</tr>
		<tr>
			<td align=\"right\">Email: </td>
			<td><input type=\"text\" name=\"email\" value=\"".$row['email']."\"></td>
		</tr>
	</table>
	
	<input type=\"hidden\" name=\"username\" value=\"$user\">
	<input type=\"hidden\" name=\"serve\" value=\"change_d\">
	<input type=\"submit\" value=\"Make Changes\" onClick=\"return checkForm('change_details');\">
	</form>
	<br /><br />
	<b><u>Request User Access Level Change</u></b>
	<br /></br />
	Current Access Level is: <span style=\"color:green\">$access_title</span>
	<br />
	
	
	<form name=\"access_change\">
	<input type=\"hidden\" name=\"username\" value=\"$user\">
	New Requested Access Level: <select name=\"new_access\">
	<option value=\"1\">Volunteer</option>
	<option value=\"4\">Teacher/Administrator</option>
	<option value=\"7\">System Administrator</option>
	</select>
	<br />
	<input type=\"button\" value=\"Submit Request\" onClick=\"ajax('newaccess','access_change');\">
	<br />
	<div id=\"response\" style=\"color:red;\"></div>
	</form>
	<br />
	<a href=\"user_home.php\">Go Back To User Home Page</a>
	</div>";
	
} elseif ($nature == 'change_d') {	// CHANGE USER DETAILS [PROCESS]

	$user = $_POST['username'];
	$firstname = $_POST['firstname'];
	$surname = $_POST['surname'];
	$email = $_POST['email'];
	
	$query = "UPDATE user_access SET firstname = '$firstname', surname = '$surname', email = '$email' WHERE username = '$user'";
	mysql_query($query);
	
	// LOGS ACTIVITY
	$message = "$user changed user details.";
	writeLog($user, "CHANGE_USER_INFO", "LOW", $message);
	
	$HTML_title = "AGAMA STUDENT REGISTRY: User Details Changed Successfully";
		
	echo $HTML_header_A.$HTML_title.$HTML_header_B;
	echo "<br /><br /><br />";
	echo "<div align=\"center\">User details changed successfully!  
	<br />To return to User Home Page, <a href=\"user_home.php\">click here</a>.</div>$HTML_closer";
	
} elseif ($nature == 'change_p') {	// CHANGE USER PASSWORD [PROCESS]

	$user = $_POST['user'];
	$oldpwd = $_POST['old_password'];
	$newpwd = $_POST['password'];
	$md5_old = md5($oldpwd);
	$md5_new = md5($newpwd);

	
	// CHECKS THAT USERNAME AND OLD PASSWORD MATCH
	$query = "SELECT email, firstname FROM user_access WHERE username = '$user' AND password = '$md5_old'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$found = mysql_num_rows($result);
	mysql_free_result($result);
	if (!$found) {
		
		// LOGS ACTIVITY
		$message = "$user failed to match usr/combo while attempting pwd change, with usr = $user, pwd = $oldpwd";
		writeLog($user, "CHANGE_USER_PWD", "HIGH", $message);
		
		$HTML_title = "ERROR: Change Password Failure";
		$HTML_body = "<br /><br /><br />The username and password do not match. <br />Please go <a href=\"javascript: history.go(-1)\">back</a> and try again.  <br />You can also contact the System Administrator at: <a style=\"text-decoration:none;font-family:verdana;font-size:10pt;color:green;\" href=\"mailto:webmaster@agamayoga.com\">webmaster AT agamayoga DOT com</a>.";
		
		die($HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body.$HTML_closer);
	}
	
	// CHECKS FOR PROPER PASSWORD COMPATIBILITY
	$regexp = "/^\w*(?=\w*\d)(?=\w*[a-zA-Z])\w*$/";
	if (!preg_match($regexp,$newpwd)) {	
	
		// LOGS ACTIVITY
		$message = "$user attempted to enter an invalid string for new pwd.";
		writeLog($user, "CHANGE_PASSWORD", "MEDIUM", $message);
		
		$HTML_title = "ERROR: Change Password Failure";
		$HTML_body = "<br /><br /><br />Passwords must contain at least one letter and one number. <br />Please go <a href=\"javascript: history.go(-1)\">back</a> and try again.  <br />You can also contact the System Administrator at: <a style=\"text-decoration:none;font-family:verdana;font-size:10pt;color:green;\" href=\"mailto:webmaster@agamayoga.com\">webmaster AT agamayoga DOT com</a>.";
		
		die($HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body.$HTML_closer);
	}

	// IF NO ERRORS, SETS NEW PASSWORD
	$query = "UPDATE user_access SET password = '$md5_new' WHERE username = '$user'";
	mysql_query($query);
	
	// LOGS ACTIVITY
	$message = "$user successfully changed pwd.";
	writeLog($user, "CHANGE_PASSWORD", "LOW", $message);
	
	$email = $row['email'];
	$firstname = $row['firstname'];
	$details = "You just changed passwords.  Your new password is $newpwd.";
	
	require("mailer.php");
	mailUser("update", $email, $firstname, $details);
	
	$HTML_title = "AGAMA STUDENT REGISTRY: Password Changed Successfully";
		
	echo $HTML_header_A.$HTML_title.$HTML_header_B;
	echo "<br /><br /><br />";
	echo "<br /><br />An email has been sent to your registered email address with the updated information.";
	echo "<div align=\"center\">Password changed successfully!  
	<br />To login to Agama Student Registry, <a href=\"login.php\">click here</a>.</div>$HTML_closer";

}
?>