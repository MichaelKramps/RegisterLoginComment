<?php

class token {
	public static function generate () {
		return session::put(config::get('session/tokenname'), md5(uniqid()));
	}
	
	public static function check ($token) {
		$tokenname = config::get('session/tokenname');
		
		if (session::exists($tokenname) && $token === session::get($tokenname)) {
			session::delete($tokenname);
			return true;
		}
		return false;
	}
}