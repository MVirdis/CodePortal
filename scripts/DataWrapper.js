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
		list_element.appendChild(document.createTextNode(Object.data[i][1]));
		list_element.style.display = "block";

		list_element.addEventListener('click', (function(id) {
			return function(){
				window.location = './profile.php?id='+id;
			};
		})(Object.data[i][0]));

		container.appendChild(list_element);
	}
};