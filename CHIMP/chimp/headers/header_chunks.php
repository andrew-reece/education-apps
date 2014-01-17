<?php

// Sets standard HTML <head> tags plus closing HTML for Registry pages
// ** Includes external link to CSS file, change here if that file name changes
// ** define $javascript before including this file

	$HTML_header_A = "
	<html>
	<head>
	<title>";
	
	// paste $HTML_title in-between, once it's set

	$HTML_header_B = "</title>
	<link href=\"/chimp/css/chimp.css\" rel=\"stylesheet\" type=\"text/css\">
	<script type=\"text/javascript\" src=\"/chimp/js/alljs.js\"></script> </head>
	<body>";
	
	$HTML_navbar = "<table cols=\"4\" width=\"100%\">
	<tr style=\"background-color:#afc9e6; text-align:center;\">
	<td>
		<a href=\"javascript: history.go(-1)\">Go Back To Previous Page</a>
	</td>
	<td>
		<a href=\"search_registry.php\">Search For A Specific Student</a>
	</td>
	<td>     
		<a href=\"search_registry_bygroup.php\">Search By Category Or Group</a>
	</td>
	<td>
		<a href=\"admin_home.php\">Go To Main Admin Page</a>
	</td>
	</tr>
	</table>";
	
	$HTML_closer = "</body></html>";
?>