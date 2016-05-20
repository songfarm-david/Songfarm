<?php

class Session{

	/**
	* @var (int) user_id
	* @var (string) username
	* @var (int) permission of user (0=false or 1=has_permission)
	*/
	public $user_id;
	public $username;
	public $email;
	public $permission = 0;
	public $timezone;
	public $full_timezone;
	public $country_name;
	public $country_code;

	/**
	*
	*/
	private $logged_in = false;

	/**
	* On execution, calls checkLogin function
	*/
	function __construct() {
		session_start();
		$this->checkLogin();
		$this->isUserLocation();
		// could take additional action here
		if($this->logged_in) {
			// do something if user is logged in
			// echo "User is logged in";
		} else {
			// do something else if user is not logged in
			// echo "User is NOT logged in";
		}
	}

	/**
	* Checks if $_SESSION exists
	*
	* if TRUE, sets all public session variables
	* AND sets $logged_in = true
	*
	* ELSE unsets all public variables
	* AND sets $logged_in = false
	*/
	private function checkLogin() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
			if(isset($_SESSION['permission'])){
				$this->permission = $_SESSION['permission'];
			}
			if(isset($_SESSION['email'])){
				$this->email = $_SESSION['email'];
			}
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			unset($this->username);
			unset($this->permission);
			$this->logged_in = false;
		}
	}

	/**
	* Checks state of private $logged_in var
	*
	* @return (bool) value of boolean
	*/
	public function isLoggedIn() {
		return $this->logged_in;
	}

	/**
	* Sets user location $_SESSION variables if applicable
	*/
	private function isUserLocation(){
		if(isset($_SESSION['country_name']) && isset($_SESSION['country_code'])){
			$this->timezone = $_SESSION['timezone'];
			$this->full_timezone = $_SESSION['full_timezone'];
			$this->country_name = $_SESSION['country_name'];
			$this->country_code = $_SESSION['country_code'];
		}
	}


	/**
	* Unsets all $_SESSION data
	* AND unsets all public vars
	* AND sets $logged_in = false
	*/
	public function logout() {
			unset($_SESSION['user_id']);
			unset($_SESSION['username']);
			unset($_SESSION['permission']);
			unset($_SESSION['message']);
			unset($this->user_id);
			unset($this->username);
			unset($this->permission);
			$this->logged_in = false;
		}
}

$session = new Session;

?>
