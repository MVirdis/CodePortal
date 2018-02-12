<?php
	if (!isset($_SESSION)) {
		session_start();
	}

	require './path.php';
	include UTILS_DIR.'sessionUtil.php';

	if(isLogged()){
		header('location: ./page.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodePortal - Login</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<link rel="stylesheet" type="text/css" href="./../style/login.css" media="screen">
</head>
<body>
	<div>
		<div class="logo">
			<h1><a href="./../" target="_self">
				&lt;/>Code<span>Portal</span>
			</a></h1>
		</div>
		<div class="title">
			<h1>Log In to CodePortal</h1>
		</div>
		<div class="form_container">
			<form action="./utils/loginUtil.php" enctype="application/x-www-form-urlencoded" method="POST">
				<label>Email:</label>
				<input id="email_input" type="text" name="email" pattern="^\w([\.-_]?[\w0-9])*@(\w[\.-_]?)*\w+$"
					    required>
				<label>Password:</label>
				<input id="password_input" name="password" type="password" required>
				<input type="hidden" name="from" value="login.php" ><!-- Vulnerabile!! -->
				<input type="submit" value="Log In" class="login_button" >
			</form>
			<?php
				if(isset($_GET['error'])) {
					echo '<div class="error">'.$_GET['error'].'</div>';
				}
			?>
		</div>
	</div>
</body>
</html>