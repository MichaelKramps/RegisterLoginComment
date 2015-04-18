<?php

class validate {
	private $_passed = false;
	private	$_errors = array();
	private	$_db = null;
	
	public function __construct () {
		$this->_db = db::getinstance();
	}
	
	public function check($source, $items = array()) {
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rulevalue) {
				
				$value = trim($source[$item]);
				$item = escape($item);
				
				if ($rule === 'required' && empty($value)) {
					$this->adderror("{$item} is required");
				} else if ($rule === 'numeric' && is_numeric($value)) {
					$this->adderror("Username must include letters.");
				} else if (!empty($value)) {
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rulevalue) {
								$this->adderror("{$item} must be a minimum of {$rulevalue} characters.");
							}
						break;
						case 'max':
							if (strlen($value) > $rulevalue) {
								$this->adderror("{$item} must be a maximum of {$rulevalue} characters.");
							}
						break;
						case 'matches':
							if ($value != $source[$rulevalue]) {
								$this->adderror("{$rulevalue}s must match.");
							}
						break;
						case 'unique':
							$check = $this->_db->get($rulevalue, array($item, '=', $value));
							if ($check->count()) {
								$this->adderror("{$item} already exists.");
							}
						break;
					}
				}
				
			}
		
		}
		if (empty($this->_errors)) {
			$this->_passed = true;
		}
		return $this;
	}
	
	private function adderror ($error) {
		$this->_errors[] = $error;
	}
	
	public function errors () {
		return $this->_errors; 
	}
	
	public function passed() {
		return $this->_passed;
	}
}