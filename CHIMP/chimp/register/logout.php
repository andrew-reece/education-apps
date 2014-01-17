<?php

//LOGS OUT, KILLS COOKIES

setcookie('chimpusername', '', time()-60*60*24*365, '/chimp', 'dev.technicalspiral.com');
setcookie('chimppassword', '', time()-60*60*24*365, '/chimp', 'dev.technicalspiral.com');
setcookie('chimpaccesslevel', '', time()-60*60*24*365, '/chimp', 'dev.technicalspiral.com');

header('Location: http://dev.technicalspiral.com/chimp/');
?>