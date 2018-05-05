<?php
	if(!isset($_SESSION)) {
		session_start();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">
	<title>Code Portal</title>
	<!-- importo il font Open Sans -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<link rel="stylesheet" type="text/css" href="./../style/home.css" media="screen">

	<script src="./../scripts/HomePage.js"></script>
</head>
<body>
	<header class="header-top">
		<nav>
			<div id="logo">
				<h1><a href="./" target="_self"><span id="logo_code">&lt;/>Code</span><span id="logo_portal">Portal</span></a></h1>
			</div>
			<ul>
				<li><a class="blue-hover" href="./login.php" target="_self">Log In</a>
				<li><a class="blue-hover" href="./instructions.php" target="_self">More Instructions</a>
			</ul>
		</nav>
		<!-- Questo div serve ad annullare tutti gli effetti dei precedenti elementi flottanti -->
		<div class="clear"></div>
	</header>
	<div id="header_placeholder" style="margin-top: 30px"></div>

	<div style="display: block;">
		
	</div>

	<script type="text/javascript">
		// Effetto fissaggio menu navigazione
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
	</script>
</body>
</html>