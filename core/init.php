<?php
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'userdb' => 'practice',
		'commentdb' => 'comments'
	),
	'remember' => array(
		'cookiename' => 'hash',
		'cookieexpiry' => 2629740,
	),
	'session' => array(
		'sessionname' => 'user',
		'tokenname' => 'token'
	)
);

// Include Class Files
spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

//Recognizes if user asked to be remembered and logs user in
if (cookie::exists(config::get('remember/cookiename')) && !session::exists(config::get('session/sessionname'))) {
	$hash = cookie::get(config::get('remember/cookiename'));
	$hashcheck = db::getinstance()->get('session', array('hash', '=', $hash));
	if ($hashcheck->count()) {
		$user = new user($hashcheck->first()->userid);
		$user->login();
	}
}