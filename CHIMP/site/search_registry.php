<?php 

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
Header("Pragma: no-cache");

$javascript = "<script type=\"text/javascript\">

// form validation for required fields
// text (is it empty)? 
// NOTE: This checkForm() is different enough from the one on 'register_student.php' so that we can't simply have them both refer to the same one in an external file
function checkForm() {
	var valueArray = new Array();
	var return_val = true;

	elements_length = document.search.elements.length;
	
	for(var i=0;i<elements_length;i++) {
		// check for empty TEXT input
	    if(document.search.elements[i].type == 'text' && document.search.elements[i].value == '' && document.search.elements[i].disabled == 0) {
			alert('You cannot leave \"' + document.search.elements[i].name + '\" field blank, it is a required field.  Please fill it in and re-submit.');
			document.search.elements[i].focus();
			return_val = false;
	   } 
	}
	
	
	return return_val;
 }
 
// GETS FORM INPUT INDEX FOR CHOSEN ELEMENT
function getElementIndex(x) {
	var j = 0;
	// gets current element index
    while (x != document.search.elements[j]) {
		j++;
	}
	
	return j;
}

// (DE-)/ACTIVATES SELECTED QUERY FIELDS
 function allowElement(element) {
	var bool;
	var i;
	
	i = getElementIndex(element);
	
	// checks for whether we should activate or de-activate query field
	(element.checked) ? bool = 0 : bool = 1

	// performs appropriate action on query fields;
	// in case of radio buttons, performs action on both (assuming only binary radio buttons for query fields
	if (document.search.elements[i+1].type == 'radio') {
		document.search.elements[i+1].disabled = bool;
		document.search.elements[i+2].disabled = bool;
	} else {
		document.search.elements[i+1].disabled = bool;
	}
 }

</script>";

require("functions_library.php");
require("HTML_headers.php");
require("registeruser/cookie_check.php");

$HTML_title = "SEARCH STUDENT REGISTRY";
echo $HTML_header_A.$HTML_title.$HTML_header_B.$HTML_navbar;	
?>

Fill in the fields you wish to search by, then click "Submit Query".
<br />
Check the boxes next to the fields you want to query to make them available for input.
<br />
Even if you have previously entered information into a search field, if its respective box is not checked (and the field itself appears "greyed-out", then that field will not be included in the search query.
<br />
<br />
* NOTE * Search will return full matches only.  For example, if you are looking for anyone with first name "Lori", and you search for First Name: "Lo", it will return zero matching results.  (Unless there is someone named "Lo" in the database.)  Partial-match search results may be included in a later version.
<br />
<br />
<form name="search" method="POST" action="search_registry_result.php">

<table cols="3" width="100%" border="1">

	<tr class="header2">
		<td class="head_left" colspan="3">
		SPECIFIC STUDENT SEARCH
		</td>
	</tr>	

	<tr class="detail">

		<td>
		<input type="checkbox" onClick="allowElement(this);" value="firstname">
		First Name:
		<input type="text" name="students_basic-firstname" maxlength="20" DISABLED>
		</td>

		<td>
		<input type="checkbox" onClick="allowElement(this);" value="surname">
		Last Name:
		<input type="text" name="students_basic-surname" maxlength="30" DISABLED>
		</td>

		<td>
		<input type="checkbox" onClick="allowElement(this);" value="email">
		Email:
		<input type="text" name="students_basic-email" size="30" maxlength="50" DISABLED>
		</td>

	</tr>

</table>
<br />
<div align="center">
	<input type="submit" value="Submit Query" onClick="return checkForm();">
</div>

</form>
</body>
</html>