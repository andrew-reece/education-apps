<?php

function passgen($len)
{
$pass = "";

if($len > 5 && $len < 17)
{
srand((double) microtime() * 1000000);
for($i=0;$i<12;$i++)
{
$pass .= chr(rand(0,255));
}
$pass = substr(base64_encode($pass), 0, $len);
}
return $pass;
}

echo passgen(8);
?>