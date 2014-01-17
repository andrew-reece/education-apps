function ajaxmailer() {
	
	var ajaxRequest;  // The variable that makes Ajax possible!
	var whoselected = document.getElementById('who');
	var who = whoselected.options[whoselected.selectedIndex].value;

		
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
			var returned = ajaxRequest.responseText;
			var return_matrix = returned.split("$$$");
			document.getElementById('mailconfirm').innerHTML = "Mail was sent to " + return_matrix[0];
			document.getElementById('mytimeslots').innerHTML = "<b>These are the timeslots you're currently signed up for:</b><br /><br />" + return_matrix[1];
		}
	}

	var queryString = "?who=" + who;

	ajaxRequest.open("GET", "mail.php" + queryString, true);
	ajaxRequest.send(null); 
}