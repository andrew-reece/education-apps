//########################################
//		CHIMP JAVASCRIPT FUNCTIONS		//
//		(called by header.php)			//
//########################################

////////////////////
//// AJAX SCRIPT FOR USER REGISTRATION PAGES
////////////////////
function ajax(from, formname, user, backup) {

	if (!backup) { backup = '';}
	
	if (document.forms[formname]) {
		var here = document.forms[formname];
	} else {
		var noform = true;
	}
	
	if (from == 'register') {
		document.getElementById('ajax-result').innerHTML = "Checking availability, please wait...";
		var username = here.uid.value;
	} else if (from == 'login') {
		document.getElementById('current-text').innerHTML = "Logging in...";
		var username = here.username.value;
		var password = here.password.value;
	} else if (from == 'forgot') {
		// since on the forgot script we pass either email or pwd through the formname parameter, this is how we sort it out here
		// also, we can assign var "user" based on where the request is coming from (either by form or by link)
		var forgotwhich = (noform) ? formname : 'password';
		var username = (noform) ? user : here.username.value;
		document.getElementById('response').innerHTML = "Requesting...";
	} else if (from == 'newaccess') {
		document.getElementById('response').innerHTML = "Requesting...";
		var username = here.username.value;
		var new_access = here.new_access.value;
	} else {
		return false;
	}
	
	
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Sorry, there seems to be a problem processing this request.  Please try again.");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			if (from == 'register') {	// REGISTER PAGE
				var response = ajaxRequest.responseText.split("_");
				document.getElementById('ajax-result').innerHTML = response[1];	
				if (response[0] == '0') {
					here.username.value = '';
				}
				here.username.focus();
				
				
			} else if (from == 'login') {	// LOGIN PAGE
				var response = ajaxRequest.responseText.split("_");
				if (response[0] == '0') {
					document.getElementById('login-status').innerHTML = response[1];
				} else {
					document.getElementById('login-status').innerHTML = response[1];
					document.getElementById('current-text').innerHTML = response[2];
					document.getElementById('navbar-loggedin-text').innerHTML = '| <a href="/chimp/register/options.php">User Options</a> | <a href="/chimp/register/logout.php">Logout</a>';
				}
				
				
			} else if (from == 'forgot') {
				document.getElementById('response').innerHTML = ajaxRequest.responseText;
			} else if (from == 'newaccess') {
				document.getElementById('response').innerHTML = ajaxRequest.responseText;
			}
		}
	}
	var queryString = "?username=" + username + "&from=" + from;
	if (from == 'login') {
		queryString = queryString + "&password=" + password;
	} else if (from == 'newaccess') {
		queryString = queryString + "&new_access=" + new_access;
	} else if (from == 'forgot') {
		queryString = queryString + "&which=" +forgotwhich;
	}
		//TEST
		//alert("query string is: "+queryString+"...yep");
	ajaxRequest.open("GET", backup + "ajax/login.php" + queryString, true);
	ajaxRequest.send(null); 
}




////////////////////
// CHECKS THAT BASIC FORM INPUT CHARACTER-LENGTH REQUIREMENTS ARE SATISFIED
////////////////////
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

 

 

////////////////////
// USED IN CHANGE PASSWORD FUNCTION (User Options section of CHIMP menu bar) 
////////////////////
function checkPassword(frm) {
	if (document.forms[frm].password.value != document.forms[frm].confirm_password.value) {
		alert("The password you entered does not match the 'Confirm Password' field, please re-enter");
		return false;
	} else {
		return true;
	}
}





////////////////////
// CHECKS TO SEE IF THERE ARE MISSING SURVEY ELEMENTS
////////////////////
function checkSurvey(survey, total, studyid) {
	
	//total count of form rows
	var count = 0;
	//will hold our number of missing rows
	var missing;
	//keeps track of the number of rows that have been checked
	var checkCount = 0;
	//tracks the actual row numbers (ex. q41, q42, but without the 'q'), 
	//in order to identify when we are still in the same row, going through multiple radio buttons
	var rowArray = new Array();
	//tracks the actual row numbers that have answers (checked radio buttons)
	var checkString = "";
	
	//here we go through all of the survey elements and check the status of each one
	for (var i=0; i < survey.elements.length; i++) {
		var found = false;
		var already_tagged = false;
		var check = survey.elements[i].checked;
		var name = survey.elements[i].name;
		var type = survey.elements[i].type;
		var thisrow = name.slice(1);
		
		for (row in rowArray) {
			if (thisrow == row) { 
				found = true; 
			}
		}
		
		if ((type == 'radio') && !(found) && (check == false)) {
			//alert("possible is: "+possible+" and i is: "+i+" and row count is: "+rowArray.length+" checkedArray is: "+checkedArray.length);
			rowArray.push(thisrow);
			count++;
		} else if ((type == 'radio') && (found) && (check == true)) {
			checkCount++;
			checkString = checkString + thisrow + ",";
		} else if ((type == 'radio') && !(found) && (check == true)) {
			count++;
			checkCount++;
			rowArray.push(thisrow);
			checkString = checkString + thisrow + ",";
		}
	}

	checkStringArray = checkString.split(",");

	missing = rowArray.length - checkCount;
	
	//first check to see if we've been here before...if so, then check whether user submits an empty form or not
	//if not entirely empty, on second pass just submit the form as-is
	//if entirely empty, cancel user from study
	if (document.getElementById('secondpass').value == 1) {
		if (checkCount == 0) {
			location.href = "cancellation.php?s="+studyid;
		} else {
			return true;
		}
	}
	
	if (missing > 0) {
		var msg;
		
		if (missing < rowArray.length) {
			
			for (var j = 0; j < rowArray.length; j++) {
				var highlight = true;
				for (k = 0; k < checkStringArray.length; k++) {
					if (j == checkStringArray[k]) {
						highlight = false;
					}
				}
				if (highlight) {
					document.getElementById('surveytable').rows[j].className = 'qrow-highlighted';					
				}
			}
			
			msg = "We noticed that you did not complete the entire survey.  If you would like to review your answers before finally submitting the data, you may do so now -- missed elements have been highlighted for your convenience.  Please remember that, while encouraged, it is not necessary to fill in all survey elements to continue with this study.  (It is, however, a requirement that you answer at least one question in each survey in order to continue on as a study participant.)  This notice is simply a second chance to complete the survey, in the event that you missed some parts accidentally.  Once you have finished reviewing your answers, click the submit button again, and this time your responses will be officially recorded.  Thank you. <br />";
		} else {
			msg = "We noticed that you did not fill in any responses in this survey.  Please remember that if you wish to continue as a participant in this study, you must provide at least one response per survey.  If you wish to continue, please review the survey again and answer as many questions as you can.  Once finished, click submit to enter your data and continue.  If you submit this survey without answering any questions for a second time, we will take that as an indication that you do not wish to continue with this study, and you will be removed as a participant.  Thank you. <br />";
		}
		
		document.getElementById('secondpass').value = 1;
		document.getElementById('msg').innerHTML = msg;
		window.location.href = "#surveytop";
		return false;
	} else {
		return true;
	}
	
}






// FORM CHECKER ON forgot.php 
function forgotPasswordCheckForm() {
	if (document.forgot.username.value == '') {
		alert('Please enter a username');
		return false;
	}
	ajax('forgot','forgot','','../');
}





// HIDE/SHOW FUNCTIONS FOR REGISTRATION PROCESS
		
function revealEdInfo(select) {
	var index = select.selectedIndex;
	
	if ((select.options[index].value != "") && (select.options[index].value != "neither")) {
	
		document.getElementById("professional_info").style.visibility = 'hidden';
		document.getElementById("professional_info").style.display = 'none';
		
		if (select.options[index].value == "student") {
			document.getElementById("degree_text").innerHTML = "What degree are you currently working toward?";
			document.getElementById("major_text").innerHTML = "What is your major/concentration/primary discipline?";
		} else if (select.options[index].value == "educator") {
			document.getElementById("degree_text").innerHTML = "At what educational level do you currently teach?";
			document.getElementById("major_text").innerHTML = "What is your primary field or discipline of instruction?";
		}
	
		document.getElementById("edinfo").style.visibility = 'visible';
		document.getElementById("edinfo").style.display = 'block';
		
	} else if (select.options[index].value == "neither"){
		
		document.getElementById("edinfo").style.visibility = 'hidden';
		document.getElementById("edinfo").style.display = 'none';
		
		document.getElementById("professional_info").style.visibility = 'visible';
		document.getElementById("professional_info").style.display = 'block';
	} else {
	
		document.getElementById("edinfo").style.visibility = 'hidden';
		document.getElementById("edinfo").style.display = 'none';
		
		document.getElementById("professional_info").style.visibility = 'hidden';
		document.getElementById("professional_info").style.display = 'none';
	}
}





function revealLink(box, el) {
			if (box.checked) {
				document.getElementById(el).style.visibility = 'visible';
			}
		}