<header>
	<nav>
		<div class="left">
			<span class="rx_space default_cursor">&lt;&#47;&gt;</span>
		</div>
		<div class="right">
			<a href="./page.php" class="icon global"></a>
			<a href="./friends.php" class="icon friends"></a>
			<a href="./search.php" class="icon search"></a>
			<a href="./mail.php" class="icon mail"></a>
			<a href="./profile.php?id=<?php echo $_SESSION['userID']; ?>" class="icon profile"></a>
			<a href="./utils/logoutUtil.php" class="icon signout"></a>
			<span class="sx_space default_cursor"><?php echo $_SESSION['username']; ?></span>
		</div>
	<nav>
</header>
<div class="placeholder clear"></div>