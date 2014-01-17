<?php

//SET DB ACCESS VARS
$host = "localhost";
$login = "dharmahound";
$pwd = "9088747814";
$db = "test_db";
$table = "feedback_firstmonth_teachers";

// CONNECT TO MYSQL AND SPECIFIC DATABASE
mysql_connect($host, $login, $pwd) or die(mysql_error());
//echo "Connected to MySQL<br />";
mysql_select_db($db) or die(mysql_error());
//echo "Connected to Database: $db<br />";

// GET A SPECIFIC RESULT FROM A TABLE
$result = mysql_query("SELECT * FROM $table") 
or die(mysql_error());  

// PRINTS OUT KEYS AND VALUES FROM QUERY
while($row = mysql_fetch_array($result)) {

$keys = array_keys($row) or die("Problem with Array Keys");


	for ($i=0; $i<count($keys); $i++) {
		
		$this_key = $keys[$i];
		
		if (!is_int($this_key)) {
		$output = $row[$this_key];
		echo $this_key." is ".$output." <br />";			
		}
	}
}
?>