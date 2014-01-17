<?php

function populateTable($host,$login,$pwd,$db,$table,$keys,$fields,$filename,$entry_type) {

mysql_connect($host, $login, $pwd) or die(mysql_error());
$response = "Connected to MySQL<br />";
mysql_select_db($db) or die(mysql_error());
$response = $response."Connected to Database: $db<br />In table name $table<br />";

switch($entry_type) {

case 'flat':
	for ($i=0;$i<count($fields);$i++) {

	//INSERT _POST DATA  INTO DATABASE
		mysql_query("INSERT INTO $table 
		($keys) VALUES ('$fields[$i]') ") 
		or die(mysql_error()); 
	}
break;

case 'array':

	for ($i=0;$i<count($fields);$i++) {
	
		$field_set = $fields[$i];
		$values = "";
		
		for ($j=0; $j<count($field_set); $j++) {
			// extracts values from multilevel $field array, puts into $values.  if last item, no "," is added to string
			if (($j+1) == count($field_set)) {
				$values = $values."'".$field_set[$j]."'";
			} else {
				$values = $values."'".$field_set[$j]."', ";
			}
		}
		echo $keys."<br />".$values;
		
		//INSERT _POST DATA  INTO DATABASE
		mysql_query("INSERT INTO $table 
		($keys) VALUES ($values) ") 
		or die(mysql_error()); 
	}	
break;

case 'external file':	// BUSTED!! FIX BEFORE USING!!
			

	// FOR POPULATING DB ROWS WITH ROWS FROM AN EXTERNAL FILE
	$handle = fopen($filename, "r");
	if ($handle) {
		while (($f = fgets($handle, 4096)) !== false) {
			$f = preg_replace("/'/","",$f);
			
			//INSERT _POST DATA  INTO DATABASE
			mysql_query("INSERT INTO $table 
			($keys) VALUES ('$f') ") 
			or die(mysql_error()); 
			
		}
		fclose($handle);
	} else {
		echo "something is wrong with the txt file!!";
	}
	
break;

default: 
	die("No field type given!!");
	break;
}

$response = $response."table $table populated successfully!";
return $response;
}


$this_host = "mysql.technicalspiral.com";
$this_login = "dharmahound";
$this_pwd = "9088747814";
$this_db = "creativechimp";
$this_table = "discipline_index";
$this_keys = "discipline_name";
$this_fields = "";
$fname = "../etc/disciplines_list.txt";
$this_entry_type = "external file";

echo populateTable($this_host,$this_login,$this_pwd,$this_db,$this_table,$this_keys,$this_fields,$fname,$this_entry_type);

/*
COURSE FIELDS

$this_fields = array(array(1,"Hatha","1"),array(2,"Hatha","2"),array(3,"Hatha","3"),array(4,"Hatha","4"),array(5,"Hatha","5"),array(6,"Hatha","6"),array(7,"Hatha","7"),array(8,"Hatha","8"),array(9,"Hatha","9"),array(10,"Hatha","10"),array(11,"Hatha","11"),array(12,"Hatha","12"),array(13,"Hatha","13"),array(14,"Hatha","14"),array(15,"Kundalini","15"),array(16,"Kundalini","16"),array(17,"Kundalini","17"),array(18,"Kundalini","18"),array(19,"Kundalini","19"),array(20,"Kundalini","20"),array(21,"Kundalini","21"),array(22,"Kundalini","22"),array(23,"Kundalini","23"),array(24,"Kundalini","24"),array(101,"Tapas","Muladhara"),array(102,"Tapas","Swadhisthana"),array(103,"Tapas","Manipura"),array(104,"Tapas","Anahata"),array(105,"Tapas","Vishuddha"),array(106,"Tapas","Ajna"),array(107,"Tapas","Sahasrara"),array(201,"Kashmir Shaivism","Study"),array(301,"Advanced","Study"),);
*/

?>