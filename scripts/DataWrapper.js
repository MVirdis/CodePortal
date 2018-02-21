function DataWrapper() {}

// Funzione di utilita': prende un domobject ed elimina i figli
DataWrapper.clear = function(toClear) {
	while(toClear.firstChild) {
		toClear.removeChild(toClear.firstChild);
	}
};

// Prende in ingresso un JSON Object che contiene i nomi degli amici in Object.data
DataWrapper.EmailDestWrapper = function (Object) {
	var suggestion_container = document.getElementById('suggestion_container');

	// Cancello tutto l'elenco
	DataWrapper.clear(suggestion_container);

	if(Object==null) {
		suggestion_container.setAttribute('class','hidden');
		return;
	}

	var inputs = document.getElementsByTagName('input');

	// Aggiungo elementi figli al contenitore
	for(var i=0; i<Object.data.length; ++i) {
		var new_div = document.createElement('div');
		var content = document.createElement('span');
		new_div.appendChild(content);
		content.appendChild(document.createTextNode(Object.data[i]));

		var clickHndlr = function() {
			this[0].value = this[1];
			DataWrapper.clear(this[2]);
			this[2].setAttribute('class','hidden');
		};

		new_div.addEventListener('click', clickHndlr.bind([inputs[0],Object.data[i],
														   suggestion_container]));

		// Aggiungo un figlio al contenitore
		suggestion_container.appendChild(new_div);
	}

	var rect = inputs[0].getBoundingClientRect();

	suggestion_container.style.top = rect.bottom;
	suggestion_container.style.left = rect.left;

	// Rendo visibile il contenitore
	suggestion_container.setAttribute('class','');
};

// Funzione che prende un JSONObject e lo trasforma in lista di utenti
DataWrapper.UserList = function(Object) {
	var container = document.getElementById('result_container');
	DataWrapper.clear(container);

	if(Object==null || Object.data.length == 0)
		return;

	for (var i = 0; i < Object.data.length; i++) {
		var list_element = document.createElement('div');
		list_element.appendChild(document.createTextNode(Object.data[i]['Username']));
		list_element.style.display = "block";
		if(Number(Object.data[i]['Amministratore'])) {
			list_element.setAttribute('class', 'admin');
		}

		list_element.addEventListener('click', (function(id) {
			return function(){
				window.location = './profile.php?id='+id;
			};
		})(Object.data[i]['ID']));

		container.appendChild(list_element);
	}
};

// Mostra le richieste in arrivo dal server
// Object.data è una tabella di richieste che rispettano i parametri di ricerca
DataWrapper.MainRequestWrapper = function(Object) {
	var container = document.getElementById('results_container');
	DataWrapper.clear(container);

	if (Object==null)
		return;

	for (var i = 0; i < Object.data.length; i++) {
		var req_element = document.createElement('div');
		req_element.setAttribute('class', 'request_container');

		var title_el = document.createElement('div');
		title_el.setAttribute('class', 'title');
		title_el.appendChild(document.createTextNode(Object.data[i]['Titolo']));

		title_el.addEventListener('click', (function(id) {
			return function(){
				window.location = './view.php?id='+id;
			};
		})(Object.data[i]['ID']));

		var detail_el = document.createElement('div');

		detail_el.style.overflow = "hidden";
		detail_el.style.padding = "0 4px";

		var username_el = document.createElement('div');
		username_el.setAttribute('class','author');
		username_el.appendChild(document.createTextNode(Object.data[i]['Username']));

		var time_el = document.createElement('div');
		time_el.setAttribute('class','time');
		time_el.appendChild(document.createTextNode('Created at '+Object.data[i]['Istante']));

		var language_el = document.createElement('div');
		language_el.setAttribute('class', 'language');
		language_el.appendChild(document.createTextNode(Object.data[i]['Linguaggio']));

		var detail2_el = document.createElement('div');
		
		var replies = document.createElement('div');
		replies.appendChild(document.createTextNode('Number of Replies: '+Object.data[i]['NumRisposte']));

		detail2_el.appendChild(replies);

		detail_el.appendChild(username_el);
		detail_el.appendChild(time_el);
		detail_el.appendChild(language_el);

		req_element.appendChild(title_el);
		req_element.appendChild(detail_el);
		req_element.appendChild(detail2_el);

		container.appendChild(req_element);
	}
};