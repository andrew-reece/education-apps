function revealLink(box, el) {
			if (box.checked) {
				document.getElementById(el).style.visibility = 'visible';
			}
		}
		
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