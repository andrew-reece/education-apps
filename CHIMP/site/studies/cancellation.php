<?php
require("../db/db_setup.php");
require("../headers/header.php");
require("../lib.php");

//we use cookies to get user id (inside 'header.php')

$study = $_REQUEST['s'];
$study_name = getStudyFullname($study);

removeUserFromStudy($user, $study);

echo "<br />
You have been removed as a participant in the <b>$study_name</b> study. <br />
You may continue to participate in CHIMP studies, however you are not permitted to register again for the <b>$study_name</b> study. <br /><br />
Please <a href=\"/chimp/\">return to the CHIMP home page</a> to continue.
</body>
</html>";

?>