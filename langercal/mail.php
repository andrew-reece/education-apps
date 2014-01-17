<?php
require("db_setup.php");
require("functions.php");

$username = $_GET["who"];
$userinfo = DBusers($username);

$studies = DBstudies();

if ($username == "ALL") {
	
	foreach ($userinfo as $thisusr) {
		$first_name = $thisusr[0];
		$inits = $thisusr[1];
		$email = $thisusr[2];
		
		$info = parseGoogleCalForHTML($inits, $studies);
		$mailinfo = parseGoogleCalForMail($inits, $studies);
		mail($email, "Langer Lab Notice: ".$first_name."'s timeslots", $mailinfo);
	}
	
	$returns = 'all RAs in database.'.'$$$'.'This feature is only available for individual RAs.
		<br /><br />[For full study listings, check our shared Google Calendar.]';
	
} else {
	
	$first_name = $userinfo[0][0];
	$inits = $userinfo[0][1];
	$email = $userinfo[0][2];
	
	$info = parseGoogleCalForHTML($inits, $studies);
	$mailinfo = parseGoogleCalForMail($inits, $studies);
	mail($email, "Langer Lab Notice: ".$first_name."'s timeslots", $mailinfo);
	
	
	$returns = $email."$$$".$info;
}


echo $returns; 

?>