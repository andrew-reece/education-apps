<?php
// CHECK FOR COOKIES
if (!(isset($_COOKIE['chimpusername']) && isset($_COOKIE['chimppassword']))) {

	$HTML_title = "ERROR: UNIDENTIFIED USER";
	$HTML_body = "<br /><br /><br /><br /><div align=\"center\">Sorry, you must <a href=\"http://dev.technicalspiral.com/chimp/\">log in</a> to view this page.</div>";
	die($HTML_header_A.$HTML_title.$HTML_header_B.$HTML_body.$HTML_closer);
} else {
	$user = $_COOKIE['chimpusername'];
	$access = $_COOKIE['chimpaccesslevel'];
}
?>