<!--
function load() {
	//Effetto di highlight del logo
	var titolo = document.getElementById("logo");
	var code = document.getElementById("logo_code");
	var portal = document.getElementById("logo_portal");

	titolo.onmouseover = function () {
		code.style.color="#707070";
		portal.style.color="#09f";
	}
	titolo.onmouseout = function () {
		code.style.color = "inherit";
		portal.style.color = "inherit";
	}

	//Effetto di fissaggio del menu di navigazione
	var body = document.body;
	var menu = document.getElementsByTagName("header")[0];
	var placeholder = document.getElementById("header_placeholder");
	body.onscroll = function () {
		if (window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop) {
			placeholder.style.display="none";
			menu.classList.remove("header-top");
		} else {
			placeholder.style.display="block";
			menu.classList.add("header-top");
		}
	}

	//Effetto di comparsa del form per la registrazione
	document.getElementById("signup_link").onclick = function () {
		document.getElementById("signup_form_container").style.display = "block";
		document.getElementById("links_container").style.display = "none";
	}

	//Indietro per uscire dalla form
	document.getElementById("back_link_signup").onclick = function () {
		document.getElementById("signup_form_container").style.display = "none";
		document.getElementById("links_container").style.display = "block";
	}

	// Controllo del form di login
	var bg_color_ok = "#F1F8E9";
	var bg_color_bad = "#FBE9E7";
	var border_color_ok = "#33691E";
	var border_color_bad = "#BF360C";

	var username_input = document.getElementById("username");
	username_input.addEventListener("change", event=>{
		if(/^\s*([A-Za-z_\.\-])+\s*$/.test(username_input.value)){
			username_input.style.border = "1px solid "+border_color_ok;
			username_input.style.backgroundColor = bg_color_ok;
		} else {
			username_input.style.border = "1px solid "+border_color_bad;
			username_input.style.backgroundColor = bg_color_bad;
		}
	});

	var first_name_input =document.getElementById("first_name")
	first_name_input.addEventListener("change", event=>{
		if(/^\s*([A-Za-z\u00C0-\u00FF]+\s*)+$/.test(first_name_input.value)) {
			first_name_input.style.border = "1px solid "+border_color_ok;
			first_name_input.style.backgroundColor = bg_color_ok;
		} else {
			first_name_input.style.border = "1px solid "+border_color_bad;
			first_name_input.style.backgroundColor = bg_color_bad;
		}
	});

	var last_name_input = document.getElementById("last_name");
	last_name_input.addEventListener("change", event=>{
		if(/^\s*([A-Za-z\u00C0-\u00FF]+\s*)+$/.test(last_name_input.value)) {
			last_name_input.style.border = "1px solid "+border_color_ok;
			last_name_input.style.backgroundColor = bg_color_ok;
		} else {
			last_name_input.style.border = "1px solid "+border_color_bad;
			last_name_input.style.backgroundColor = bg_color_bad;
		}
	});

	var email_input = document.getElementById("id_email");
	email_input.addEventListener("change", event=>{
		if(/^\w([\.-_]?[\w0-9])*@(\w[\.-_]?)*\w+$/.test(email_input.value)) {
			email_input.style.border = "1px solid "+border_color_ok;
			email_input.style.backgroundColor = bg_color_ok;
		} else {
			email_input.style.border = "1px solid "+border_color_bad;
			email_input.style.backgroundColor = bg_color_bad;
		}
	});

	var password_input = document.getElementById("id_password");
	var err_password = document.getElementById("id_password_err");
	password_input.addEventListener("change", function (event){
		event.preventDefault();
		if(/^[A-Za-z0-9]{8,}$/.test(password_input.value)) {
			password_input.style.border = "1px solid "+border_color_ok;
			password_input.style.backgroundColor = bg_color_ok;
			err_password.style.display = "none";
		} else {
			password_input.style.border = "1px solid "+border_color_bad;
			password_input.style.backgroundColor = bg_color_bad;
			err_password.style.display = "inline";
		}
	});

	var password_input_rep = document.getElementById("id_password_rep");
	password_input_rep.addEventListener("change", event=>{
		if(password_input_rep.value===password_input.value) {
			password_input_rep.style.border = "1px solid "+border_color_ok;
			password_input_rep.style.backgroundColor = bg_color_ok;
		} else {
			password_input_rep.style.border = "1px solid "+border_color_bad;
			password_input_rep.style.backgroundColor = bg_color_bad;
		}
	});
}
-->
