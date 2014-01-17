<?php
// connects to and queries database for langer lab study/RA info

//connection
$host = "db.apps.langerlab.com";
$login = "reecestudies";
$pwd = "raforlanger123";
$db = "langercalendars";

mysql_connect($host, $login, $pwd) or die(mysql_error());
mysql_select_db($db) or die(mysql_error());

?>
