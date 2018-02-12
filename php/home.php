<?php
	if(!isset($_SESSION)) {
		session_start();
	}

	require './path.php';
	include_once UTILS_DIR.'sessionUtil.php';

	if(isLogged()) {
		header('location: ./page.php');
		exit;
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
	<!-- Definisco di seguito le varie funzioni che gestiscono gli eventi
		Per associare ad un elemento levent handler devo aspettare che lelemento sia caricato -->
	<script src="./../scripts/HomePage.js"></script>
</head>
<body onload="load()">
	<!-- In questa sezione compaiono il menu di navigazione ed il logo oltre ad una prima sezione di presentazione-->
	<section id="first_section">
		<header class="header-top">
			<nav>
				<div id="logo">
					<h1><a href="./" target="_self"><span id="logo_code">&lt;/>Code</span><span id="logo_portal">Portal</span></a></h1>
				</div>
				<ul>
					<li><a class="blue-hover" href="./login.php" target="_self">Log In</a>
					<li><a class="blue-hover" href="#" target="_self">More Instructions</a>
				</ul>
			</nav>
			<!-- Questo div serve ad annullare tutti gli effetti dei precedenti elementi flottanti -->
			<div class="clear"></div>
		</header>
		<div id="header_placeholder"></div>
		<div id="presentation">
			<div id="title_container" class="container">
				<span id="second_title" class="dark">A Social Network for</span>
				<h1 class="dark">Developers</h1>
			</div>
			<div id="links_container" class="container">
				<a id="signup_link" class="button" ><span>Sign Up</span></a>
				<span id="button_sep_text" class="dark">or</span>
				<a id="login_link" href="./login.php" class="dark" >Log In</a>
			</div>
			<div id="signup_form_container" class="<?php if(!isset($_GET['error'])) echo 'hidden'; ?> container dark form-container">
				<form id="signup_form" action="./utils/registerUtil.php" enctype="application/x-www-form-urlencoded" method="POST" >
					<input id="username" name="username" autocomplete="off" maxlength="20" placeholder="Username" pattern="^\s*([A-Za-z_\.\-])+\s*$" type="text" required>
					<input id="first_name" autocomplete="off" class="left" maxlength="30" name="first_name" placeholder="First Name" pattern="^\s*([A-Za-z\u00C0-\u00FF]+\s*)+$" type="text" required>
					<input id="last_name" autocomplete="off" maxlength="30" name="last_name" placeholder="Last Name" pattern="^\s*([A-Za-z\u00C0-\u00FF]+\s*)+$" type="text" required>
					<input id="id_email" autocomplete="off" maxlength="75" name="email" placeholder="Email" pattern="^\w([\.-_]?[\w0-9])*@(\w[\.-_]?)*\w+$" type="text" required>
					<input id="id_password" autocomplete="off" name="password" placeholder="Password" pattern="^[A-Za-z0-9]{8,}$" type="password" required>
					<p id="id_password_err" class="error hidden">Password must be 8 characters long, only letters and numbers.</p>
					<input id="id_password_rep" autocomplete="off" name="password" placeholder="Confirm Password" type="password" required>
					<input type="submit" class="nice-submit large" name="submit" value="Start Now">
				</form>
				<?php
					if(isset($_GET['error'])){
						echo '<div class="error">'.$_GET['error'].'</div>';
					}
				?>
				<a id="back_link_signup" class="dark back-link">Back</a>
			</div>
		</div>
	</section>
	<!-- Nelle sezioni seguenti si mostrano le principali funzionalità del servizio web -->
	<section id="second_section" class="container">
		<div id="second_container" class="center-block">
			<h2>Sharing your codes with your friends is now easy and fast.</h2>
			<p>With Code Portal you can <em>share your code and projects with your friends</em> or make it publicly available!</p>
		</div>
		<div id="third_container" class="center-block">
			<h2>Turn your ideas into concrete Projects.</h2>
			<p>Code Portal helps you to turn your original ideas into shared project by finding other programmers with the same interests.</p>
		</div>
		<div id="fourth_container" class="center-block">
			<h2>Finding mistakes or bugs is easier with the help of more coders.</h2>
			<p>You can request help to other coders who can give you advice and can contact you opening a forum-like public post or directly in a private form.</p>
		</div>
	</section>
	<section id="third_section" class="container">
		<?php include LAYOUT_DIR.'counter.php'; ?>
	</section>
	<?php
		include LAYOUT_DIR.'footer.php';
	?>
</body>
</html>
