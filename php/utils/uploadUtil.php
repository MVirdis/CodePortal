<?php
/* Questo file effettua il caricamento di un'immagine nel database. */
/* Va usato tramite POST.                                           */

require __DIR__.'/../path.php';
require UTILS_DIR.'managerDB.php';
require UTILS_DIR.'sessionUtil.php';

if(!isset($_SESSION))
	session_start();

if (!isLogged()) {
	header('location: ./../home.php');
	exit;
}

$upload_dir = './../../uploads/';
$file_dir = $upload_dir.hash('sha256', $_SESSION['userID']);

if (isset($_POST['submit'])) {
	move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $file_dir);

	updateDatabase();

	header('location: ./../profile.php?id='.$_SESSION['userID']);
	exit;
}

function updateDatabase() {
	global $dbmanager;
	global $_SESSION;

	$query = 'UPDATE utente U '.
			 'SET U.Image=1 '.
			 'WHERE U.ID='.$_SESSION['userID'];
	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();
}

?>