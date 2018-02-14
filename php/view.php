<?php

if(!isset($_SESSION))
	session_start();

require_once './path.php';
require_once UTILS_DIR.'sessionUtil.php';
require_once UTILS_DIR.'informationUtil.php';
require_once UTILS_DIR.'profilePicUtil.php';
require_once LAYOUT_DIR.'dataWrapper.php';

if(!isLogged() || !isset($_GET['id'])) {
	header('location: ./login.php');
	exit;
}

// Prelevo le info della richiesta
$request = getRequest($_GET['id']);

if ($request==null || $request->num_rows!=1) {
	header('location: ./home.php');
	exit;
}

$request = $request->fetch_assoc();

// Prelevo le info dell'autore
$author = getUser($request['Autore']);

if ($author == null || $author->num_rows != 1 ) {
	header('location: ./home.php');
	exit;
}

$author = $author->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Request <?php echo $request['Titolo']; ?> - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,700" rel="stylesheet">
	<link rel="stylesheet" href="./../style/view.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<section>
		<div class="container">
			<h1><?php echo $request['Titolo']; ?></h1>
			<div class="author">
				<?php echo getPic($author['ID']); ?>
				<span><?php echo $author['Username']." says:"; ?></span>
			</div>
			<div class="description">
				<p><?php echo $request['Descrizione']; ?></p>
			</div>
			<div>
				<div class="replies">
					<?php
						$res = getReplies($request['ID']);
						wrapRepliesShowcase($res);
					?>
				</div>
			</div>
		</div>
	</section>
</body>
</html>