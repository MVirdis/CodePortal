<?php
/* Questo file contiene delle funzioni che convertono il result object in html leggibile dall'utente. */

include_once UTILS_DIR.'profilePicUtil.php';

function wrapRequests($data) {
	if($data==null || $data->num_rows==0) {
		echo '<div style="float:right; display: inline-block;">'.
			 '<span style="color: gray; font-size: 16px; font-family: \'Open Sans\',sans-serif;">'.
			 'There are no requests at the moment with this filter.</span>'.
			 '</div>';
		return;
	}

	while ($row = $data->fetch_assoc()) {
		$img = getPic($row['UserID']);

		echo "<div id='".$row["ID"]."' class='post_container'>".
			 "<div class='post'>".
			 "<span class='author'>".$row["Autore"].":</span><br>".
			 "<span class='description'>".$row["Titolo"]."</span><br>".
			 "<div style='text-align: right;''>".
			 "<span class='details'>".$row["Istante"]." - Language: </span>".
			 "<span class='details' style='color: black;''>".$row["Linguaggio"]."</span>".
			 "</div>".
			 "</div><div class='post_picture'>".
			 	$img.
			 "</div></div>".
			 "<div class='clear'></div>\n".
			 "<script>\n".
			 	"var x_container = document.getElementById('".$row['ID']."');\n".
			 	"x_container.addEventListener('click',function(){\n".
			 		"window.location = './view.php?id=".$row["ID"]."';\n".
			 	"});\n".
			 "</script>";
	}
}

function getMailList($data) {
	if($data==null || $data->num_rows==0) {
		echo '<div>'.
			 '<span style="color: gray; font-size: 16px; font-family: \'Open Sans\',sans-serif;">'.
			 'You don\'t have messages at the moment.</span>'.
			 '</div>';
		return;
	}

	$in_flag = false;

	while($row = $data->fetch_assoc()) {
		$image = getPic($row['UserID']);

		$in_flag = $row['dir'];

		echo "<div ".(($row['Visualizzato']==1 || !$in_flag)?"name='v'":"")." id='".$row['ID']."' class='list_element ".((!$row['Visualizzato'] && $row['dir'])?"marked":"")."'>".
			 "<div class='image'>".
			 $image.
			 "</div>".
			 "<div class='details'>".
			 "<span>".$row['UsernameTarget']."</span><br>".
			 "<a>".
			 $row['Oggetto'].
			 "</a><br>".
			 "<span>".
			 $row['Istante'].
			 "</span>".
			 "</div>".
			 "</div>";
	}

	// Script che associa al click una redirect a mail.php
	// con parametri GET per la email cliccata
	$js_code= "<script>".
			  "var mails = document.getElementsByName('v');\n".
			  "var onClick = function() {\n".
			  		"window.location=(\"./mail.php?mail=".$_GET['mail']."&view=\"+String(this.id));\n".
			  "};\n".
			  "for(var i=0; i<mails.length; ++i) {\n".
					"mails[i].addEventListener('click',onClick.bind(mails[i]));\n".
			  "}\n".
			  "</script>\n";

	echo $js_code;

	if(!$in_flag) return;

	// Restituisco anche uno script che manda una richiesta ajax
	// Quando ogni elemento non letto della lista viene cliccato
	$js_code= "<script>".
			  "var onResponse = function(Object) {\n".
					"window.location=\"./mail.php?mail=".$_GET['mail']."&view=\"+Object.data[0];\n".
			  "};\n".
			  "var mails = document.getElementsByClassName('marked');\n".
			  "var fireAjaxRequest = function() {\n".
			  		"AjaxActivities.markAsRead(Number(this.id), onResponse);\n".
			  "};\n".
			  "for(var i=0; i<mails.length; ++i) {\n".
					"mails[i].addEventListener('click',fireAjaxRequest.bind(mails[i]),true);\n".
			  "}\n".
			  "</script>\n";

	echo $js_code;
}

function wrapMailContent($data) {
	$data = $data->fetch_assoc();
	echo '<span>From: </span>'.$data['UsernameMittente'].'<br>'.
		 '<span>To: </span>'.$data['UsernameDestinatario'].'<br>'.
		 '<span>Object: </span>'.$data['Oggetto'].'<br>'.
		 '<span>Message:</span><br>'.
		 '<div class="text-area">'.
		 $data['Testo'].
		 '</div>';
}

function getFriendReqsList($data) {
	if($data == null || $data->num_rows==0) {
		echo '<p>You don\'t have requests now.</p>';
		return;
	}

	while($row = $data->fetch_assoc()) {
		$output = '<div class="list_element">'.
				  	'<div class="img_container">'.
				  		getPic($row['UserID']).
				  	'</div>'.
				  	'<div class="username">'.
				  		'<a href="./profile.php?id='.$row['UserID'].'">'.$row['Username'].'</a>'.
				  	'</div>'.
				  	'<div class="buttons">'.
				  		'<form action="./utils/interactionDB.php?action=accReq" method="POST">'.
				  			'<input name="request" type="hidden" value="'.$row['ID'].'">'.
					  		'<button>Add</button>'.
					  	'</form>'.
					  	'<form action="./utils/interactionDB.php?action=declReq" method="POST">'.
					  		'<input name="request" type="hidden" value="'.$row['ID'].'">'.
					  		'<button>Decline</button>'.
					  	'</form>'.
					'</div>'.
				  '</div><br>';
		echo $output;
	}
}

// Mostra tutte le risposte ad una richiesta nella pagina di visualizzazione
function wrapRepliesShowcase($data) {

	if ($data == null || $data->num_rows == 0) {
		return;
	}

	while ($row = $data->fetch_assoc()) {
		echo "<div id='".$row['ID']."' class='reply_element'>".
				"<div class='profile_pic'>".getPic($row["Autore"])."</div>".
			 	"<div class='username'><span>".$row["Username"]."</span></div>".
			 	"<div class='date'> Last Change: ".$row["UltimaModifica"]."</div>".
			 	"<div class='rating'>".
			 		"<span style='color: green'>".retLikes($row['ID'])."</span>".
			 		"-".
			 		"<span style='color: red'>".retDislikes($row['ID'])."</span>".
			 	"</div>".
			 "</div>".
			 "<script>\n".
			 	"var div_x = document.getElementById('".$row['ID']."');\n".
			 	"div_x.addEventListener('click', function(){\n".
			 		"window.location = './code.php?id=".$row['ID']."';\n".
			 	"});\n".
			 "</script>";
	}

}

?>