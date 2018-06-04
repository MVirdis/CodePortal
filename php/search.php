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
	<link rel="stylesheet" href="./../style/search.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">
	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<title><?php echo $_SESSION['username']; ?> - CodePortal</title>

	<script src="./../scripts/ResponseTable.js"></script>
	<script src="./../scripts/DataWrapper.js"></script>
	<script src="./../scripts/AjaxEngine.js"></script>
	<script src="./../scripts/AjaxActivities.js"></script>
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<section>
		<div class="heading">
			<h1>Find a Code Request</h1>
			<ul>
				<li><label>Title: <input type="text" name="title"></label></li>
				<li><label>Author: <input type="text" name="author"></label></li>
				<li><label>Language: <input type="text" name="Language"></label></li>
			</ul>
		</div><br>
		<div>
			<div id="results_container" class="results">
			</div>
		</div>
	</section>

	<div style="height: 200px">
	</div>

	<script>
		var container = document.getElementById('results_container');
		var options = document.getElementsByTagName('input');

		for (var i = 0; i < options.length; i++) {
			options[i].addEventListener('keyup', (function(title_input,author_input,language_input){
				return function(){
					AjaxActivities.searchRequests(title_input.value,
												  author_input.value,
												  language_input.value,
												  DataWrapper.MainRequestWrapper);
				};
			})(options[0], options[1], options[2]));
		}
	</script>
</body>
</html>