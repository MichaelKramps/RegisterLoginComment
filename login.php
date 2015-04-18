<?php
require_once 'core/init.php';
?>

<html>


<div>
	<div><a href="index.php"><-Back to home</a></div>
	<form action="" method="post">
	<h3>Log in</h3>
	<div>
	<ul>
	<?php
	if (input::exists()) {
	if (token::check(input::get('token'))) {
		$validate = new validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)
		));
		if ($validation->passed()) {
			$user = new user();
			$remember = (input::get('remember') === 'on') ? true : false;
			$login = $user->login(input::get('username'), input::get('password'), $remember);			
			
			if ($login) {
				redirect::to('index.php');
			} else {
				echo '<li>We could not log you in, please try again.</li>';
			}
		} else {
			foreach ($validation->errors() as $error) {
				echo '<li>', $error, '</li>';
			}
		}
	}
}?>	</ul>
	</div>
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" autocomplete="off">
	</div>
	
	<div class="field">
		<label for="username">Password</label>
		<input type="password" name="password" id="password" autocomplete="off">
	</div><br>
	
	<div class="field">
		<label for="remember">
		<input type="checkbox" name="remember" id="remember"> Remember me
		</label><br>&nbsp;
	</div>
	<div class="field">
	<input type="hidden" name="token" value="<?php echo token::generate(); ?>">
	<input type="submit" value="Log in">
	</div>
</form>

</body>
</html>