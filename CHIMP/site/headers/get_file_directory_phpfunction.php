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

?>