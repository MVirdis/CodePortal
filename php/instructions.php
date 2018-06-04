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
	<link rel="stylesheet" type="text/css" href="./../style/instructions.css" media="screen">

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

	<section>
		<h3>Registration and Login</h3>
		<p>Use the <span>SignUp/LogIn button</span> on the home page.</p>
	</section>

	<section>
		<h3>Main Landing Page</h3>
		<p>Accessible by clicking on the icon <img src="./../images/global_icon.png" alt="Main page"> inside the navbar.</p>
		<p>This page shows all the requests available on CodePortal, based on selected filters.<br>
		You can press one of them to open it.</p>
	</section>

	<section>
		<h3>Social Friends Page</h3>
		<p>Accessible by clicking on the icon <img src="./../images/friends_icon.png" alt="Main page"> inside the navbar.</p>
		<p>This page shows all the friend requests you received as well as a form that allows you to find other users using their usernames.</p>
	</section>

	<section>
		<h3>Requests Search Page</h3>
		<p>Accessible by clicking on the icon <img src="./../images/search_icon.png" alt="Search page"> inside the navbar.</p>
		<p>This page allows you to find specific requests inside the CodePortal Database using three different search parameters: Title, Author's username and Programming Language.</p>
	</section>

	<section>
		<h3>Mail Page</h3>
		<p>Accessible by clicking on the icon <img src="./../images/mail_icon.png" alt="Mail page"> inside the navbar.</p>
		<p>This page shows you all your emails. You can switch between inbox and outbox by clicking on
			<span>Incoming</span> or <span>Sent</span> in the top left corner.</p>
		<p>Unread messages are marked with a white box. Read messages are marked with a gray box.</p>
		<p>To write a new email you can press on the <img src="./../images/plus_round.png" alt="">
		 icon located near the bottom right corner.</p>
	</section>

	<section>
		<h3>Profile Page</h3>
		<p>Accessible by clicking on the icon <img src="./../images/profile_icon.png" alt="Profile page"> inside the navbar.</p>
		<p>This page shows your personal profile as seen by the other users, made exception of the dashboard which is only visible to you.</p>
		<p>Here you can see some statistics on your activity in CodePortal, followed by all your published requests.</p>
		<p>Using the dashboard you can:<br>
			<ol>
				<li>Change your profile picture (Allowed formats are png and jpeg).</li>
				<li>Delete your profile picture.</li>
				<li>Make a new request.</li>
			</ol>
		</p>
	</section>

	<section>
		<h3>Request Viewing Page</h3>
		<p>Accessible by clicking on any request box.</p>
		<p>This page shows all the information regarding the selected request.
		Those information are
		<ol>
			<li>Title</li>
			<li>Author</li>
			<li>Description</li>
			<li>Programming Language</li>
			<li>A list of submitted codes by other users (each of them is clickable)</li>
		</ol>
		</p>
		<p>If you're the author of the request or an administrator then you'll also see the 
			<span>Edit</span> and <span style="color: red;">Delete</span> options.</p>
		<p>Right under the description there is the <span>Submit your Code</span> option that allows you to submit a code reply.</p>
	</section>

	<section>
		<h3>Code Viewing Page</h3>
		<p>Accessible by clicking on any code box.</p>
		<p>This page shows the information regarding the selected code:
			<ol>
				<li>Author</li>
				<li>Code body</li>
				<li>Like/Dislike buttons</li>
				<li>A list of comments by other users</li>
			</ol>
		</p>
		<p>If you're the author of the request or an administrator then you'll also see the 
			<span>Edit</span> and <span style="color: red;">Delete</span> options.</p>
		<p>By hovering on a comment in the case you're the author or an administrator, it will appear a <span style="color: red;">red delete button</span>.</p>
	</section>

	<script>
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