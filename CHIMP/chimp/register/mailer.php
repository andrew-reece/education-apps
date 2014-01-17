<?php
// SEND USER EMAIL FOR EITHER REGISTRATION OR CHANGE INFO

function mailUser($type, $address, $name, $meat) {

if ($type == 'first') {
	$subject = "Welcome to CHIMP!";
	$beginning = "Thanks for signing up with the CHIMP research website.  This is an email confirming your registration details. \n\n";
} elseif ($type == 'update') {
	$subject = "User Information Update from Agama Yoga Student Registry";
	$beginning = "Our records show that you have recently changed some details of your user profile.  Here are the changes we have recorded: \n\n";
} elseif ($type == 'forgot') {
	$subject = "CHIMP User Login Details";
	$beginning = "This email has been sent in response to a request for information related to the username registered with this email address.  If you're getting this, it probably means you either forgot your password or your username.  If you have not made such a request, please contact the CHIMP team at chimpresearch@gmail.com, and relay the contents of this message.  Thank you. \n\n";
} elseif ($type == 'request') {
	$subject = "Access Level Upgrade Request";
	$beginning = "This is an automated request sent through the Agama Yoga Student Registry.  You should be the AYSR Sysadmin.  If you are not, please contact Michael (michael@agamayoga.com) and let him know you have received this email in error. \n\n Request is as follows: \n";
	$name = "Sysadmin";
} else {
	die("I don't recognize the page that's calling me.");
}

$end = "\n\n\n Have a great day! \n\nSincerely, \nThe CHIMP Team\n chimpresearch@gmail.com";


$to = $address;
$message = "Dear $name, \n\n";
$message .= $beginning;
$message .= $meat;
$message .= $end;
$headers = 'From: chimpresearch@gmail.com' . "\r\n" .
    'Reply-To:chimpresearch@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
}
?>