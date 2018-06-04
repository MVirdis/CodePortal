function AjaxActivities() {}// New function obj

// Questa funzione restituisce un handler da uno custom
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

// Cerca tra gli amici quello il cui username combacia con
// Pattern; viene utilizzato nel campo destinatario delle email
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

// Manda un segnale per segnare una data email come letta
AjaxActivities.markAsRead = function(id,customHandler) {
	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?read="+String(id),
								  true,// Is Asynchronous?
								  null,// Data to send
								  responseHandler);
};

// Invia la richiesta AJAX per cercare un utente, prende un pattern
// che viene confrontato con gli username
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

// Questa funzione manda una richiesta ajax per trovare una richiesta
// Che combaci con i parametri passati
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

// Manda un segnale per mettere un like
AjaxActivities.likeCode = function(code_id, customHandler) {
	if (code_id==null) {
		customHandler(null);
		return;
	}

	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?likes="+code_id,
								  true,
								  null,
								  responseHandler);
};

// Manda un segnale per mettere non mi piace
AjaxActivities.dislikeCode = function(code_id, customHandler) {
	if (code_id==null) {
		customHandler(null);
		return;
	}

	var responseHandler = this.createResponseHandler(customHandler);

	AjaxEngine.performAjaxRequest("GET",
								  "./ajax/requestReceiver.php?dislikes="+code_id,
								  true,
								  null,
								  responseHandler);
};
