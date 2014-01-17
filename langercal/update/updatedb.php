<?php  
// update database script for langerlab.com calendar hack

require("db_setup.php");

$first = $_GET["first"];
$last = $_GET["last"];
$inits = $_GET["inits"];
$email = $_GET["email"];

$query = "INSERT INTO userdata (first_name, last_name, initials, email) 
		  VALUES('$first', '$last', '$inits', '$email')";
		  
mysql_query($query) 
or die(mysql_error());  

echo "All set - $first $last is now in the database.";
?>
