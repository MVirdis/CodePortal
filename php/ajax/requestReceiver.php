<?php
	require_once __DIR__.'/../path.php';
	require_once UTILS_DIR.'informationUtil.php';
	require_once AJAX_DIR.'AjaxResponse.php';

	session_start();

	$response = new AjaxResponse();

	if(isset($_GET['pattern'])) {

		$pattern = $_GET['pattern'];
		$result = getFriendsOfUserLike($_GET['pattern']);

		// Se non ci sono amici che corrispondono risposta vuota
		if($result==null || $result->num_rows==0) {
			$response->responseCode = 1;
			echo json_encode($response);
			return;
		}

		// Altrimenti popolo l'AjaxResponse
		$i = 0;
		while($row = $result->fetch_assoc()) {
			$response->data[$i] = $row['Username'];
			$i = $i+1;
		}

		// Setto i parametri
		$response->responseCode = 0;
		$response->message = "OK";

		// Rispondo
		echo json_encode($response);

	} elseif (isset($_GET['read'])) {

		$id = $_GET['read'];
		$check = getMail($id);

		$check = $check->fetch_assoc();
		// Se quello che ha mandato la richiesta non è il destinatario della email
		if($_SESSION['userID']!=$check['Destinatario']) {
			echo json_encode($response);
			return;
		}

		// Eseguo la richiesta
		$query = 'UPDATE messaggio M '.
				 'SET M.Visualizzato=1 '.
				 'WHERE M.ID='.$id;
		$dbmanager->performQuery($query);
		$dbmanager->closeConnection();

		// Setto i parametri
		$response->responseCode = 0;
		$response->message = "OK";
		$response->data[0] = $id;

		echo json_encode($response);
	} elseif (isset($_GET['patternx'])) {

		$pattern = $_GET['patternx'];
		$result = getUsersLike($pattern);

		// Se non ci sono amici che corrispondono risposta vuota
		if($result==null || $result->num_rows==0) {
			$response->responseCode = 1;
			echo json_encode($response);
			return;
		}

		// Altrimenti popolo l'AjaxResponse
		$i = 0;
		while($row = $result->fetch_assoc()) {
			$response->data[$i] = [$row['ID'], $row['Username']];
			$i = $i+1;
		}

		// Setto i parametri
		$response->responseCode = 0;
		$response->message = "OK";

		// Rispondo
		echo json_encode($response);

	} elseif (isset($_GET['t']) || isset($_GET['a']) || isset($_GET['l'])) {

		$t = $a = $l = null;

		if (isset($_GET['t']) && $_GET['t'] != null && $_GET['t']!='')
			$t = $_GET['t'];

		if (isset($_GET['a']) && $_GET['a'] != null && $_GET['a']!='')
			$a = $_GET['a'];

		if (isset($_GET['l']) && $_GET['l'] != null && $_GET['l']!='')
			$l = $_GET['l'];

		$result = getRequestsLike($t, $a, $l);

		if ($result == null || $result->num_rows == 0) {
			$response->responseCode = 1;
			echo json_encode($response);
			return;
		}

		$i = 0;
		while($row = $result->fetch_assoc()) {
			$response->data[$i] = $row;
			$i = $i+1;
		}

		// Setto i parametri
		$response->responseCode = 0;
		$response->message = "OK";

		// Rispondo
		echo json_encode($response);

	} else {
		echo json_encode($response);
	}
?>