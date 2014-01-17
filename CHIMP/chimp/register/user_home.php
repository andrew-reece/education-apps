<?php

require("ureg_HTML_headers.php");
require("cookie_check.php");

// GO TO USER HOME PAGE
	$user = $_COOKIE['agyogausername'];
	$access = $_COOKIE['agyogaaccesslevel'];
	
	if ($access == 1) {
		$access_title = "Volunteer";
	} elseif ($access == 4) {
		$access_title = "Teacher/Administrator";
	} elseif ($access == 7) {
		$access_title = "Sysadmin";
	} else {
		$access_title = "Unknown";
	}
	$HTML_title = "AGAMA STUDENT REGISTRY: User Home Page";
	echo "$HTML_header_A $HTML_title $HTML_header_B <br /><br /><br />";
	echo "<div align=\"center\">";
	echo "<img src=\"../images/agama_logo_small.jpg\"></img>";
	echo "<br />Welcome, <span style=\"text-size:14pt;color:blue;\">$user</span>! This is your personal home page on the Agama Student Registry. Your access level is <span style=\"color:green\">$access_title</span>.";
	echo "<br /><br />";
	echo "<table border=\"1\" width=\"85%\" style=\"font-weight:bold\">
		<tr>
			<td>
				<a href=\"../admin_home.php\">Go To Student Registry</a>
			</td>
			<td>
				<a href=\"change.php?serve=pwd\">Change Password</a>
			</td>
			<td>
				<a href=\"change.php?serve=det\">Change User Details</a>
			</td>";
	if ($access == 7) {
		echo "<td>
				<a href=\"log.php\">View Activity Log</a>
			</td>";
	}
	echo "
			<td>
				<a href=\"help_manual.php\">Registry Help Manual</a>
			</td>
			<td>
				<a href=\"logout.php\">Logout</a>
			</td>
		</tr>
	</table>
	</div>
	$HTML_closer";
?>