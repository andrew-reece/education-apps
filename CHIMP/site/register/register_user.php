<?php	

$javascript = "<script type=\"text/javascript\" src=\"ureg_ajax.js\"></script>
<script type=\"text/javascript\" src=\"ureg_formcheck.js\"></script>";
require("ureg_HTML_headers.php");

$HTML_title = "AGAMA STUDENT REGISTRY: REGISTER NEW USER";
echo $HTML_header_A.$HTML_title.$HTML_header_B;	
?>

<div align="center">
<b>AGAMA YOGA STUDENT REGISTRY: Register User</b>
<hr>
<br /><br />
<form name="reg_user" method="POST" action="register.php">

<table cols="2" width="30%" style="font-weight:bold;">
<tr>
<td> First name: </td>
<td align="right"> <input type="text" name="firstname" size="30"> </td>
</tr>
<tr>
<td>Surname: </td>
<td align="right"><input type="text" name="surname" size="30"></td>
</tr>
<tr>
<td>Email: </td>
<td align="right"><input type="text" name="email" size="30"></td>
</tr>
</table>
<br />

Your Username may be a mix of letters and numbers, between 5 and 12 characters in length.
<br />
Your password MUST contain at least one letter and one number, between 8 and 15 characters in length.
<br /><br />

Username: <input type="text" name="username"> 
<input type="button" name="check_usrname" value="Check Username Availability" onClick="ajax('register', 'reg_user');">
<div id="ajax_result" style="color:red"></div>
<br />
Password: <input type="password" name="password">
Confirm password: <input type="password" name="confirm_password">
<br /><br />
<input type="submit" name="register" value="Register New User" onClick="return checkForm('reg_user');">
</form>
</div>
</body>
</html>