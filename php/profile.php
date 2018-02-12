<?php
	if(!isset($_SESSION))
		session_start();

	require_once __DIR__.'/path.php';
	include UTILS_DIR.'sessionUtil.php';

	if (isLogged() && isset($_GET['id']) && $_SESSION['userID'] == $_GET['id'])
		include './personal_profile.php';
	else
		include './show_profile.php';

?>