<?php
/* Questo file contiene delle funzioni per inserire informazioni nel database. */

require_once './../path.php';
require_once UTILS_DIR.'sessionUtil.php';
require_once UTILS_DIR.'informationUtil.php';

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

	} elseif ($_GET['action']=='newcode') {
		
		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		newCodeSubmit();
	} elseif ($_GET['action']=='newcomment') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		newComment();

	} elseif ($_GET['action']=='upcode') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		updateCode();

	} elseif ($_GET['action']=='rmcomment') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		removeComment();

	} elseif ($_GET['action']=='rmreq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		removeRequest();

	} elseif ($_GET['action']=='upreq') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		updateRequest();

	} elseif ($_GET['action']=='rmcode') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		removeCode();
	} elseif ($_GET['action']=='rmfriend') {

		if (!isLogged()) {
			header('location: ./../login.php');
			exit;
		}

		removeFriend();
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
		header('location: ./../new_request.php?message='.'Error some information was missing!');
		exit;
	}

	$title = $dbmanager->sqlInjectionFilter($_POST['title']);
	$language = $dbmanager->sqlInjectionFilter($_POST['language']);
	$description = $dbmanager->sqlInjectionFilter($_POST['description']);

	$query = 'INSERT INTO richiesta(Autore, Titolo, Descrizione, Linguaggio, Visibilita) '.
			 'VALUES ('.$_SESSION['userID'].', "'.$title.'", "'.$description.'", "'.$language.'", 1)';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	//header('location: ./../new_request.php?message='.'Request successfully received.');
	exit;
}

function newCodeSubmit() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['request']) || !isset($_POST['code'])) {
		header('location: ./../new_code.php?message='.'Error submitting your code!');
		exit;
	}

	$request = $dbmanager->sqlInjectionFilter($_POST['request']);
	$code = $dbmanager->sqlInjectionFilter($_POST['code']);

	$query = 'INSERT INTO risposta(Autore, Richiesta, Codice, UltimaModifica) '.
			 'VALUES ('.$_SESSION['userID'].', '.$request.', "'.$code.'", CURRENT_DATE)';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../new_code.php?id='.$_POST['request'].'&message='.'Code successfully sent!');
	exit;
}

function newComment() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['comment']) && isset($_POST['return'])) {
		header('location: ./../code.php?id='.$_POST['return']);
		exit;
	} elseif (!isset($_POST['comment']) && !isset($_POST['return'])) {
		header('location: ./../code.php');
		exit;
	}

	$comment = $dbmanager->sqlInjectionFilter($_POST['comment']);
	$code_id = $dbmanager->sqlInjectionFilter($_POST['return']);

	$query = 'INSERT INTO commento(Autore, Risposta, Testo) '.
			 'VALUES ('.$_SESSION['userID'].', '.$code_id.', "'.$comment.'")';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../code.php?id='.$code_id);
	exit;
}

function updateCode() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['old_code_id']) || !isset($_POST['code'])) {
		header('location: ./../new_code.php?message='.'Error updating your code!');
		exit;
	}

	$old_id = $dbmanager->sqlInjectionFilter($_POST['old_code_id']);

	$query = 'SELECT R.Autore AS ID '.
			 'FROM risposta R '.
			 'WHERE R.ID='.$old_id;

	$author = $dbmanager->performQuery($query);

	if ($author==null || $author->num_rows!=1) {
		header('location: ./../new_code.php?message='.'Error updating your code!');
		exit;
	}

	$author = $author->fetch_assoc();

	// Controllo di sicurezza
	if (!$_SESSION['admin'] && $_SESSION['userID']!=$author['ID']) {
		header('location: ./../new_code.php?message='.'Error updating your code!');
		exit;
	}

	$code = $dbmanager->sqlInjectionFilter($_POST['code']);

	$query = 'UPDATE risposta R '.
			 'SET R.Codice="'.$code.'" '.
			 'WHERE R.ID='.$old_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../new_code.php?id='.$_POST['request'].'&message='.'Code updated!');
	exit;
}

function removeComment() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['comment_id'])) {
		header('location: ./../home.php');
		exit;
	}

	$comment_id = $dbmanager->sqlInjectionFilter($_POST['comment_id']);

	$query = 'SELECT C.Autore '.
			 'FROM commento C '.
			 'WHERE C.ID='.$comment_id;

	$author = $dbmanager->performQuery($query);

	if ($author==null || $author->num_rows!=1) {
		header('location: ./../home.php');
		exit;
	}

	$author = $author->fetch_assoc();

	// Controllo di sicurezza
	if (!$_SESSION['admin'] && $_SESSION['userID']!=$author['Autore']) {
		header('location: ./../home.php');
		exit;
	}

	$query = 'DELETE C.* '.
			 'FROM commento C '.
			 'WHERE C.ID='.$comment_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../home.php');
	exit;
}

function removeRequest() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['request_id'])) {
		header('location: ./../view.php');
		exit;
	}

	$request_id = $dbmanager->sqlInjectionFilter($_POST['request_id']);

	$request = getRequest($request_id);

	if ($request==null || $request->num_rows!=1) {
		header('location: ./../view.php');
		exit;
	}

	$request = $request->fetch_assoc();

	$author = $request['Autore'];

	// Controllo di sicurezza
	if (!$_SESSION['admin'] && $_SESSION['userID']!=$author) {// Se non è né l'admin né l'autore
		header('location: ./../view.php');
		exit;
	}

	$query = 'DELETE R.* '.
			 'FROM richiesta R '.
			 'WHERE R.ID='.$request_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../page.php');
	exit;
}

function updateRequest() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['old_req_id']) 
	  || !isset($_POST['language']) 
	  || !isset($_POST['title']) 
	  || !isset($_POST['description'])) {
	  	header('location: ./../new_request.php?message='.'Error some information was missing!');
		exit;
	}

	$old_id = $dbmanager->sqlInjectionFilter($_POST['old_req_id']);

	$request = getRequest($old_id);

	if ($request==null || $request->num_rows!=1) {
		header('location: ./../view.php?id='.$old_id);
		exit;
	}

	$request = $request->fetch_assoc();

	// Controllo di sicurezza
	if (!$_SESSION['admin'] && $_SESSION['userID']!=$request['Autore']) {
		header('location: ./../view.php?id='.$old_id);
		exit;
	}

	$language = $dbmanager->sqlInjectionFilter($_POST['language']);
	$title = $dbmanager->sqlInjectionFilter($_POST['title']);
	$description = $dbmanager->sqlInjectionFilter($_POST['description']);
	$public = $dbmanager->sqlInjectionFilter($_POST['public']);

	$query = 'UPDATE richiesta R '.
			 'SET R.Titolo="'.$title.'", '.
			 	'R.Linguaggio="'.$language.'", '.
			 	'R.Descrizione="'.$description.'", '.
			 	'R.Visibilita=1 '.
			 'WHERE R.ID='.$old_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../new_request.php?message='.'Request successfully received.');
	exit;
}

function removeCode() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['code_id'])) {
		header('location: ./../home.php');
		exit;
	}

	$code_id = $dbmanager->sqlInjectionFilter($_POST['code_id']);

	$query = 'SELECT R.Autore '.
			 'FROM risposta R '.
			 'WHERE R.ID='.$code_id;

	$author = $dbmanager->performQuery($query);

	if ($author==null || $author->num_rows!=1) {
		return;
	}

	$author = $author->fetch_assoc();

	// Controllo di sicurezza
	if (!$_SESSION['admin'] && $_SESSION['userID']!=$author['Autore']) {
		header('location: ./../home.php');
		exit;
	}

	$query = 'DELETE R.* '.
			 'FROM risposta R '.
			 'WHERE R.ID='.$code_id;

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../home.php');
	exit;
}

function removeFriend() {
	global $dbmanager;
	global $_SESSION;

	if (!isset($_POST['id'])) {
		return;
	}

	$friend_id = $dbmanager->sqlInjectionFilter($_POST['id']);

	$query = 'DELETE A.* '.
			 'FROM amicizia A '.
			 'WHERE (A.Utente1='.$_SESSION['userID'].' AND '.
			 	'A.Utente2='.$friend_id.') OR ( '.
			 	'A.Utente1='.$friend_id.' AND A.Utente2='.$_SESSION['userID'].') '.
			 	' AND A.DataAmicizia IS NOT NULL';

	$dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	header('location: ./../profile.php?id='.$friend_id);
	exit;
}

?>