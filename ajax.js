function AJAX(URL) {
	var xmlhttp;
	if (window.XMLHttpRequest) {	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}else{							// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("GET", URL + '&rnd=' + Math.random(), false);
	xmlhttp.send();

	return String(xmlhttp.responseText);
}