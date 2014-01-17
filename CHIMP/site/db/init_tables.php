<?php 

$host = "localhost";
$login = "dharmahound";
$pwd = "9088747814";
$db = "test_db";
$table = "activity_log";
$insert_data = "id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
timestamp DATETIME,
user VARCHAR(12),
action VARCHAR(12),
msg VARCHAR(255)";

mysql_connect($host, $login, $pwd) or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db($db) or die(mysql_error());
echo "Connected to Database: $db<br />";


// Create a MySQL table in the selected database
mysql_query("CREATE TABLE $table($insert_data)")
 or die(mysql_error());  

echo "Table $table Created!";

?>