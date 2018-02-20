<?php
/* Questo file contiene delle funzioni per inserire informazioni nel database. */

require_once './../path.php';
require_once UTILS_DIR.'managerDB.php';
require_once UTILS_DIR.'sessionUtil.php';

if(isset($_GET['action'])) {
	if(!isset($_SESSION))
		session_start();

	if($_GET['action']=='rm') {
		
		removeProfilePic();

	} elseif ($_GET['action']=='send') {

		if(!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		sendEmail();

	} elseif ($_GET['action']=='accReq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		acceptRequest();

	} elseif ($_GET['action']=='declReq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		declineRequest();

	} elseif ($_GET['action']=='sendReq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		newFriendRequest();

	} elseif ($_GET['action']=='newreq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		newCodeRequest();

	}
}

function removeProfilePic() {
	global $dbmanager;
	global $_SESSION;

	unlink('./../../uploads/'.hash('sha256',$_SESSION['userID']));

	$query = 'UPDATE utente U '.
			 'SET U.Image = NULL '.
			 'WHERE U.ID='.$_SESSION['userID'];

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../profile.php?id='.$_SESSION['userID']);
	exit;
}

function sendEmail() {
	global $dbmanager;
	global $_SESSION;

	if(!isset($_POST['to'])) {
		header('location: ./../mail.php?res=Failed to send the email.');
		exit;
	}

	$destination = $dbmanager->sqlInjectionFilter($_POST['to']);
	$obj = $dbmanager->sqlInjectionFilter($_POST['object']);
	$message = $dbmanager->sqlInjectionFilter($_POST['message']);

	$query = 'SELECT ID '.
			 'FROM utente '.
			 'WHERE Username="'.$destination.'"';

	$result = $dbmanager->performQuery($query);

	if($result==null && $result->num_rows==0) {
		$dbmanager->closeConnection();
		header('location: ./../mail.php?res=Failed to send the email.');
		exit;
	}

	$result = $result->fetch_assoc();

	$destID = $result['ID'];

	$query = 'INSERT INTO messaggio(Oggetto,Testo,Mittente,Destinatario) '.
			 'VALUES ("'.$obj.'","'.$message.'",'.$_SESSION['userID'].','.$destID.') ';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../mail.php?res=Email correctly sent.');
	exit;
}

function acceptRequest() {
	global $dbmanager;
	global $_SESSION;

	if(!isset($_POST['request'])) {
		header('location: ./../friends.php');
		exit;
	}

	// Prelevo la richiesta
	$req_id = $_POST['request'];

	$req_id = $dbmanager->sqlInjectionFilter($req_id);

	$query = 'UPDATE amicizia A '.
			 'SET A.DataAmicizia = CURRENT_DATE '.
			 'WHERE A.ID='.$req_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../friends.php');
	exit;
}

function declineRequest() {
	global $dbmanager;
	global $_SESSION;

	if(!isset($_POST['request'])) {
		header('location: ./../friends.php');
		exit;
	}

	// Prelevo la richiesta
	$req_id = $_POST['request'];

	$req_id = $dbmanager->sqlInjectionFilter($req_id);

	$query = 'DELETE A.* '.
			 'FROM amicizia A '.
			 'WHERE A.ID='.$req_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../friends.php');
	exit;
}

function newFriendRequest() {
	global $dbmanager;
	global $_SESSION;

	if(!isset($_POST['id']))
		return;

	$id = $dbmanager->sqlInjectionFilter($_POST['id']);

	$query = 'SELECT * '.
			 'FROM amicizia A '.
			 'WHERE (A.Utente2='.$_SESSION['userID'].' '.
			 		'AND A.Utente1='.$id.') '.
			 	'AND A.DataAmicizia IS NULL';

	$res = $dbmanager->performQuery($query);

	// Se e' gia' presente una richiesta di amicizia in sospeso concludiamo quella
	if ($res->num_rows != 0) {

		$res = $res->fetch_assoc();

		$query = 'UPDATE amicizia A '.
				 'SET A.DataAmicizia=CURRENT_DATE '.
				 'WHERE A.ID='.$res['ID'];
		$dbmanager->performQuery($query);

		$dbmanager->closeConnection();

		echo 'Chiuso pending request!';

		header('location: ./../profile.php?id='.$id);
		exit;
	}

	// Altrimenti ne creo una nuova
	$query = 'INSERT INTO amicizia(Utente1, Utente2, DataRichiesta) '.
			 'VALUES ('.$_SESSION['userID'].', '.$id.', CURRENT_DATE)';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	echo 'Aperto nuova request!';

	header('location: ./../profile.php?id='.$id);
	exit;
}

function newCodeRequest() {
	global $dbmanager;
	global $_SESSION;

	if(!isset($_POST['title']) || !isset($_POST['language']) || !isset($_POST['description'])) {
		header('location: ./../new_request.php');
		exit;
	}

	$title = $dbmanager->sqlInjectionFilter($_POST['title']);
	$language = $dbmanager->sqlInjectionFilter($_POST['language']);
	$public = $dbmanager->sqlInjectionFilter($_POST['public']);
	$description = $dbmanager->sqlInjectionFilter($_POST['description']);

	$query = 'INSERT INTO richiesta(Autore, Titolo, Descrizione, Linguaggio, Visibilita) '.
			 'VALUES ('.$_SESSION['userID'].', "'.$title.'", "'.$description.'", "'.$language.'", "'.$public.'")';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../profile.php?id='.$_SESSION['userID']);
	exit;
}

?>