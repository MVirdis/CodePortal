<?php
	if(!isset($_SESSION))
		session_start();

	require_once __DIR__.'/path.php';
	include UTILS_DIR.'sessionUtil.php';

	if(!isLogged()) {
		header('location: ./home.php');
		exit;
	}

	if(!isset($_GET['mail'])) {
		$_GET['mail'] = 0;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">
	<!-- importo il font Open Sans -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="stylesheet" href="./../style/mail.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">
	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<script src="./../scripts/ResponseTable.js"></script>
	<script src="./../scripts/DataWrapper.js"></script>
	<script src="./../scripts/AjaxEngine.js"></script>
	<script src="./../scripts/AjaxActivities.js"></script>
	<title>Mail - CodePortal</title>
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<div class="section">
		<div class="list">
			<div>
				<div><a id="incoming_link" href="./mail.php" target="_self">Incoming</a></div>
				<div><a id="sent_link" href="./mail.php?mail=1" target="_self">Sent</a></div>
			</div>
			<script>
				var options = [document.getElementById('incoming_link'),
							   document.getElementById('sent_link')];
				options[<?php echo $_GET['mail']; ?>].setAttribute('class','selected');
			</script>
			<div class="clear"></div>
			<?php
				require UTILS_DIR.'informationUtil.php';
				require LAYOUT_DIR.'dataWrapper.php';
				if($_GET['mail'])
					$emails = getMailsOut();
				else
					$emails = getMailsIn();
				
				getMailList($emails);
			?>
		</div>
		<div class="mail_content">
			<?php
				if(!isset($_GET['view'])) {
					if(isset($_GET['res']))
						echo '<div class="comunication">'.$_GET['res'].'</div>';

					echo '<div class="comunication">Select a message from the left.</div>';
				} else {
					$email = getMail($_GET['view']);
					wrapMailContent($email);
				}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<a id="floating_button"></a>
	<div class="container hidden">
		<div>
			<form id="new_email_form" action="./utils/interactionDB.php?action=send" enctype="application/x-www-form-urlencoded" method="POST">
				<input type="text" autocomplete="off" name="to" maxlength="100" placeholder="To">
				<div id="suggestion_container" class="hidden"></div>
				<input type="text" autocomplete="off" name="object" maxlength="100" placeholder="Object">
				<textarea form="new_email_form" name="message" autocomplete="off" placeholder="Text" cols="9" rows="15"></textarea>
				<div>
					<input type="submit" name="submit" value="Send">
				</div>
			</form>
		</div>
	</div>

	<script>
	// Email form popup
	var float_bttn = document.getElementById('floating_button');
	var new_email = document.getElementsByClassName('hidden')[0];

	float_bttn.addEventListener('click',function(event){
		new_email.setAttribute('class','container');
	});

	var inputs = new_email.getElementsByTagName('form')[0].getElementsByTagName('input');

	// Evito che il tasto invio nei primi 2 campi di testo invii la form
	for(var i=0; i<2; ++i) {
		inputs[i].addEventListener('keydown',function(event) {
			if(event.keyCode==13) {
				event.preventDefault();
			}
		});
	}

	// AJAX per il campo destinatario
	var fireSearchRequest = function() {
		AjaxActivities.searchFriend(this.value,DataWrapper.EmailDestWrapper);
	};

	inputs[0].addEventListener('keyup', fireSearchRequest.bind(inputs[0]) );

	// Chiudi la form al click dello sfondo
	var closeOnClick = function() {
		this.setAttribute('class', 'container hidden');
	};
	var closeOnEsc = function(event) {
		if(event.keyCode==27) {
			//Tasto ESC
			this.setAttribute('class', 'container hidden');
		}
	};

	new_email.addEventListener('click', closeOnClick.bind(new_email) );
	document.addEventListener('keydown', closeOnEsc.bind(new_email));

	// Prevengo la propagazione verso l'alto
	// cosi' se clicco la form non si chiude
	new_email.getElementsByTagName('div')[0].addEventListener('click',event=>{
		event.stopPropagation();
	});
	</script>
</body>
</html>