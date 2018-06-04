<?php
	if(!isset($_SESSION))
		session_start();

	require_once __DIR__.'/path.php';
	include UTILS_DIR.'sessionUtil.php';

	if(!isLogged()) {
		header('location: ./home.php');
		exit;
	}

	if(!isset($_GET['info'])) {
		$_GET = ['info'=>'toprated'];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">
	<!-- importo il font Open Sans -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="stylesheet" href="./../style/page.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">
	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<title><?php echo $_SESSION['username']; ?> - CodePortal</title>
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<div class="navigation">
		<a id="toprated" href="./page.php?info=toprated" target="_self">Top Rated</a>
		<a id="friends" href="./page.php?info=friends" target="_self">Friends</a>
		<a id="recent" href="./page.php?info=recent" target="_self">Recent</a>
	</div>
	<script>document.getElementById(<?php echo '"'.$_GET['info'].'"'; ?>).classList.add('selected');</script>

	<div class="section">
		<?php
			require UTILS_DIR.'informationUtil.php';
			require LAYOUT_DIR.'dataWrapper.php';
			
			//include LAYOUT_DIR.'privates.php';
		?>
		<div class="container">
		<?php

			if ($_GET['info']=='toprated') {

				$result = topRatedRequests();

			} elseif ($_GET['info']=='friends') {

				$result = friendsRequests();

			} else {

				$result = recentRequests();

			}

			wrapRequests($result);// Actual display of request
		?>
		</div>
	</div>
</body>
</html>