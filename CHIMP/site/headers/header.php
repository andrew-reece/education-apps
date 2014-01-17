<?php


// slices up filepath to get current directory only into $path string
function GetFileDir($php_self){
$filename = explode("/", $php_self); // THIS WILL BREAK DOWN THE PATH INTO AN ARRAY
for( $i = 0; $i < (count($filename) - 1); ++$i ) {
$filename2 .= $filename[$i].'/';
}
return $filename2;
}

$path = GetFileDir($_SERVER['PHP_SELF']);
//if we are one directory deeper than the main dir, this var adds on a 'go to parent dir' command to the filepath for header files
$go_up_a_level = ($path != "/chimp/") ? "../" : "";

require($go_up_a_level."cookies/cookie_check.php");
$home = "http://dev.technicalspiral.com/chimp/";

?> 

<html>
<head>

	<title>Creativity, Human Intelligence, Mind & Personality (CHIMP) Institute</title>
	<script type="text/javascript" src="/chimp/js/alljs.js"></script>
	<link type="text/css" href="/chimp/css/chimp.css" rel="stylesheet">
</head>

<body>
<h2>Creativity, Human Intelligence, Mind & Personality (CHIMP) Institute</h2>
<hr>
<a href="/chimp/">Home</a> <div id="navbar-loggedin-text"><?php if ($cookies_set) { echo '| <a href="/chimp/register/options.php">User Options</a> | <a href="/chimp/register/logout.php">Logout</a>'; } ?></div>
<hr>