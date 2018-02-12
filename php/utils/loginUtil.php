<?php
	/* Questo file effettua la procedura di verifica per il login di un utente. */
	/* Va utilizzato inviando le informazioni email e pass via _POST.           */

	require_once __DIR__.'/../path.php';
	require_once UTILS_DIR.'managerDB.php';
	require UTILS_DIR.'sessionUtil.php';

	$email = $_POST['email'];
	$password = $_POST['password'];
	$prec = $_POST['from'];

	$errorMessage = 'Invalid email or password.';

	$vars = login($email, $password);
	if($vars!=null) {
		setupSession($vars[0], $vars[1], $vars[2], $vars[3]);
		header('location: ./../page.php');
		exit;
	} else {
		header('location: ./../'.$prec.'?error='.$errorMessage);
		exit;
	}

	function login($email, $password) {
		global $dbmanager;

		$email = $dbmanager->sqlInjectionFilter($email);
		$password = hash('sha256', $password);

		$query = 'SELECT ID, Username, Nome, Cognome 
				  FROM utente 
				  WHERE Email="'.$email.'" 
				  	AND Password="'.$password.'"';

		$result = $dbmanager->performQuery($query);
		$num_rows = mysqli_num_rows($result);

		$dbmanager->closeConnection();

		if($num_rows!=1) {
			return null;
		}

		$result = $result->fetch_assoc();

		return [$result['ID'], $result['Username'], $result['Nome'], $result['Cognome']];
	}

?>