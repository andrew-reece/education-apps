<?php
// FOR CHANGING PASSWORD OR USER DETAILS

require("../headers/header.php");

if ($_POST['changepass']) {

	require("../db/db_setup.php");
	require("registration_functions.php");
	
	$u = $_POST['user'];
	$op = $_POST['old_password'];
	$p = $_POST['password'];
	$md5o = md5($op);
	$md5n = md5($p);
	echo changePassword($u, $op, $p, $md5o, $md5n);
	
} else {

	echo "<br />
	<h3>Change Password</h3>
	
	To change your password, please type in first your old password, then your new desired password.  
	<br />Remember that passwords must be between 8 and 15 characters, and must contain at least one letter and one number.  
	<br />Passwords are case-sensitive.
	<br /><br />
	<form name=\"change\" method=\"POST\" action=\"options.php\">		
			
		Username: 	<input type=\"text\" name=\"user\">
		<br />
		Old password: <input type=\"text\" name=\"old_password\">
				<div id=\"verify\"></div>
		New password: <input type=\"text\" name=\"password\">
		<br />
		Confirm new password: <input type=\"text\" name=\"confirm_password\">
		<br />
		<input type=\"hidden\" name=\"changepass\" value=\"1\">
		<input type=\"submit\" id=\"submitter\" value=\"Change Password\" onClick=\"return checkForm('change');\">
	</form>";
}
?>
</body>
</html>