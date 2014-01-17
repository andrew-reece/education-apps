<?php

require("db_setup.php");

for ($i=1; $i<=100; $i++) {
	$query = "ALTER TABLE survey_data CHANGE q$i q$i SMALLINT(3)";
	mysql_query($query);
}
?>