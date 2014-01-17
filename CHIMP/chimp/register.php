<?php
	require("db/db_setup.php");
	require("headers/header.php");
?>


<h3>Registration - Step 1 of 3</h3>
<br />
<form name="register" action="register/register_backend.php" method="POST">
<input name="step" type="hidden" value="1">

<?php 
$study = $_POST['study'];
if (!$study) {
echo '
Please select the study you wish to participate in:
<input name="study" type="radio" value="1"> Pilot Complexity &nbsp;&nbsp;&nbsp;
<input name="study" type="radio" value="2"> Pilot Creativity&nbsp;&nbsp;&nbsp;
<input name="study" type="radio" value="3"> Mindful Creativity Study
<br /><br />';
} else {
	echo "<input type='hidden' name='study' value='".$study."'";
}
?>

<b>Please choose a username and password:</b>
<br /><br />
<b>Username:</b> <input name="uid" type="text" size="15" maxlength="12"> (max. 12 characters) 
<br />
<input type="button" value="Check Availability of Username" onclick="ajax('register','register');"> <div id="ajax-result"></div>
<br />
<b>Password:</b> <input name="pwd" type="text" size="15" maxlength="12"> (max. 12 characters)
<br />
<b>Confirm password:</b> <input name="pwd_confirm" type="text" size="15" maxlength="12">
<br /><br />
<b>Please enter a valid email address.</b>
<br />
Once you submit this information, a confirmation link will be sent to the address provided, which will take you to the next step in the registration process.
<br /><br />
<b>Email Address:</b> <input name="email" type="text" size="35">
<br />
<b>Confirm Email Address:</b> <input name="email_confirm" type="text" size="35">
<br /><br />
<input type="submit" value="Click to Proceed">
</form>
</body>
</html>