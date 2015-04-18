<?php
require_once 'core/init.php';
?>

<html>

<div>
	<div><a href="index.php"><-Back to home</a></div>
	<form action="" method="post">
		<h3>Register New Account</h3>
		<div class="errors">
		<ul>
		<?php
		if (input::exists()) {
		if (token::check(input::get('token'))) {
			$validate = new validate();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'required' => true,
					'min' => 2,
					'max' => 20,
					'unique' => 'users',
					'numeric' => false
				),
				'password' => array(
					'required' => true,
					'min' => 6,
				),
				'passwordagain' => array(
					'required' => true,
					'matches' => 'password'
				)
			));
			
			if ($validation->passed()) {
				$user = new user();
				$salt = hash::salt(32);
							
				try {
					$user->create(array (
						'username' 	 => input::get('username'),
						'password'	 => hash::make(input::get('password'), $salt),
						'salt'		 => $salt,
					));
					redirect::to('index.php');
				} catch (Exception $e) {
					die($e->getMessage());
				}
				
			} else {
				foreach ($validation->errors() as $error) {
					echo '<li>', $error, '</li>';
				}
			}
		}
	}

	?>	</ul>
		</div>
		<div class="field">
			<label for="username">Username</label><br>
			<input type="text" name="username" id="username" value="<?php echo escape(input::get('username')); ?>" autocomplete="off">
		</div>
		
		<div class="field">
			<label for="password">Choose a Password</label><br>
			<input type="password" name="password" id="password">
		</div>
		
		<div class="field">
			<label for="passwordagain">Repeat Password</label><br>
			<input type="password" name="passwordagain" id="passwordagain">
		</div>
		<div class="field">
		<input type="hidden" name="token" value="<?php echo token::generate(); ?>"><br>
		<input type="submit" value="Register">
		</div>
	</form>
</div>

</body>
</html>