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
	<link rel="stylesheet" href="./../style/friends.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">
	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<title><?php echo $_SESSION['username']; ?> - CodePortal</title>

	<script type="text/javascript" src="./../scripts/ResponseTable.js"></script>
	<script type="text/javascript" src="./../scripts/DataWrapper.js"></script>
	<script type="text/javascript" src="./../scripts/AjaxEngine.js"></script>
	<script type="text/javascript" src="./../scripts/AjaxActivities.js"></script>
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<section>
		<div>
			<h2>Your Friend Requests</h2>
		</div>
		<br>
		<div>
			<?php
				require UTILS_DIR.'informationUtil.php';
				require LAYOUT_DIR.'dataWrapper.php';

				$friends = getFriendReqs();

				getFriendReqsList($friends);
			?>
		</div>

	</section>
	<!-- Contenitore nuova amicizia -->
	<div class="container">
		<div>
			<h2>Add a new Friend!</h2>
			<input id="new_friend_input" type="text" autocomplete="off">
			<div id="result_container"></div>
		</div>
	</div>

	<script>
		var input_el = document.getElementById('new_friend_input');

		input_el.addEventListener('keyup', (function(element){
			return function(){
				AjaxActivities.findNewFriend(element.value, DataWrapper.UserList);
			};
		})(input_el));
	</script>
</body>
</html>