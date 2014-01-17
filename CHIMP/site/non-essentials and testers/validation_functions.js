   
// form validation for required fields
// different validation for:
// text (is it empty)? 
// radio (has a choice been made?) 
// select box (is the option selected something other than default?)

function checkForm(values) {
   
	for(var i=0;i<values.length;i++) {
  
		// check for empty TEXT input
	    if(values[i].type == "text" && values[i].value == '') {
			alert('You cannot leave "' + values[i].name + '" field blank, it is a required field.  Please fill it in and re-submit.');
			values[i].focus();
			return false;
		// check for unselected SELECT input
	   } else if(values[i].type == "select-one" && values[i].selectedIndex < 1) {
			//this variable avoids confusion over HTML field "Nationality" having NAME value as "country_id"
			var replaced_name;
			if (values[i].name == "country_id") {
				var replaced_name = "nationality";
			} else {
				var replaced_name = values[i].name;
			}
			alert('You cannot leave "' + replaced_name + '" field unselected, it is a required field.  Please fill it in and re-submit.');
			values[i].focus();
			return false;
		// check for unselected pairs of radio buttons
		} else if (values[i].type == "radio" && values[i].checked == "0" && values[i+1].type == "radio" && values[i+1].checked == "0" && values[i].name == values[i+1].name) {
			alert('You cannot leave "' + values[i].name + '" field unchecked, it is a required field.  Please fill it in and re-submit.');
			values[i].focus();
			return false;
		}
	}
	
	return true;
 }