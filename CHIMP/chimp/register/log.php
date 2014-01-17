<?php

require("ureg_HTML_headers.php");
require("cookie_check.php");
require("ureg_db_setup.php");
require("writelog.php");

// FIRST CHECK FOR PROPER ACCESS
if ($access < 7) {

	// LOGS ACTIVITY
	$message = "$user attempted to view Activity Log without proper Access Level.";
	writeLog($user, "ATTEMPTED_LOG_VIEW", "MEDIUM", $message);

	$HTML_title = "ERROR: User Access Denied";
	echo $HTML_header_A.$HTML_title.$HTML_header_B;	
	
	echo "<div align=\"center\">You are not authorized to view this page.  If you believe otherwise, contact the system administrator at <a style=\"text-decoration:none;font-family:verdana;font-size:10pt;color:green;\"  href=\"mailto:webmaster@agamayoga.com\">webmaster AT agamayoga DOT com</a>. 
	<br /><br />
	<a href=\"user_home.php\">Return to User Home</a>
	</div>$HTML_closer";
	
} else {	// OKAY TO SHOW LOG
	
	
	$query = "SELECT * FROM activity_log";
	$result = mysql_query($query);
	
	$HTML_title = "AGAMA STUDENT REGISTRY: Activity Log";
	echo $HTML_header_A.$HTML_title.$HTML_header_B;	
	echo "<table border=\"1\" width=\"100%\" style=\"text-align:center\">
	<tr style=\"background-color:orange\">
		<td>TIMESTAMP</td><td>USER</td><td>ACTION</td><td>MESSAGE</td><td>URGENCY</td>
	</tr>";
	
	while ($row = mysql_fetch_assoc($result)) {
		if ($row['urgency'] == 'HIGH') {
			$style = "style=\"background-color:red;\"";
		} elseif ($row['urgency'] == 'MEDIUM') {
			$style = "style=\"background-color:yellow;\"";
		} elseif ($row['urgency'] == 'LOW') {
			$style = "style=\"background-color:white;\"";
		}
		echo "<tr $style>";
		
		$counter = 0;
		foreach ($row as $datum) {
			if ($counter) {
				echo "<td>$datum</td>";
			}
			$counter++;
		}
		echo "</tr>";
	}
	
	echo "</table> <br /><br /><a href=\"user_home.php\">User Home Page</a> 
	<br /><a href=\"../admin_home.php\">Administrator Home Page</a>$HTML_closer";
}
?>