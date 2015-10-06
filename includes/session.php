<?php

class Session{

	private $logged_in = false;
	public $user_id;
	public $username;
	public $permission;

	function __construct() {
		session_start();
		$this->check_login();
		// could take additional action here
		if($this->logged_in) {
			// do something if user is logged in
			// echo "User is logged in";
		} else {
			// do something else if user is not logged in
			// echo "User is NOT logged in";
		}
	}

	private function check_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
			$this->permission = $_SESSION['permission'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			unset($this->username);
			unset($this->permission);
			$this->logged_in = false;
		}
	}

	public function is_logged_in() {
		return $this->logged_in;
	}

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
