function AjaxActivities() {}

AjaxActivities.createResponseHandler = function(customHandler) {
	if(customHandler==null)
		return function(response){};

	var realHandler = function (response) {
		if(response.responseCode == ResponseTable.OK) {
			customHandler(response);
		}

		if(response.responseCode == ResponseTable.NO_DATA) {
			customHandler(null);
		}
	};

	return realHandler;
};

AjaxActivities.searchFriend = function(pattern,customHandler){
	if(pattern==null || pattern.length==0) {
		customHandler(null);
		return;
	}

	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?pattern="+pattern,
								  true,// Is Asynchronous?
								  null,// Data to send
								  responseHandler);
};

AjaxActivities.markAsRead = function(id,customHandler) {
	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?read="+String(id),
								  true,// Is Asynchronous?
								  null,// Data to send
								  responseHandler);
};

AjaxActivities.findNewFriend = function(pattern, customHandler){
	if(pattern == null || pattern.length == 0) {
		customHandler(null);
		return;
	}

	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?patternx="+pattern,
								  true,
								  null,
								  responseHandler);
};

AjaxActivities.searchRequests = function(title_pattern,author_pattern,
										 language_pattern,customHandler) {
	if ((title_pattern==null || title_pattern.length==0) && 
	   	(author_pattern==null || author_pattern.length==0) &&
	   	(language_pattern==null || language_pattern.length==0)) {
		customHandler(null);
		return;
	}

	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
		"./ajax/requestReceiver.php?t="+title_pattern+"&a="+author_pattern+"&l="+language_pattern,
								  true,
								  null,
								  responseHandler);
};