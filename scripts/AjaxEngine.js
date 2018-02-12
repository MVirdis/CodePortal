function AjaxEngine() {}

AjaxEngine.getAjaxObject = function() {
	var xmlHttp = null;
	try { 
		xmlHttp = new XMLHttpRequest(); 
	} catch (e) {
		try { 
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); //IE (recent versions)
		} catch (e) {
			try { 
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); //IE (older versions)
			} catch (e) {
				xmlHttp = null; 
			}
		}
	}
	return xmlHttp;
};

AjaxEngine.performAjaxRequest = function(method,url,isAsync,data,responseHandler) {
	var xmlHttp = AjaxEngine.getAjaxObject();
	if (xmlHttp === null){
		window.alert("Your browser does not support AJAX!");
		return;
	}

	console.log(url);

	xmlHttp.open(method, url, isAsync); 
	xmlHttp.onreadystatechange = function (){
		if (xmlHttp.readyState == 4){
			console.log(xmlHttp.responseText);

			var data = JSON.parse(xmlHttp.responseText);
			responseHandler(data);
		}
	}
	xmlHttp.send(data);
};