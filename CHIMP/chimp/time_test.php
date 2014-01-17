<?php

require("db/db_setup.php");
require("lib.php");

// check to see why survey_id is not accurate when entered into each line of survey_data
// figure out how to get SURVEY nicknames, not study nicknames, (or just survey id) when grabbing timepoints

$id = 2;
$sv = 1;

$response = getTimeRemaining(2,1,1,'total');
echo "Time remaining is: ".$response;
?>