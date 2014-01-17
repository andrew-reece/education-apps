//
//// AJAX SCRIPT FOR USER REGISTRATION PAGES
//

function ajax(from, formname) {
	var here = document.forms[formname];
	
	if (from == 'register') {
		document.getElementById('ajax_result').innerHTML = "Checking username availability, please wait...";
		var username = here.username.value;
	} else if (from == 'login') {
		document.getElementById('status').innerHTML = "Logging in...";
		var username = here.username.value;
		var password = here.password.value;
		var remember = here.remember.checked;
	} else if (from == 'forgot') {
		document.getElementById('response').innerHTML = "Requesting...";
		var username = here.username.value;
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
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			if (from == 'register') {	// REGISTER PAGE
				var response = ajaxRequest.responseText.split("_");
				document.getElementById('ajax_result').innerHTML = response[1];	
				if (response[0] == '0') {
					here.username.value = '';
				}
				here.username.focus();
			} else if (from == 'login') {	// LOGIN PAGE
				var response = ajaxRequest.responseText.split("_");
				if (response[0] == '0') {
					document.getElementById('status').innerHTML = response[1];
				} else {
					document.getElementById('fullpage').innerHTML = response[1];
					location.href = "user_home.php";
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
		queryString = queryString + "&password=" + password + "&remember=" + remember;
	} else if (from == 'newaccess') {
		queryString = queryString + "&new_access=" + new_access;
	}
		//TEST
		//alert("query string is: "+queryString+"...yep");
	ajaxRequest.open("GET", "ureg_ajax.php" + queryString, true);
	ajaxRequest.send(null); 
}