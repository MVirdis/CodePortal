<?php
	if(!isset($_SESSION)) {
		session_start();
	}

	require './path.php';
	include UTILS_DIR.'sessionUtil.php';

	if(isLogged()) {
		header('location: ./page.php');
		exit;
	} else {
		header('location: ./home.php');
		exit;
	}
?>