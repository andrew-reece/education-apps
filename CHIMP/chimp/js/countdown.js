function countdown(cont, study, startTime) {

	//timer start is either passed in, or set to "9:59" as default
	if (startTime == undefined) {
		startTime = "9:59";
	}
	
		if (cont == 1) {	//we have already begun countdown sequence
		
			var current;
			var elems = new Array();
			var mn;
			var sc;
			var strMN;
			var strSC;
			
			current = document.getElementById('breaktimer').innerHTML;
			elems = current.split(":");		//divide time into min and sec by splitting on ":"
			
			mn = parseInt(elems[0]);
			sc = parseInt(elems[1]);
			
			if ((sc == 0) && (elems[1].length == 2)) {
				actualSecond = elems[1][1];
				sc = parseInt(actualSecond);
			}
			//alert("mn is "+mn+" and sc is "+sc);
			
			if ((elems[0] == "00") && (elems[1] == "00")) {		//countdown completed, move to cancel user from this study
				document.getElementById('breaktimer').innerHTML = "";
				window.location = "http://dev.technicalspiral.com/chimp/studies/cancellation.php?s=" + study;
			} else if (sc == 0) {	//change from one minute to the next
				mn = mn - 1;
				strMN = "0" + mn;
				strSC = "59";
			} else {	//change from one second to the next
				sc = sc - 1;
				if (sc == 0) {
					strSC = "00";
				} else if (sc < 10) {
					strSC = "0"+sc;
				} else {
					strSC = sc;
				}
				strMN = elems[0];
			}
			
			document.getElementById('breaktimer').innerHTML = strMN + ":" + strSC;
			
		} else {	//we are just starting countdown sequence
		
			document.getElementById('breakannounce').innerHTML = "Time Remaining: ";
			document.getElementById('breaktimer').innerHTML = startTime;
			
		}
		
		setTimeout("countdown(1, "+study+")", 1000);

}

