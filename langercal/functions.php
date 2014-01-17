<?php

/////
// Scrapes GCal for HTML output, for individual RAs in lab
////
function parseGoogleCalForHTML($inits, $studies) {
	
	//this is for checking against calendar data to exclude old timeslots
	$today_str = date("D M j, Y");
	$today = strtotime($today_str);
	
	for ($i = 0; $i < count($studies); $i++) {
    
	    $studyname = $studies[$i][0];
	    $studyurl = $studies[$i][1];
	    
		$whens .= "<u>".$studyname."</u><br /><br />";
	
		// read feed into SimpleXML object
		$sxml = simplexml_load_file($studyurl);
		    
		    	
		// iterate over entries in category
	    // print each entry's details
		
		$content = 0; //this is a marker that lets us fill in "N/A" if no slots for a study
		foreach ($sxml->entry as $entry) {
		      $title = stripslashes($entry->title);
		      $summary = stripslashes($entry->summary);
			    
			  // there's extra stuff we don't need in $summary
		      // this just trims it at the "&nbsp" part of the timestamp 		      
			$when_array = explode("&nbsp;", $summary);
		    $when = substr($when_array[0], 6);
			       
			$when_pieces = explode(" ", $when);
		    $thisdate_str = $when_pieces[1]." ".$when_pieces[2]." ".$when_pieces[3];
		    $thisdate = strtotime($thisdate_str);
			      
		    if($title == $inits && $thisdate >= $today) {
		    	$content++;
		      	$whens .= "<span style='color:purple'>".$when."</span><br /><br />";
		    }
		}
		if ($content == 0) {$whens .= "N/A<br /><br />";}
	}
	
return $whens;
}


/////
// Parse Google Cal for sending mail schedule of timeslots to RAs
/////
function parseGoogleCalForMail($inits, $studies) {
	
	//this is for checking against calendar data to exclude old timeslots
	$today_str = date("D M j, Y");
	$today = strtotime($today_str);
	
	for ($i = 0; $i < count($studies); $i++) {
    
	    $studyname = $studies[$i][0];
	    $studyurl = $studies[$i][1];
	    
		$whens .= $studyname."\n\n";
	
		// read feed into SimpleXML object
		$sxml = simplexml_load_file($studyurl);
		    
		    	
		// iterate over entries in category
	    // print each entry's details
		
		$content = 0; //this is a marker that lets us fill in "N/A" if no slots for a study

		foreach ($sxml->entry as $entry) {
		      $title = stripslashes($entry->title);
		      $summary = stripslashes($entry->summary);
			      
			  // there's extra stuff we don't need in $summary
		      // this just trims it at the "&nbsp" part of the timestamp 		      
			$when_array = explode("&nbsp;", $summary);
		    $when = substr($when_array[0], 6);
			      
			$when_pieces = explode(" ", $when);
		    $thisdate_str = $when_pieces[1]." ".$when_pieces[2]." ".$when_pieces[3];
		    $thisdate = strtotime($thisdate_str);
			      
		    if($title == $inits && $thisdate >= $today) {
		    	$content++;
		      	$whens .= $when."\n\n";
		    }
		}
		if ($content == 0) {$whens .= "N/A \n\n";}
	}
	
return $whens;
}


/////
// FUNCTION: GRABS USER INFO FROM DB
/////
function DBusers($usr) {

	$data = array();
	
	if ($usr == "ALL") {
		
		$query = "SELECT first_name, initials, email FROM userdata";
	
	} else {
		
		$query = "SELECT first_name, initials, email FROM userdata WHERE initials='".$usr."'";
	}
	
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_row($result)) {
			
		$data[] = $row;
	}
	
return $data;
}


/////
// FUNCTION: GRABS STUDY INFO FROM DB
/////
function DBstudies() {

	$data = array();

	$query = "
			SELECT title, url 
			FROM studies";
	
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_row($result)) {
			
		$data[] = $row;
	}	

return $data;
}

?>