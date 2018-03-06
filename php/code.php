<?php

if(!isset($_SESSION))
	session_start();

require_once './path.php';
require_once UTILS_DIR.'sessionUtil.php';
require_once UTILS_DIR.'informationUtil.php';
require_once UTILS_DIR.'profilePicUtil.php';
require_once LAYOUT_DIR.'dataWrapper.php';

if(!isLogged()) {
	header('location: ./login.php');
	exit;
}

if(!isset($_GET['id'])) {
	header('location: ./home.php');
	exit;
}

$reply = getReply($_GET['id']);

if ($reply==null || $reply->num_rows!=1) {
	header('location: ./home.php');
	exit;
}

$reply = $reply->fetch_assoc();

$author = getUser($reply['Autore']);

if ($author==null || $author->num_rows!=1) {
	header('location: ./home.php');
	exit;
}

$author = $author->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $author['Username']; ?>'s Reply - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400" rel="stylesheet">
	<link rel="stylesheet" href="./../style/code.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
	<script type="text/javascript" src="./../scripts/ResponseTable.js"></script>
	<script type="text/javascript" src="./../scripts/DataWrapper.js"></script>
	<script type="text/javascript" src="./../scripts/AjaxEngine.js"></script>
	<script type="text/javascript" src="./../scripts/AjaxActivities.js"></script>
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<section>
		<div class="container">
			<h1>Reply #<?php echo $reply['ID']; ?></h1>
			<div class="author">
				<?php echo getPic($author['ID']); ?>
				<?php
					if ($author['Amministratore'])
						echo '<span class="admin">'.$author['Username'].'</span>';
					else
						echo '<span>'.$author['Username'].'</span>';
				?> submitted:
			</div>
			<div class="code">
				<code>
					<pre><?php echo htmlspecialchars($reply['Codice']); ?></pre>
				</code>
				<div class="rating_container">
					<div class="icon like <?php if(retIsLiked($reply['ID'])) echo 'selected'; ?>"></div>
					<span><?php echo retLikes($reply['ID']).' - '.retDislikes($reply['ID']);?></span>
					<div class="icon dislike <?php if(retIsDisliked($reply['ID'])) echo 'selected'; ?>"></div>
					<?php
						if ($_SESSION['userID']==$author['ID'] || $_SESSION['admin']) {
							echo '<div id="remove_container">'.
									'<form action="./utils/interactionDB.php?action=rmcode" method="POST" '.
											'enctype="application/x-www-form-urlencoded">'.
										'<input type="hidden" name="code_id" value="'.$reply['ID'].'">'.
										'<input type="submit" value="Delete this code">'.
									'</form>'.
								 '</div>';

							echo '<div id="change_container">'.
									'<form action="./new_code.php?id='.$reply['Richiesta'].'" method="POST" '.
											'enctype="application/x-www-form-urlencoded"> '.
										'<input type="hidden" name="code_id" value="'.$reply['ID'].'"> '.
										'<input type="hidden" name="old_code" value="'.htmlspecialchars($reply['Codice']).'"> '.
										'<input type="submit" value="Edit code"> '.
									'</form> '.
								 '</div>';
						}
					?>
				</div>
			</div>
			<div>
				<div class="comments">
					<span>Comments</span>
					<div>
						<?php
							$comments = getComments($reply['ID']);
							getCommentsList($comments);
						?>
						<div class="comment">
							<div class="profile_pic">
								<?php echo getPic($_SESSION['userID']); ?>
							</div>
							<div class="content">
								<form action="./utils/interactionDB.php?action=newcomment" method="POST"
									enctype="application/x-www-form-urlencoded">
									<input type="hidden" value="<?php echo $_GET['id']; ?>" name="return">
									<textarea name="comment" placeholder="Write your comment" autocomplete="off" maxlength="10000" rows="1" required></textarea>
									<button class="hidden">Send</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		// Setto le richieste ajax per il mi piace e non mi piace
		var like_el = document.getElementsByClassName('rating_container')[0].getElementsByClassName('icon')[0];
		var dislike_el = document.getElementsByClassName('rating_container')[0].getElementsByClassName('icon')[1];

		function getCustomHandler(toSelect, toDeselect){
			return function(data) {
				toSelect.setAttribute('class', toSelect.getAttribute('class')+' selected');
				toDeselect.setAttribute('class', toDeselect.getAttribute('class').replace(/\sselected/g, ''));
			};
		}

		like_el.addEventListener('click', function(){
			AjaxActivities.likeCode(<?php echo $reply['ID']; ?>, getCustomHandler(like_el, dislike_el));
		});

		dislike_el.addEventListener('click', function(){
			AjaxActivities.dislikeCode(<?php echo $reply['ID']; ?>, getCustomHandler(dislike_el, like_el));
		});

		// Comment button handler
		var bttn = document.getElementsByTagName('button');
		bttn = bttn[bttn.length-1];// L'ultimo pulsante è quello dei commenti
		var area = document.getElementsByTagName('textarea')[0];
		area.addEventListener('click', (function(button){
			return function() {
				button.setAttribute('class', '');
				this.style.outline = '0';
				this.style.borderBottom = '1px solid #09f';
				this.style.width = '280px';
				this.style.height = '180px';
				this.style.resize = 'both';
			};
		})(bttn).bind(area));

		// Admin's remove buttons
		var comments = document.getElementsByClassName('comment');
		for(var i=0; i<comments.length-1; ++i) {
			comments[i].addEventListener('mouseenter', (function(){
				this.getElementsByClassName('delete_button')[0].setAttribute('class', 'delete_button');
			}).bind(comments[i]));

			comments[i].addEventListener('mouseleave', (function(){
				this.getElementsByClassName('delete_button')[0].setAttribute('class', 'delete_button hidden');
			}).bind(comments[i]));
		}

		// Prompt for comment deletion before executing
		var dd_form = document.getElementsByClassName('delete_form');
		for(var i=0; i<dd_form.length; ++i) {
			dd_form[i].addEventListener('submit', (function(event) {
				event.preventDefault();
				if(confirm('Are you sure you want to delete this comment?'))
					this.submit();
			}).bind(dd_form[i]));
		}
	</script>
</body>
</html>