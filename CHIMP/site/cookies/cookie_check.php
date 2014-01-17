<?php

// checks if username and passwords cookies are set for this session
$cookies_set = ((isset($_COOKIE['chimpusername']) && isset($_COOKIE['chimppassword']))) ? TRUE : FALSE;

// checks to see if we are on the home page -- if so and cookies are set, we need to display the available studies 
$homepage = ($_SERVER['PHP_SELF'] == '/chimp/index.php') ? TRUE : FALSE;
$forgotpwd = ($_SERVER['PHP_SELF'] == '/chimp/register/forgot.php') ? TRUE : FALSE;
$info = ($_SERVER['PHP_SELF'] == '/chimp/studies/info.php') ? TRUE : FALSE;
$register = ($_SERVER['PHP_SELF'] == '/chimp/register.php') ? TRUE : FALSE;
$register_backend = ($_SERVER['PHP_SELF'] == '/chimp/register/register_backend.php') ? TRUE : FALSE;

$allowed = $homepage || $forgotpwd || $info || $register || $register_backend;

// CHECK FOR COOKIES

if (!($cookies_set) && !$allowed) {
	$HTML_title = "ERROR: UNIDENTIFIED USER";
	$HTML_body = "<br /><br /><br /><br /><div align=\"center\">Sorry, you must <a href=\"http://dev.technicalspiral.com/chimp/\">log in</a> to view this page.</div>";
	die($HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body.$HTML_closer);
} elseif ($cookies_set) {
	$user = $_COOKIE['chimpusername'];
}
?>