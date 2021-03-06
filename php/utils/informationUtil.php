<?php
/* Questo file contiene funzioni per ottenere varie informazioni dal database sottoforma di object. */

require_once __DIR__.'/../path.php';
require UTILS_DIR.'managerDB.php';

// Ordina le richieste di codici per popolarita'
function topRatedRequests() {
	global $dbmanager;

	$query = 'SELECT DT.* '.
			 'FROM ( '.
				  'SELECT R.*, '.
				  		'U.ID AS UserID, '.
						'U.Username AS UsernameAutore, '.
						'U.Image, '.
						'U.Amministratore, '.
						'count(DISTINCT RA.ID) AS NumResponses '.
				  'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) '.
				  		'LEFT OUTER JOIN risposta RA ON RA.Richiesta=R.ID '.
				  'WHERE R.Visibilita=1 '.
				  'GROUP BY R.ID, U.Username '.
				  'ORDER BY NumResponses DESC, R.Istante DESC '.
			 ') DT '.
			 'WHERE DT.NumResponses>=( '.
			 	'SELECT avg(DT2.NumResponses) '.
			 	'FROM ( '.
					  'SELECT count(DISTINCT RA.ID) AS NumResponses '.
					  'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) '.
					  		'LEFT OUTER JOIN risposta RA ON RA.Richiesta=R.ID '.
					  'WHERE R.Visibilita=1 '.
					  'GROUP BY R.ID, U.Username '.
					  'ORDER BY NumResponses DESC, R.Istante DESC '.
				 ') DT2 '.
			 ')';

	$rows = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if ($rows == null || $rows->num_rows == 0)
		return null;

	return $rows;
}

// Restituisce le richieste dei soli amici
function friendsRequests() {
	global $dbmanager;

	$query = 'SELECT R.*, U.ID AS UserID, U.Username AS UsernameAutore, U.Image, U.Amministratore '.
			 'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore '.
			 'WHERE EXISTS ( '.
			 '   SELECT * '.
			 '   FROM amicizia A '.
			 '   WHERE (A.Utente1=R.Autore AND A.Utente2='.$_SESSION['userID'].') '.
			 '   	OR (A.Utente1='.$_SESSION['userID'].' AND A.Utente2=R.Autore) '.
			 '      AND A.DataAmicizia IS NOT NULL '.
			 ') AND R.Visibilita=1 '.
			 'ORDER BY R.Istante DESC';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce le richieste ordinate temporalmente
function recentRequests() {
	global $dbmanager;

	$query = 'SELECT R.*, U.ID AS UserID, U.Username AS UsernameAutore, U.Image, U.Amministratore '.
			 'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore '.
			 'WHERE R.Visibilita=1 '.
			 'ORDER BY R.Istante DESC';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce il numero di codici in totale nella piattaforma
function getNumOfTotalCodes() {
	global $dbmanager;

	$query = 'SELECT count(*) AS Num '.
			 'FROM risposta';
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();

	if($result->num_rows==0)
		return 0;
	return $result->fetch_assoc()['Num'];
}

// Restituisce il numero di codici dell'utente $id
function getNumOfCodes($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT count(ID) AS Num '.
		     'FROM risposta '.
		     'WHERE Autore='.$id;
	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if($result->num_rows==0)
		return 0;
	return $result->fetch_assoc()['Num'];
}

// Restituisce il numero di richieste effettuate dall'utente loggato
function getNumOfRequests($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT count(ID) AS Num '.
			 'FROM richiesta '.
			 'WHERE Autore='.$id;
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();

	return $result->fetch_assoc()['Num'];
}

// Restituisce il numero di commenti effettuati dall'utente loggato
function getNumOfComments($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT count(ID) AS Num '.
		     'FROM commento '.
		     'WHERE Autore='.$id;
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();

	if($result->num_rows==0)
		return 0;
	return $result->fetch_assoc()['Num'];
}

// Restituisce l'esperienza dell'utente loggato
function getExperience($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT Esperienza '.
			 'FROM utente '.
			 'WHERE ID='.$id;
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();

	if($result==null)
		return 0;
	return $result->fetch_assoc()['Esperienza'];
}

// Restituisce il contenuto dell'email di id $mailID
function getMail($mailID) {
	global $dbmanager;

	$mailID = $dbmanager->sqlInjectionFilter($mailID);

	$query = 'SELECT M.*, U.Username AS UsernameMittente, U2.Username AS UsernameDestinatario '.
			 'FROM ( '.
			 	'SELECT * FROM messaggio '.
			 	'WHERE Destinatario='.$_SESSION['userID'].' '.
			 	'OR Mittente='.$_SESSION['userID'].' '.
			 ') M '.
			 ' INNER JOIN utente U ON U.ID=M.Mittente '.
			 '   INNER JOIN utente U2 ON U2.ID=M.Destinatario '.
			 'WHERE M.ID='.$mailID;

	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();
	return $result;
}

// Restituisce tutte le email in arrivo all'utente loggato
function getMailsIn() {
	global $dbmanager;

	$query = 'SELECT M.*, U1.ID AS UserID, U1.Username AS UsernameTarget, U1.Image, 1 AS dir '.
			 'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Mittente '.
			 'WHERE M.Destinatario='.$_SESSION['userID'].' '.
			 'ORDER BY M.Istante DESC';
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();
	return $result;
}

// Restituisce tutte le email inviate dall'utente loggato
function getMailsOut() {
	global $dbmanager;

	$query = 'SELECT M.*, U1.ID AS UserID, U1.Username AS UsernameTarget, U1.Image, 0 AS dir '.
			 'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Destinatario '.
			 'WHERE M.Mittente='.$_SESSION['userID'].' '.
			 'ORDER BY M.Istante DESC';
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();
	return $result;
}

// Restituisce gli amici dell'utente loggato con username like $pattern
function getFriendsOfUserLike($pattern) {
	global $dbmanager;

	$pattern = $dbmanager->sqlInjectionFilter($pattern);

	$query = 'SELECT IF(U1.Username="'.$_SESSION['username'].'",U2.Username,U1.Username) AS Username '.
			 'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID '.
			 '    INNER JOIN utente U2 ON A.Utente2 = U2.ID '.
			 'WHERE A.DataAmicizia IS NOT NULL '.
			 '  AND ((U1.ID='.$_SESSION['userID'].' AND U2.Username LIKE "%'.$pattern.'%") '.
			 '       OR (U2.ID='.$_SESSION['userID'].' AND U1.Username LIKE "%'.$pattern.'%")) ';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce le richieste di amicizia ancora in sospeso per l'utente loggato
function getFriendReqs() {
	global $dbmanager;

	$query = 'SELECT A.ID, '.
					'A.Utente1 AS UserID, '.
			 		'U1.Username, '.
			 		'U1.Image, '.
			 		'U1.Amministratore '.
			 'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID '.
			 'WHERE A.DataAmicizia IS NULL '.
			 '  AND A.Utente2='.$_SESSION['userID'];

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce i nomi utenti registrati presso il sito like $pattern
function getUsersLike($pattern) {
	global $dbmanager;

	$pattern = $dbmanager->sqlInjectionFilter($pattern);

	$query = 'SELECT U.ID, U.Username, U.Amministratore '.
			 'FROM utente U '.
			 'WHERE U.Username LIKE "%'.$pattern.'%" '.
			   'AND U.Username <> "'.$_SESSION['username'].'"';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce l'username di un utente a partire dal suo id
function id2Username($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT U.Username '.
			 'FROM utente U '.
			 'WHERE U.ID='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce il formato immagine dell'immagine utente
function id2Pic($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT U.Image '.
			 'FROM utente U '.
			 'WHERE U.ID='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if($result==null || $result->num_rows == 0)
		return null;

	return $result;
}

// Restituisce l'utente
function getUser($id) {
	global $dbmanager;

	if ($id == null) {
		return null;
	}

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT U.* '.
			 'FROM utente U '.
			 'WHERE U.ID='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce una richiesta a partire dall'id
function getRequest($id) {
	global $dbmanager;

	if ($id == null) {
		return null;
	}

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT R.* '.
			 'FROM richiesta R '.
			 'WHERE R.ID='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce la risposta di id $id
function getReply($id) {
	global $dbmanager;

	if ($id == null) {
		return null;
	}

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT R.* '.
			 'FROM risposta R '.
			 'WHERE R.ID='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce i codici proposti come risposta di una richiesta di id $id
function getReplies($id) {
	global $dbmanager;

	if ($id==null) {
		return null;
	}

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT RA.*, U.Username, U.Amministratore '.
			 'FROM risposta RA INNER JOIN utente U ON U.ID=RA.Autore '.
			 'WHERE RA.Richiesta='.$id;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Controlla se l'utente loggato e' amico di quello passato
function checkFriendship($id) {
	global $dbmanager;

	$id = $dbmanager->sqlInjectionFilter($id);

	$query = 'SELECT IF(A.DataAmicizia IS NULL, '.
						'IF( A.Utente1='.$_SESSION['userID'].', 1, 0), '.
					  	'2 ) AS Flag '.
			 'FROM amicizia A '.
			 'WHERE (A.Utente1='.$_SESSION['userID'].' AND A.Utente2='.$id.') '.
			 	'OR (A.Utente1='.$id.' AND A.Utente2='.$_SESSION['userID'].') ';
	$result = $dbmanager->performQuery($query);
	$dbmanager->closeConnection();

	// Se non ci sono risultati imposto il valore 0
	if($result == null || $result->num_rows == 0) {
		$result = ['Flag'=>0];
	} else {
		$result = $result->fetch_assoc();
	}

	return $result['Flag'];
}

// Restituisce le richieste nel database in base alle informazioni fornite
function getRequestsLike($title, $author, $language) {
	global $dbmanager;

	if ($title==null && $author == null && $language==null)
		return null;

	$title = $dbmanager->sqlInjectionFilter($title);
	$author = $dbmanager->sqlInjectionFilter($author);
	$language = $dbmanager->sqlInjectionFilter($language);

	$query = 'SELECT R.*, U.ID AS UserID, U.Amministratore, U.Username AS UsernameAutore, '.
				'IFNULL(DT.NumRisposte, 0) AS NumRisposte '.
			 'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) '.
			 	' LEFT OUTER JOIN ( '.
			 		'SELECT R2.ID, count(*) AS NumRisposte '.
			 		'FROM richiesta R2 INNER JOIN risposta RA ON RA.Richiesta = R2.ID '.
			 		'GROUP BY R2.ID '.
			 	' ) DT ON DT.ID=R.ID '.
			 'WHERE '.
			 		(($title!=null)? ' R.Titolo LIKE "%'.$title.'%" AND ' : '' ).
			 		(($author!=null)? ' U.Username LIKE "%'.$author.'%" AND ' : '' ).
			 		(($language!=null)? ' R.Linguaggio LIKE "%'.$language.'%" AND ' : '' ).
			 		' 1 '.
			 'ORDER BY R.Istante DESC';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

// Restituisce il numero di like di un codice
function retLikes($code) {
	global $dbmanager;

	if ($code==null) {
		return 0;
	}

	$code = $dbmanager->sqlInjectionFilter($code);

	$query = 'SELECT count(*) AS Likes '.
			 'FROM risposta R INNER JOIN likes L ON L.Risposta=R.ID '.
			 'WHERE R.ID='.$code;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if ($result==null)
		return null;

	if ($result->num_rows==0)
		return 0;

	$result = $result->fetch_assoc();

	return $result['Likes'];
}

// Restituisce il numero di dislike di un codice
function retDislikes($code) {
	global $dbmanager;

	if ($code==null) {
		return 0;
	}

	$code = $dbmanager->sqlInjectionFilter($code);

	$query = 'SELECT count(*) AS Dislikes '.
			 'FROM risposta R INNER JOIN dislikes D ON D.Risposta=R.ID '.
			 'WHERE R.ID='.$code;

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if ($result==null)
		return null;

	if ($result->num_rows==0)
		return 0;

	$result = $result->fetch_assoc();

	return $result['Dislikes'];
}

// Restituisce vero se l'utente ha già messo mi piace al codice
function retIsLiked($code) {
	global $dbmanager;

	if ($code==null) {
		return null;
	}

	$code = $dbmanager->sqlInjectionFilter($code);

	$query = 'SELECT IF( EXISTS ( '.
				'SELECT * '.
				'FROM likes L '.
				'WHERE L.Utente='.$_SESSION['userID'].' '.
				'AND L.Risposta='.$code.' '.
			 ' ) ,1,0) AS Result ';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if ($result==null)
		return null;

	if ($result->num_rows==0)
		return null;

	$result = $result->fetch_assoc();

	return $result['Result'];
}

// Restituisce vero se l'utente ha già messo mi piace al codice
function retIsDisliked($code) {
	global $dbmanager;

	if ($code==null) {
		return null;
	}

	$code = $dbmanager->sqlInjectionFilter($code);

	$query = 'SELECT IF( EXISTS ( '.
				'SELECT * '.
				'FROM dislikes L '.
				'WHERE L.Utente='.$_SESSION['userID'].' '.
				'AND L.Risposta='.$code.' '.
			 ' ) ,1,0) AS Result ';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	if ($result==null)
		return null;

	if ($result->num_rows==0)
		return null;

	$result = $result->fetch_assoc();

	return $result['Result'];
}

// Restituisce tutti i commenti relativi ad un codice
function getComments($code) {
	global $dbmanager;

	if ($code==null) {
		return null;
	}

	$code = $dbmanager->sqlInjectionFilter($code);

	$query = 'SELECT C.*, U.ID AS UserID, U.Username, U.Amministratore '.
			 'FROM commento C INNER JOIN utente U ON U.ID=C.Autore '.
			 'WHERE C.Risposta='.$code.' '.
			 'ORDER BY C.Istante DESC';

	$result = $dbmanager->performQuery($query);

	$dbmanager->closeConnection();

	return $result;
}

?>