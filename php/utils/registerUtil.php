<?php
/* Questo File effettua le procedure di verifica e registrazione. */
/* Va chiamato con POST.                                          */

require_once __DIR__.'/../path.php';
require_once UTILS_DIR.'managerDB.php';
include UTILS_DIR.'sessionUtil.php';

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$name = $_POST['first_name'];
$surname = $_POST['last_name'];

$errorMessage = 'Your registration failed!';

$vars = register($email,$username,$name,$surname,$password);
if($vars==null) {
	header('location: ./../home.php?error='.$errorMessage);
	exit;
} else {
	setupSession($vars[0],$vars[1],$vars[2]);
	header('location: ./../page.php');
	exit;
}

function register($email,$username,$name,$surname,$password) {
	global $dbmanager;

	// Pulisco input
	$email = $dbmanager->sqlInjectionFilter($email);
	$username = $dbmanager->sqlInjectionFilter($username);
	$name = $dbmanager->sqlInjectionFilter($name);
	$surname = $dbmanager->sqlInjectionFilter($surname);

	$query = 'SELECT * 
			  FROM utente 
			  WHERE Email="'.$email.'"';

	$result = $dbmanager->performQuery($query);
	$num_rows = mysqli_num_rows($result);

	if(num_rows!=0)
		return null;

	$query = 'INSERT INTO utente(Email,Username,Password,Nome,Cognome) 
			  VALUES ("'.$email.'", "'.$username.'", "'.hash('sha256',$password).'", "'.
			  		     $name.'", "'.$surname.'")';

	$dbmanager->performQuery($query);

	// Retrieve userID
	$query = 'SELECT ID FROM utente WHERE Email="'.$email.'"';

	$result = $dbmanager->performQuery($query);
	$result = $result->fetch_assoc();

	$dbmanager->closeConnection();

	return [$result['ID'], $username, $name, $surname];
}

?>