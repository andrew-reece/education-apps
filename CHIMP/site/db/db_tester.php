<?php 

require("db_setup.php");

$query = "SELECT month_id, month_name FROM month_index";

mysql_connect($host, $login, $pwd) or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db($db) or die(mysql_error());
echo "Connected to Database: $db<br />";


// Create a MySQL table in the selected database

	$result = mysql_query($query) or die(mysql_error());
	while($months = mysql_fetch_assoc($result)) {
		echo "month id: ".$months[month_id];
		echo "<br />";
		echo "month name: ".$months[month_name];
		echo "<br /><br />";
	};
	mysql_free_result($result);

echo "<br /><br />All finished.";
?>