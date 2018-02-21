<?php
	if(!isset($_SESSION))
		session_start();

	require_once __DIR__.'/path.php';
	require_once UTILS_DIR.'sessionUtil.php';
	require_once UTILS_DIR.'informationUtil.php';
	require_once UTILS_DIR.'profilePicUtil.php';

	if (!isLogged()) {
		header('location: ./login.php');
		exit;
	}

	$request = getRequest($_GET['id']);
	if ($request==null || $request->num_rows!=1) {
		header('location: ./page.php');
		exit;
	}

	$request = $request->fetch_assoc();

	if ($request['Visibilita']!=1) {
		header('location: ./page.php');
		exit;
	}

	$edit_flag = isset($_POST['old_code']);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Submit a new code - CodePortal</title>
	<meta charset="utf-8">
	<meta name="author" content="Mario Virdis">
	<meta name="keywords" content="coding code programming social network socialnetwork programs C C++ Java Python">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
	<link rel="stylesheet" href="./../style/new_code.css" type="text/css">
	<link rel="stylesheet" href="./../style/menu.css" type="text/css">

	<link rel="icon" type="image/png" href="./../images/codeportal_logo2.png"  >
</head>
<body>
	<?php include LAYOUT_DIR.'menu.php'; ?>
	<div>
		<div class="container">
			<h1><?php if($edit_flag) echo 'Edit your'; else echo 'New'; ?> Reply to request <?php echo '#'.$request['ID']; ?></h1>
			<p><?php if(isset($_GET['message'])) echo $_GET['message']; ?></p>
			<form action="./utils/interactionDB.php?action=<?php if($edit_flag) echo 'upcode'; else echo 'newcode'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
				<input name="request" type="hidden" value="<?php echo $request['ID']; ?>">
				<textarea name="code" placeholder="Write here your code." autocomplete="off" required><?php 
					if ($edit_flag) {
						echo trim(htmlspecialchars($_POST['old_code']));
					}
				?></textarea>
				<?php
					if ($edit_flag) {
						echo '<input type="hidden" name="old_code_id" value="'.$_POST['code_id'].'" >';
					}
				?>
				<div><input type="submit" value="Send"></div>
			</form>
		</div>
	</div>
	<script>
		// Permetto di premere tab nella text area ed inserire un \t
		var code_space = document.getElementsByTagName('textarea')[0];

		code_space.addEventListener('keydown', (function(element){
			return function(key){
				if(key.keyCode==9) {//tab
					key.preventDefault();// Prevengo il comportamento default
					element.value = element.value + "\t";// Append di \t
				}
			};
		})(code_space));
	</script>
</body>
</html>