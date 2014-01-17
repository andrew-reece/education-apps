<?php

$javascript = "<script type=\"text/javascript\" src=\"ureg_ajax.js\"></script>";
require("ureg_HTML_headers.php");

$HTML_title = "AGAMA STUDENT REGISTRY: LOGIN";
echo $HTML_header_A.$HTML_title.$HTML_header_B;	
echo "<br /><br />";
echo "<div align=\"center\" style=\"font-weight:900;font-size:16pt;color:blue\">LOG IN TO AGAMA STUDENT REGISTRY</div>";
echo "<br /><br /><br /><br />";
?>

<div id="fullpage" align="center">
<form name="login" >
<table border="1">
<tr>
<td>Username: <input type="text" name="username"></td>
</tr>
<td>Password: <input type="password" name="password"></td>
</tr>
<tr>
<td>Remember me: <input type="checkbox" name="remember"></td>
</tr>
</table>
<br />
<input type="submit" name="sub" value="Login" onClick="ajax('login','login'); return false;">
<br />
<div id="status" style="color:red;"></div>
</form>
<br />
Forgot your password? <a href="forgot.php">Click here</a> for help.
<br />
Need to register? <a href="register_user.php">Sign up here</a>.
</div>
</body>
</html>