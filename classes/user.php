<?php
class user {
	private $_db;
	private $_data;
	private $_sessionname;
	private $_cookiename;
	private $_isloggedin;
	
	public function __construct ($user = null) {
		$this->_db = db::getinstance();
		$this->_sessionname = config::get('session/sessionname');
		$this->_cookiename = config::get('remember/cookiename');
		if (!$user) {
			if (session::exists($this->_sessionname)) {
				$user = session::get($this->_sessionname);
				if ($this->find($user)) {
					$this->_isloggedin = true;
				} else {
					// process log out
				}
			}
		} else {
			$this->find($user);
		}
	}
	
	public function update ($fields = array(), $id = null) {
	
		if (!$id && $this->isloggedin()) {
			$id = $this->data()->id;
		}
		
		if (!$this->_db->update('users', $id, $fields)) {
			throw new Exception('there was a problem updating');
		}
	}
	
	public function create ($fields = array ()) {
		if (!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}
	
	public function find ($user = null) {
		if ($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function login ($username = null, $password = null, $remember = false) {
		if (!$username && !$password && $this->exists()) {
			session::put($this->_sessionname, $this->data()->id);
		} else {
	
			$user = $this->find($username);
			
			if ($user) {
				if ($this->data()->password === hash::make($password, $this->data()->salt)) {
					session::put($this->_sessionname, $this->data()->id);
					
					if ($remember) {
						$hash = hash::unique();
						$hashcheck = $this->_db->get('session', array ('userid', '=', $this->data()->id));
						
						if (!$hashcheck->count()) {
							$this->_db->insert('session', array(
								'userid' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashcheck->first()->hash;
						}
						cookie::put($this->_cookiename, $hash, config::get('remember/cookieexpiry'));
					}
					
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}
	
	
	public function logout () {
	
		$this->_db->delete('session', array('userid', '=', $this->data()->id));
	
		session::delete($this->_sessionname);
		cookie::delete($this->_cookiename);
	}
	
	public function data () {
		return $this->_data;
	}
	
	public function isloggedin () {
		return $this->_isloggedin;
	}
}