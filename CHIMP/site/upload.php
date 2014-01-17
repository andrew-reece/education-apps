<?php

////
//  	BEGIN  FILE UPLOADS WITH CSV FORMAT 
////
//		FIRST-TIME REGISTER ONLY!!! 
////

$target_path = "batch_uploads/";
$target_path .= basename( $_FILES['batch_register']['name']); 

//TEST
//echo "Target path is: ".$target_path."<br />";
	
// default <title>
$HTML_title = "ERROR: PROBLEM WITH FILE UPLOAD";

// dies if file upload fails
move_uploaded_file($_FILES['batch_register']['tmp_name'], $target_path) or die("$HTML_header_A $HTML_title $HTML_header_B There was an error uploading the file, please go <a href=\"javascript: history.go(-1)\">back</a> and try again.");
		
		
// see FUNCTIONS LIBRARY		
$student_id = getNewStudentID();

// opens newly saved file
$handle = fopen($target_path, "r") or die("Couldn't open the file");
//row counter
$counter = 0;
// duplicate entry counter
$dup_count = 0;
		
		while (($data = fgetcsv($handle,10000,",")) !== FALSE) {
		
			// this means it's the first row, which contains the table-field values, afterwards counter is incremented
			if (!$counter) {
			
				// either REGISTER or UPDATE
				$action = $data[0];
				// IF UPDATE, SET WHAT KIND OF UPDATE
				if ($action == "UPDATE") {
					$action_detail = $data[1];
					if ($action_detail == "ATTENDANCE") {
						// IF UPDATE ATTENDANCE, SET FOR WHICH MONTH
						$attend_detail = $data[2];
					}
				}				
				$counter++;
				
			} elseif ($counter == 2) { // THIS IS A ROW IN THE EXCEL TEMPLATE WHICH WE DON'T USE
			
				$counter++;
				continue;
				
			} elseif ($counter == 1) {
				
				//just a marker
				$multiple_tables = 0;
				
				// here we put a 2-level array together, with second level containing table [0] and field [1] values
				for($i=0;$i<count($data);$i++) {
					
					$table_and_field = explode("-",$data[$i]);
					$current_table = $table_and_field[0];
					$current_field = $table_and_field[1];					
					$tables_array[$i] = $current_table;
					
					// for checking for duplicate entry attempts later on
					if ($current_field == "firstname") {
						$fn_index = $i;
					} elseif ($current_field == "surname"){
						$sn_index = $i;
					}
					
					// keeps array of unique table names
					$not_unique = 0;
					if($i==0) {$unique_tables[0] = $current_table;}
					for($j=0; $j<count($unique_tables); $j++) {
						if ($current_table == $unique_tables[$j]) {
							$not_unique = 1;
							break;
						}
					}
					if (!$not_unique) {
						$unique_tables[] = $current_table;
					}
					
					// SETS FIELD STRINGS PER TABLE VIA ASSOC ARRAY
					// if current field is a member of the same table as before, add field to current running $field_string (or if $i is on 1st iteration)
					if ($current_table == $tables_array[$i-1]) {
					
						// keeps fields separated per table with $field_string assoc array (see $value_string similar below)
						$field_string[$current_table] .= $current_field.",";
						
					} else { // set $current_table to new table name, start up a new running $field_string
						
						// multiple tables marker 
						if ($i != 0) { $multiple_tables = 1; }
						
						// updates assoc array pointer
						$current_table = $tables_array[$i];
						
						$id_field = "student_id,";
						// begins new table field-set
						$field_string[$current_table] = $id_field.$current_field.",";
					}

				}
				$counter++;
			} elseif ($counter > 1) { // MEANS WE ARE NOW INTO "VALUES" ROWS OF CSV FILE
				
				// this marks whether the current iteration is a duplicate entry
				// used for a different purpose than $dup_count
				$this_dup = 0;
				
				// FIRST CHECK TO SEE IF THIS ROW'S STUDENT ALREADY IS REGISTERED (NOT FOR "UPDATE")
				if ($action == "REGISTER") {
				
				$dupcheck_query = "SELECT * FROM students_basic WHERE firstname = '$data[$fn_index]' AND surname = '$data[$sn_index]'";
				$dupcheck_result = mysql_query($dupcheck_query);
				}
				
				// IF ALREADY REG'D, MARK COUNTER AND ADD TO DUPLICATE NOTIFICATION STRING
				// THEN SKIP THE REST FOR THIS ROW
				if (mysql_num_rows($dupcheck_result) > 0) { 
					$dup_count++;
					$dup_string .= "$data[$fn_index] $data[$sn_index], ";
					$this_dup = 1;
				} else { // PROCEED WITH DATA PREP
					
					// sets $value_string in case of single-table QUERY
					if (!$multiple_tables) {
						$fvalues = "'$student_id',";
						foreach ($data as $x) {
							$fvalues .= "'".$x."',";
						}
						$trimmed_fvalues = substr($fvalues,0,-1);
						$value_string .= "(".$trimmed_fvalues."),";
						//TEST
						//echo $value_string."<br />";
					} else {
						// sets initial table value for upcoming loop
						$table = $tables_array[0];
						
						// SETS VALUE STRINGS PER TABLE VIA ASSOC ARRAY $value_string[$table]
						for ($i=0; $i<count($data); $i++) {
						
							// if current value is a member of the same table as before, add value to current running $value_string
							if ($tables_array[$i] == $tables_array[$i-1]) {
						
								$value_string[$table] .= "'".$data[$i]."',";
								$this_count = $counter;
							} else { // if not, update $table var and begin new array slot in $value_string

								$table = $tables_array[$i];
								
								$id_val = "'$student_id',";
								// formatting for multiple-row QUERY
								$value_string[$table] .= "($id_val";
								$value_string[$table] .= "'".$data[$i]."',";
							}
							//TEST
							//echo "current value string is: $value_string[$table] <br /><br />";
						}
					}
				}
				if ($multiple_tables) {
					// formatting for multiple-row QUERY with multiple tables
					foreach($unique_tables as $ut) {
						if ($counter > 1) {
							$value_string[$ut] = substr($value_string[$ut],0,-1);
							if (!$this_dup) {
								$value_string[$ut] .= "),";
							}
						}
					}
				}
				$counter++;
			}
			$student_id++;
		}
		fclose($handle);	
	
		// if only one table, sets $table for QUERY later
		if (!$multiple_tables) {
		
			$table = $current_table;
			$fields = substr($field_string[$table],0,-1);
			$values = substr($value_string,0,-1);
			// () around $values is taken care of above
			$query = "INSERT INTO $table ($fields) VALUES $values";
			$result = mysql_query($query);
			
			//TEST
			//echo "Query looks like: $query";
		} else {
		
			for($i=0; $i<count($unique_tables); $i++) {
				$table = $unique_tables[$i];
				$fields = substr($field_string[$table],0,-1);
				$values = substr($value_string[$table],0,-1);
				// () around $values are taken care of above
				$query = "INSERT INTO $table ($fields) VALUES $values";
				$result = mysql_query($query); //or die("something came up.  there was an incident.");
				
				mysql_free_result($result);
				//TEST
				//echo "Query looks like: $query <br /><br />";
			}
		}
	
	
	$HTML_title = "FILE UPLOAD SUCCESSFUL";
	echo $HTML_header_A.$HTML_title.$HTML_header_B.$HTML_navbar;
	
	// IF DUPLICATE ENTRY ATTEMPTS DETECTED
	if ($dup_count) {
		// trims last ", "
		$dup_string = substr($dup_string,0,-2);
		$dup_string_start = "There are student records in the file you're uploading which already exist in the Agama Registry.  They have been omitted from the submission, although the original file remains as you uploaded it.  The following students are already registered: ";
		echo $dup_string_start."<span class=\"redbold\">".$dup_string."</span><br /><br />";
	}			
	
	// here we only return basic info, first/last name, email
	// IF YOU CHANGE THESE NUMBER OF FIELDS, CHANGE "COLUMN COUNT" AROUND 10 LINES BELOW
	$fields = "student_id, firstname, surname, email";	
	$return_count = ($counter-3)-$dup_count;
	$display_query = "SELECT $fields FROM students_basic ORDER BY student_id DESC LIMIT $return_count";
	$result = mysql_query($display_query);
	
	// accounts for data fields plus edit and delete and view-full 
	// count is set lower than actual because HTML looks funny otherwise
	$column_count = 3;
	echo "<br /><br /> MOST RECENT RECORDS ADDED: <br /><hr>";
	if (!$return_count) { echo "No records have been added from this uploaded file."; }
	echo "<table border=\"1\" cols=\"".$column_count."\">";
	$row_count = 1;
			
	while ($row = mysql_fetch_array($result)) {
			
		$headings = array_keys($row);
		$values = array_values($row);
				
		// WRITES HTML FOR QUERY RESULTS
				
		if($row_count == 1) {
			echo "<tr class=\"header3\">";
			for ($i=0;$i<count($headings);$i++) {
				if(!is_int($headings[$i])) {
					// switches out DB name "country_id" for user-interface name "nationality"
					if ($headings[$i] == "country_id") { $headings[$i] = "nationality"; }
					echo "<td>".$headings[$i]."</td>";
				}
			}
			echo "<td colspan=\"2\">Update Records</td><td>Full Student Details</td>";
			echo "</tr><tr>";
		} else {
			echo "<tr>";
		}
				
			for ($i=0;$i<count($values);$i++) {
				if(!is_int($headings[$i])) {
					echo "<td class=\"results\">$values[$i]</td>";
				}
			}
			
			// sets student_id as checkbox value for Edit or Delete
			echo "<td class=\"results\"><a href=\"edit_record.php?student_id=".$values[0]."\">Edit</a></td>
						<td class=\"results\"><a href=\"update_records.php?del=".$values[0]."\">Delete</a></td>
						<td class=\"results\"><a href=\"view_record.php?student_id=".$values[0]."\">View</a></td>";
			echo "</tr>";
				
		$row_count++;
	}
			
	echo "</table>
			<br />
			<br />
			<div style=\"position:absolute; top:93%;\">$HTML_navbar</div>
			$HTML_closer";

			die();
////
// 	END BATCH FILE FIRST-TIME REGISTER SECTION
////

?>