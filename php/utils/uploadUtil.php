<?php
/* Questo file effettua il caricamento di un'immagine nel database. */
/* Va usato tramite POST.                                           */

require __DIR__.'/../path.php';
require UTILS_DIR.'managerDB.php';

session_start();

$upload_dir = './../../uploads/';
$file_dir = $upload_dir.hash('sha256',$_SESSION['username']);

if(isset($_POST['submit'])) {
	move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $file_dir);

	updateDatabase();

	header('location: ./../profile.php');
	exit;
}

function updateDatabase() {
	global $dbmanager;
	global $_SESSION;

	$type = $dbmanager->sqlInjectionFilter($_FILES['fileToUpload']['type']);

	$query = 'UPDATE utente U '.
			 'SET U.Image="'.$type.'" '.
			 'WHERE U.ID='.$_SESSION['userID'];
	$dbmanager->performQuery($query);
}

?>