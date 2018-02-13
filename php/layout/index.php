<?php
	if (!isset($_SESSION))
		session_start();

	header('location: ./../home.php');
	exit;
?>