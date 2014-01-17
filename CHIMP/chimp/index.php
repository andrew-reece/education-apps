<?php
	require("db/db_setup.php");
	require("headers/header.php");
	require("cookies/cookie_check.php");
	require("ajax/login.php");
	
?>

<div style="position:relative;width:1200px;">


<div style="float:left; margin-right:10px;">
<img src="images/chimp.jpg" >
</div>
<div style="position:relative;float:left;width:400px;">
<b>Welcome to the CHIMP Home Page.</b>
<br /><br />
The CHIMP team is interested in researching creativity. What exactly is creativity, and how can we enhance it?  This is an important and relevant topic in today's quickly-growing world.  Creativity lies at the very heart of innovation, and is the starting point of much of our technological progress. 
<br /><br />
While it's a fascinating topic, creativity is difficult to investigate empirically.  That's what we're trying to change here, with accessible online research projects that everyone can participate in!  Our team is making an attempt to investigate creativity in everyone and enhance the skills of people around the world, to help people becoming more creative, mindful, and healthy. 
<br /><br />
In short, we believe that every individual has an enormous creative potential within them, and we hope to discover more about it through our work.
<br /><br />

</div>

<div id="registration-column">
<b>Sound like something you'd like to be a part of?</b>
<br />
For more information about specific studies we're currently running, or to register for a study, have a look at the links listed below.  
<br />
&nbsp;&nbsp;&nbsp;1. <a href="studies/info.php?s=1">Unifying Characteristics of Creativity</a> <br /> 
&nbsp;&nbsp;&nbsp;2. <a href="studies/info.php?s=2">Creativity Test!</a> <br />
&nbsp;&nbsp;&nbsp;3. <a href="studies/info.php?s=3">Multidimensional Creativity Study</a>
<br />
<hr>
	<div id="login-status">
		<?php 
		if(!$cookies_set) { 
		
			echo "<b>If you have already registered, please log in to continue.</b>";
			echo "<form id=\"login\" name=\"login\">
			<b>User Name:</b> <input name=\"username\" type=\"text\" size=\"12\">
			<br />
			<b>Password:</b> <input type=\"text\" name=\"password\" size=\"10\">
			<br />
			<input type=\"submit\" value=\"Log Into CHIMP\" onClick=\"ajax('login','login'); return false;\">
			</form>
			<div id=\"current-text\"></div>
			Forgot your password? <a href=\"register/forgot.php\">Click here for assistance.</a>
			</div>
			<div id=\"current-text\"></div>"; 
		} else {
			echo "Welcome, <b>$user</b>.<br />Please select from your list of current studies to continue.<br />
				</div><div id=\"current-text\">";
			login("index", $user,'','',$go_up_a_level);
			echo "</div>";
		}
		?>
		
</div>

</div>
</body>
</html>