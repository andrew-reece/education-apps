<?php

function writeLog($user, $action, $urgency, $info="foo", $id=0) {

	if (isset($user) && isset($action) && isset($urgency)) {
		if ($info != "foo") {
		
			$msg = $info;
			
			if ($id != 0) {
				$msg .= "to $id.";
			}
		} else {
			$msg = "$user performed $action";
		}
		
		
		$query = "INSERT INTO activity_log (timestamp, user, action, msg, urgency) VALUES
		(NOW(), '$user', '$action', '$msg', '$urgency')";
		mysql_query($query);
	}
}
?>