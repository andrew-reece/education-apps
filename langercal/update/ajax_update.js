//this file is for the database update page for Langer Lab

function ajax() {
	
	var ajaxRequest;  // The variable that makes Ajax possible!
	var first = document.getElementById('first').value;
	var last = document.getElementById('last').value;
	var inits = document.getElementById('inits').value;
	var email = document.getElementById('email').value;
	
		
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
			document.getElementById('updateconfirm').innerHTML = returned;
			
			document.getElementById('first').value = "";
			document.getElementById('last').value = "";
			document.getElementById('inits').value = "";
			document.getElementById('email').value = "";
		}
	}

	var queryString = "?first=" + first +"&last=" + last+"&inits=" + inits+"&email=" + email;

	ajaxRequest.open("GET", "updatedb.php" + queryString, true);
	ajaxRequest.send(null); 
}