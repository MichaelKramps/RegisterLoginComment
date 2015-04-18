<?php

require_once 'core/init.php';

$tablename = 'ndex';
$user = new user();
$commenthandler = comment::getinstance();

if (input::get('comment')) {
	try {
		$commenthandler->insert($tablename, array(
			'username' => $user->data()->username,
			'comment' => $_POST['comment'],
		));
	} catch (Exception $e) {
		die ($e->getMessage());
	}
}
?>
<html>

<body>
<div>
	<?php
	if ($user->isloggedin()) {
?>
	<a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>  |  <a href="logout.php">Logout</a>
	<?php
	} else { ?>
	<a href="login.php">Log in</a> or <a href="register.php">Register</a> to comment
	<?php
	}
	?>
	
	
</div>

	
	<h2>Leave Comment</h2>
	
	<form class="commentform" action="" method="post">
	 <textarea rows="4" cols="40" name="comment"></textarea><br>
	
	<?php if ($user->isloggedin()) { ?>
	<input type="submit" value="Post Comment">
	<?php } else { ?>
	<p>
	You are not logged in.
	</p>
	<?php } ?>
	</form>
	
	<h3>Comments</h3>
	<?php 
	$commenthandler->getcomments($tablename);
	$commenthandler->showcomments();
	?>


</body>


</html>