<?php
// FORGOTTEN PASSWORD
require("../headers/header.php");
require("../headers/header_chunks.php");
require("../db/db_setup.php");

echo "<br /><br /><br /><br />";
?>
<div align="center">
<p style="font-weight:700;">If you've forgotten your password, enter your username below and click the "Send Helpful Email" button.  
<br />An email will be sent to your registered address with your new temporary password.
<br /><br />
If you've forgotten your username also, you will have to contact us at: chimpresearch AT gmail DOT com.  
<br />Thank you.</p>
<br />
<form name="forgot">
<input type="text" name="username">
<input type="button" name="ajax" value="Send Helpful Email" onClick="forgotPasswordCheckForm();">
</form>
<br />
<div id="response" style="color:red;"></div>
<br /><br />
</div>
</body>
</html>