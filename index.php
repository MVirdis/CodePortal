<?php
	if(isset($_SESSION))
		session_start();

	include "./php/utils/sessionUtil.php";

	if(isLogged()) {
		header('location: ./php/page.php');
		exit;
	} else {
		header('location: ./php/home.php');
		exit;
	}
	
?>
