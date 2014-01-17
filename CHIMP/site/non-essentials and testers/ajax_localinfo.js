function ajaxModifier() {

	document.getElementById('quick_info').innerHTML = "Loading, please wait...";
	
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
			document.getElementById('quick_info').innerHTML = ajaxRequest.responseText;
		}
	}
	var fullname = document.admin_grab_localinfo.fullname.value;
	var split_names = fullname.split(" ");
	var firstname = split_names[0];
	var surname = split_names[1];
	var branch = document.admin_grab_localinfo.branch.value;
	var queryString = "?firstname=" + firstname + "&surname=" + surname + "&branch=" + branch;
	ajaxRequest.open("GET", "ajax.php" + queryString, true);
	ajaxRequest.send(null); 
}