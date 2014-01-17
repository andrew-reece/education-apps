<?php
	
require("db/db_setup.php");


//
////		FUNCTIONS LISTED IN ALPHABETICAL ORDER
//



////////////////////////////////////////////////////////////////////////////////////
///				FUNCTION: populateSelectBox()						///
///////////////////////////////////////////////////////////////////////////////////

// determines which data is needed to populate <SELECT> tag
// used on register_student.php and search_registry.php et al.
function populateSelectBox($which) {

	// var to set whether function calls accessDB(), default TRUE
	$needs_db = TRUE;
	$this_special = 0;
	$month_array = array("January","February","March","April","May","June","July","August","September","October","November","December");
	
	switch($which) {
	case 'country':   
		$this_table = "country_index"; 
		$this_fields = "country_id,country_name";
		break; 
	case 'birth year': 
		$html = "<option value=\"\">* Select Year *</option> \r\n";
		$currentyear = date("Y");
		for ($i=1900;$i<=$currentyear;$i++) {
			$html = $html."<option value=\"$i\">$i</option> \r\n";
		}
		$needs_db = FALSE;
		break;
	case 'month':
		$html = "<option value=\"\">* Select Month *</option> \r\n";

		for ($i=0;$i<12;$i++) {
			$month_num = $i+1;		
			$html = $html."<option value=\"$month_num\">$month_array[$i]</option> \r\n";
		}
		$needs_db = FALSE;	
		break;
	case 'birth day':
		$html = "<option value=\"\">* Select Day *</option> \r\n";
		for ($i=1;$i<=31;$i++) {
			$html = $html."<option value=\"$i\">$i</option> \r\n";
		}
		$needs_db = FALSE;
		break;
	case 'birth hour':
		$html = "<option value=\"\">* Select Hour *</option> \r\n";
		for ($i=0;$i<=23;$i++) {
			if ($i==0) {
				$html = $html."<option value=\"00\">00</option> \r\n";
			} elseif ($i<10) {
				$html = $html."<option value=\"0$i\">0$i</option> \r\n";
				
			} else {
				$html = $html."<option value=\"$i\">$i</option> \r\n";
			}
		}
		$needs_db = FALSE;
		break;
	case 'birth minute':
		$html = "<option value=\"\">* Select Minute *</option> \r\n";
		for ($i=0;$i<=59;$i++) {
			if ($i==0) {
				$html = $html."<option value=\"00\">00</option> \r\n";
			} elseif ($i<10) {
				$html = $html."<option value=\"0$i\">0$i</option> \r\n";
				
			} else {
				$html = $html."<option value=\"$i\">$i</option> \r\n";
			}
		}
		$needs_db = FALSE;
		break;

	default: 
		$needs_db = FALSE;
		die("no data input for populateSelectBox function"); 
		break;
	}

	if ($needs_db) {
	
		$this_query = "SELECT $this_fields FROM $this_table $order_by";

		return accessDB($this_host, $this_login, $this_pwd, $this_db, $this_query, $this_special);
	} else {
		return $html;
	}

}
	
	

////////////////////////////////////////////////////////////////////////////////////
///				FUNCTION: validateFormInput()						///
///////////////////////////////////////////////////////////////////////////////////
	
//
// VALIDATES FORM INPUT ACCORDING TO TYPE
// goes with student_lookup.php, update_records.php and search_registry_result.php
//
	function validateFormInput($data,$key,$target) {	
		
		switch($target) {
		
		case "alpha": 	// a-z A-Z
		
			if (!empty($data) && !ctype_alpha($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters only. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}	
			break;
		
		
		case "name": 	// a-z A-Z plus space and not-first-character apostrophe
			$regexp = "/^[a-zA-Z\s]+\'?$/";
			
			if (!empty($data) && !preg_match($regexp, trim($data))) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters, and blank spaces or apostrophes ('). You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}	
			break;
		
		case "alphanumeric": 	// a-z A-Z 0-9
		
			if (!empty($data) && !ctype_alnum($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of letters and/or numbers. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;

		case 'digit': 	//  0-9
		
			if (!empty($data) && !ctype_digit($data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be composed of numbers only. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;

		case "text": 	// alphanumeric plus symbols , ' " . ! etc etc
			$regexp = "/^[^@\$\{\}\;\+\*=]+$/";
		
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span>is not valid. It cannot contain characters \"@\", \"\$\", \"(\", \")\", \";\", \"+\", or \"=\". You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
			
		case "email":
			$regexp = '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';

			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not a valid email address. You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
		
		// USED FOR REGISTER USERNAME/PASSWORD
		case "password":
			$regexp = "/^\w*(?=\w*\d)(?=\w*[a-zA-Z])\w*$/";
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not a valid password. Passwords must contain at least one letter and one number.  You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value.  Please go back and re-enter.<br /><br />";
			}
			break;
			
		case "phone":	// allows for straight digits or in +(123) 9871234987 format

			$regexp = "/^\+?\(?[0-9]{1,3}\)?[0-9]{8,15}$/";
			
			if (!empty($data) && !preg_match($regexp, $data)) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. It should be in the format +(123)1234-5678, or in an unbroken string of numbers (total digits may vary). You have entered <span class=\"error_detail\">\"". $data ."\"</span> for this value. Please go back and re-enter. <br /><br />";
			}		
			break;
		case "date":
			if (!empty($data) && !checkdate($data[1],$data[2],$data[0])) {
			    $error_msg = "The field marked <span class=\"error_detail\">\"".$key."\"</span> is not valid. You have entered <span class=\"error_detail\">\" $data[0]-$data[1]-$data[2] \"</span> for this value, which represents YYYY-MM-DD. Make sure you have selected a valid day for the appropriate month and/or year. Please go back and re-enter.<br /><br />";
			}
			break;
		case "non-validating":
			$error_msg = "";
			break;
		default: 
			$error_msg = "for $key there is nothing to validate!! <br /><br />";
			break;
		}
	
		if(!empty($error_msg)) { 
			$return_msg = $error_msg;
		} else {
			$return_msg = "";
		}
		
		return $return_msg;
	}	


	
?>