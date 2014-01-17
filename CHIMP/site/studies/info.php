<?php
	require("../headers/header.php");
	require("../db/db_setup.php");
	require("../lib.php");
	
	$id = $_GET['s'];
	$query = "SELECT info, name, nickname FROM study_index WHERE id = '$id'";
	$result = mysql_query($query);
	$data = mysql_fetch_assoc($result);
	
	$name = $data['name'];
	$info = $data['info'];
	$nickname = $data['nickname'];
?>
<div style="width:600px;padding:4px;border:2px solid maroon;">
<h3 style="position:inherit;width:100%;background-color:#ffffcc;color:maroon;"><?php echo $name ?> </h3>

<p><?php echo $info ?> </p>
</div>


<?php 
	//here we check to see if someone is already logged into the system
	//if so, check what studies they are registered for, 
	//and if this info page is called for an already-reg'd study, we don't allow signup again
	if ($cookies_set) { 
		$results = checkIfAlreadyRegistered($nickname, $user);

		$registered = $results[0]; 
		$uid = $results[1];
		
		$already_in_system = "<input type=\"hidden\" name=\"already_here\" value=\"$uid\">";
		$action = "register/register_backend.php";
	} else {
		$action = "register.php";
	}
	
	if ($registered > 0) {
		echo "<p><b>You are already registered for this study!</b></p>";
	} elseif ($registered < 0) {
		echo "<p><b>Sorry, you are not permitted to register for this study.  <br />
		This is most likely because you were registered at one point, and failed to complete the requirements.  <br />
		If you believe you are receiving this messge in error, please contact us at: chimpresearch AT gmail DOT com.  Thank you.</b></p>";
	} else { 
		echo "<p><b>Interested?  Click below to sign up and participate!</b></p>
		<form method=\"POST\" action=\"/chimp/$action\">
		<input type=\"hidden\" name=\"study\" value=\"$id\">
		$already_in_system
		<input type=\"submit\" value=\"Sign me up!\">
		</form>";
	} 
?>
</body>
</html>