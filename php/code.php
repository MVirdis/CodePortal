<?php

if(!isset($_SESSION))
	session_start();

require_once './path.php';
require_once UTILS_DIR.'sessionUtil.php';
require_once UTILS_DIR.'informationUtil.php';
require_once UTILS_DIR.'profilePicUtil.php';
require_once LAYOUT_DIR.'dataWrapper.php';

if(!isLogged()) {
	header('location: ./login.php');
	exit;
}

if(!isset($_GET['id'])) {
	header('location: ./home.php');
	exit;
}

$reply = getReply($_GET['id']);

if ($reply==null || $reply->num_rows!=1) {
	header('location: ./home.php');
	exit;
}

$reply = $reply->fetch_assoc();

$author = getUser($reply['Autore']);

if ($author==null || $author->num_rows!=1) {
	header('location: ./home.php');
	exit;
}

$author = $author->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $author['Username']; ?>'s Reply - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,700" rel="stylesheet">
	<link rel="stylesheet" href="./../style/code.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<section>
		<div class="container">
			<h1>Reply #<?php echo $reply['ID']; ?></h1>
			<div class="author">
				<?php echo getPic($author['ID']); ?>
				<span><?php echo $author['Username']; ?></span> submitted:
			</div>
			<div class="code">
				<code>
					<pre><?php echo htmlspecialchars($reply['Codice']); ?></pre>
				</code>
			</div>
			<div>
				<div class="comments">
					Commenti
				</div>
			</div>
		</div>
	</section>
</body>
</html>