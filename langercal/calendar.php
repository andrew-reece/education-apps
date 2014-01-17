<?php
require("db_setup.php");

$query = "SELECT first_name, last_name, initials FROM userdata";
$result = mysql_query($query);
	
while ($row = mysql_fetch_row($result)) {
			
	$userdata[] = $row;
}
?>
<html>
   	<head>
    <title>Langer Lab - Calendar Reminder App</title>
	  	<script type='text/javascript' src='ajax.js'></script>
  	</head>

<body align="center">
<br /><br /><br /><br />
  <h2>Welcome to the Langer Lab Calendar Reminder app! </h2>

  <div style="width:400px; margin-left:auto; margin-right:auto;">
  This is a simple program that lets you send an email to yourself, 
  with a list of all the upcoming timeslots you're signed up for. 
  <br /><br />
 	 All this is on GCal too - this page only sends email reminders.

 	<br /><br />
  <b>Instructions: </b>
  <br />
  		For an email reminder of the timeslots you're signed up for, 
  		choose your name from the dropdown box and click send. 
  </div>
  <br /><br />
  <div align="center">
  
	  <select id='who'>
	  <?php 
	  	foreach ($userdata as $user) {
	  		print "<option value='$user[2]'>$user[0] $user[1]</option>\n";
	  	}
	  ?>
	  	<option value="ALL">Send to All</option>	
	  </select>
  
		<input type='button' id='sendmail' value='Send' onclick='ajaxmailer();'> 
  		<br />
  		<div id='mailconfirm' style='color:red; display:inline'></div>
  </div>
  <br /><br />
  <div id='mytimeslots'></div>
  </body>
</html> 
