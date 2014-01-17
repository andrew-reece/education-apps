<?php

//
// GETTING FORM DATA READY FOR VALIDATION	
// (NOT A FUNCTION)
//

	
	for ($i=0;$i<count($post_keys);$i++) {
	
		// for now, original register_student.php does not have form input names set to include DB table name, as do later pages, such as edit_record.php
		// we identify such later pages with the student_id field passed via POST; register_student does not have this value in the form
		// until this is changed, this removes the table name from the field name in order for the validator to parse data like it would with the register_student page
		if ($editor) {
			$exploded_keys = explode("*",$post_keys[$i]);
			$this_key = $exploded_keys[1];
		} else {
			$this_key = $post_keys[$i];	
		}
		$this_val = $post_vals[$i];
		
		// CHECKS PHONE NUMBERS FOR "-" BEFORE VALIDATING THEM AS DIGITS
		if (preg_match("/phone/",$this_key) && preg_match("/-/",$this_val)) {
			$this_val = str_replace("-","",$this_val);
		}
		
		// puts together birth date in YYYY-MM-DD format, then on completion with birth_day value, sets as $this_key for validation
		// $birth_date_array is for checkdate() function which wants separate parameters for month, day, and year
		if ($editor) {
			if ($this_key == "birth_date") {
				$birth_date_vals = explode("-",$this_val);
				$this_val = $birth_date_vals;
			}
		} else {
			if ($this_key == "birth_year") {
				$birth_date_array[0] = $this_val;
				$birth_date = $this_val."-";
			} elseif ($this_key == "birth_month") {
				$birth_date_array[1] = $this_val;
				// puts month into MM format if value is < 10
				$birth_date .= ($this_val < 10) ? "0".$this_val."-" : $this_val."-";
			} elseif ($this_key == "birth_day") {
				$birth_date_array[2] = $this_val;
				// puts  day into  DD format if value is < 10
				$birth_date .= ($this_val < 10) ? "0".$this_val."-" : $this_val."-";
				$birth_date_vals = array_values($birth_date_array);
				$this_val = $birth_date_vals;
				$this_key = "birth_date";
			}
			
		}
		// set vars which need validation as arrays, value1 = data, value2 = key name, value3 = target type for form validation (ie. $phone = array("$_POST["phone"],"phone"))
		$datum = array($this_val,$this_key,"");
		
		// determines target type for each _POST var
		switch($this_key) {
		
		case "firstname":
		case "surname":
		case "emergency_name":
		case "occupation":
		case "birth_city":
		case "birth_state":
			$datum[0] = stripslashes($datum[0]);
			$datum[2] = "name";
			break;
		case "emergency_phone_home_countrycode":
		case "emergency_phone_home_mainnumber":
		case "emergency_phone_work_countrycode":
		case "emergency_phone_work_mainnumber":
		case "emergency_phone_mobile_countrycode":
		case "emergency_phone_mobile_mainnumber":
		case "local_phone":
			$datum[2] = "digit";
			break;
		case "email":
		case "emergency_email":			
			$datum[2] = "email";
			break;
		case "passport":
		case "local_address_housenumber";	
			$datum[2] = "alphanumeric";
			break;
		case "medform_issues_details":
		case "emergency_address":
		case "local_address_ghname":
		case "comments":
			$datum[2] = "text";
			break;
		case "birth_date":
			$datum[2] = "date";
			break;
		default:
			$datum[2] = "non-validating";
			break;
		}

		// validates specific form input for $this_key
		$validation_return = validateFormInput($datum[0],$datum[1],$datum[2]);
		
		// if $validation_return is not empty, it means an error was detected.  $error incrememts, error message is printed
		if(!empty($validation_return)) {
		
			$HTML_title = "ERROR: DATA SUBMISSION ERROR DETECTED";
			$error++;	
			if ($error == 1) {

				$HTML_body = "<span class=\"bluebold\"> Errors in your submission have detected.  Please note the following: </span> <br /><br />";
				// HTML closer is added later
				echo $HTML_header_A.$HTML_title.$HTML_header_B.$HTML_navbar.$HTML_body;
			}
			echo $validation_return;
		} else {
			$HTML_title = "STUDENT DATA ENTERED SUCCESSFULLY";
		}
	}

// if validation finishes with no errors, DB entry proceeds, else die()
if (!$error == 0) {
	die("<br />Please go <a href=\"javascript: history.go(-1)\">back</a> and re-enter data correctly. <br /> <br /> $HTML_closer");
}
?>