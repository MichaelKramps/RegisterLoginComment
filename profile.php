<?php
require_once 'core/init.php';

$user = new user();

?>

<html>



<body>
<div id="topstrip">
	<?php
	if ($user->isloggedin()) {
?>
	<a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>  |  <a href="logout.php"> Logout</a>
	
	<?php
	} else { 
		echo "";
	}
	?>
	
	
</div>



	<?php
if (!$usernameprof = input::get('user')) {
	redirect::to('index.php');
} else {
	$userprof = new user($usernameprof);
	if (!$userprof->exists()) {
		redirect::to(404);
	} else {
		$profdata = $userprof->data();
	}
}

?>

<h1><?php echo escape($profdata->username) ?>'s Profile</h1>

<p>
Sorry, nothing to see here!
</p>
<div><a href="index.php"><-Back to home</a></div>


</body>


</html>

