<?php

$username = $_POST['username'];
$pwd = $_POST['password'];

$query = "SELECT username FROM participants WHERE username = '$username' AND password = '$pwd'";
$result = mysql_query($query);

if($result) {
	header( 'Location: http://www.yoursite.com/new_page.html' ) ;
} else {

}

?>