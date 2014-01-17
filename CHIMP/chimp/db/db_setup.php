<?php

//DB ACCESS BASICS
$host = "mysql.technicalspiral.com";
$login = "dharmahound";
$pwd = "9088747814";
$db = "creativechimp";
mysql_connect($host, $login, $pwd) or die(mysql_error());
mysql_select_db($db) or die(mysql_error());

?>
