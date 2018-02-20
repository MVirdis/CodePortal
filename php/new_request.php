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

	$target_id = $_SESSION['userID'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['username']; ?>'s Profile - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="stylesheet" href="./../style/new_request.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<div>
		<div class="container">
			<h1>New Request</h1>
			<form action="./utils/interactionDB.php?action=newreq" method="POST" enctype="application/x-www-form-urlencoded">
				<input name="title" type="text" placeholder="Title" autocomplete="off" required>
				<input type="text" name="language" placeholder="Language" autocomplete="off" required>
				<label>Public: <input type="checkbox" name="public"></label>
				<textarea name="description" placeholder="Description" autocomplete="off" required></textarea>
				<div><input type="submit" value="Send"></div>
			</form>
		</div>
	</div>
</body>
</html>