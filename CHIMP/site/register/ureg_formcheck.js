 
 function checkPassword(frm) {
	if (document.forms[frm].password.value != document.forms[frm].confirm_password.value) {
		alert("The password you entered does not match the 'Confirm Password' field, please re-enter");
		return false;
	} else {
		return true;
	}
}

function checkForm(formname) {
	if (document.forms[formname].password) {
		var falsepass = checkPassword(formname);
		if (!falsepass) {
			return false;
		}
	}
	
	var thisform = window.document.forms[formname];
   
   for (i=0;i<thisform.elements.length;i++) {
   /*
   // add input fields here that need to be validated
   valueArray[0]    = window.document.reg_user.firstname;
   valueArray[1]   = window.document.reg_user.surname;
   valueArray[2] = window.document.reg_user.email;
   valueArray[3] = window.document.reg_user.username;
   valueArray[4] = window.document.reg_user.password;
   valueArray[5] = window.document.reg_user.confirm_password;
   */
		var now = thisform.elements[i];
		// check for empty input
	    if (now.type == 'text' && now.value == '') {
			alert('You cannot leave \"' + now.name + '\" field blank, it is a required field.  Please fill it in and re-submit.');
			now.focus();
			return false;
	   } 
	   
	   // check string length
	   var chars = now.value.length;
	   if (now.name == 'username' && (chars < 5 || chars > 12)) {
			alert('Username must be between 5 and 12 characters in length.');
			return false;
	   } else if (now.name == 'password' && (chars < 8 || chars > 15)){
			alert('Password must be between 8 and 15 characters in length.');
			return false;
	   }
	}
 }  
