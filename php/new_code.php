<?php
	if(!isset($_SESSION))
		session_start();

	require_once __DIR__.'/path.php';
	require_once UTILS_DIR.'sessionUtil.php';
	require_once UTILS_DIR.'informationUtil.php';
	require_once UTILS_DIR.'profilePicUtil.php';

	if (!isLogged()) {
		header('location: ./login.php');
		exit;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Submit a new code - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="stylesheet" href="./../style/new_code.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<div>
	</div>
</body>
</html>