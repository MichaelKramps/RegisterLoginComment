<?php

class comment {
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_results, 
			$_count = 0;
			
	private function __construct () {
		try {
			$this->_pdo = new PDO('mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/commentdb'), config::get('mysql/username'), config::get('mysql/password'));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public static function getinstance () {
		if (!isset(self::$_instance)) {
			self::$_instance = new comment();
		}
		return self::$_instance;
	}
	
	public function query ($sql, $params = array()) {
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		return $this;
	}
	
	public function insert ($table, $fields = array()) {
		if(count($fields)) {
			$keys = array_keys($fields);
			$values = null;
			$x = 1;
			
			foreach ($fields as $field) {
				$values .= "?";
				if ($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
			
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}
	
	public function getcomments ($tablename) {
		$this->_error = false;
		
		if ($this->_query = $this->_pdo->prepare("SELECT * FROM " . $tablename)) {
			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
	}
	
	public function showcomments () {
		if ($this->results()) {
			foreach ($this->results() as $comment) {
				echo ("<p>" . $comment->username . " wrote:<br>" . $comment->comment . "</p>");
			}
		} else {
			echo "There are no comments.";
		}
	}
	
	public function results () {
		return $this->_results;
	}
	
	public function error () {
		return $this->_error;
	}
	
	public function count () {
		return $this->_count;
	}
}