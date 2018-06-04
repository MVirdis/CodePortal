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
				<span >
				<?php
					if ($author['Amministratore'])
						echo '<span class="admin">'.$author['Username'].'</span> says:';
					else
						echo '<span>'.$author['Username']."</span> says:";
				?>	
				</span>
			</div>
			<div class="description">
				<pre><?php echo $request['Descrizione']; ?></pre>
				<div>
					Language: <span><?php echo $request['Linguaggio']; ?></span>
				</div>
				<?php
					if ($_SESSION['admin'] || $_SESSION['userID']==$author['ID']) {
						echo "<form method=\"POST\" action=\"./new_request.php\" enctype=\"application/x-www-form-urlencoded\">".
								"<input type=\"hidden\" name=\"old_title\" value=\"".$request['Titolo']."\">".
								"<input type=\"hidden\" name=\"old_language\" value=\"".$request['Linguaggio']."\">".
								"<input type=\"hidden\" name=\"old_public\" value=\"".$request['Visibilita']."\">".
								"<input type=\"hidden\" name=\"old_description\" value=\"".htmlspecialchars($request['Descrizione'])."\">".
								"<input type=\"hidden\" name=\"old_id\" value=\"".$request['ID']."\">".
								"<input type=\"submit\" value=\"Edit this request\">".
							 "</form>";
					}
				?>
			</div>
			<div>
				<div class="replies_header">
					<span>Replies:</span>
					<?php
						if ($_SESSION['admin'] || $_SESSION['userID']==$author['ID']) {
							echo "<form id=\"deletion_form\" action=\"./utils/interactionDB.php?action=rmreq\" method=\"POST\" ".
									"enctype=\"application/x-www-form-urlencoded\">".
									"<input type=\"hidden\" name=\"request_id\" value=\"".$_GET['id']."\">".
									"<input type=\"submit\" value=\"Delete request\">".
								 "</form>";
						}
					?>
					<a href="./new_code.php?id=<?php echo $request['ID']; ?>">Submit your code</a>
				</div>
				<div class="replies">
					<?php
						$res = getReplies($request['ID']);
						wrapRepliesShowcase($res, $request['ID']);
					?>
				</div>
			</div>
		</div>
	</section>
	<script>
		// Confirm request deletion if needed
		var form = document.getElementById('deletion_form');
		if (form!=null)
			form.addEventListener('submit', (function(event){
				event.preventDefault();// Fermo la sottomissione del form
				if (confirm('Do you really want to delete this request?')) {
					this.submit();
				}
			}).bind(form));
	</script>
</body>
</html>