<?php

require("db_setup.php");

$sql = "CREATE TABLE survey_data
(id mediumint(15),
survey_id tinyint(2),
time timestamp,
average tinyint(3)";

for ($i=1; $i<=100; $i++) {
	$sql .= ", q$i tinyint(1)";
}

$sql .= ")";
mysql_query($sql) or die(mysql_error()."<br />Query: $sql");
echo "Table created! With query: $sql";
?>