<?php 

$host = "localhost";
$login = "dharmahound";
$pwd = "9088747814";
$db = "test_db";
$drop_command = "DROP TABLE ";
$table = "sash_index";
$query = $drop_command.$table;

mysql_connect($host, $login, $pwd) or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db($db) or die(mysql_error());
echo "Connected to Database: $db<br />";


// Create a MySQL table in the selected database
mysql_query($query)
 or die(mysql_error());  

echo "Table $table Deleted!";

?>