<?php require_once('database.php');

class validateLogin extends MySQLDatabase{

	public $username;
	public $password;
	public $errors = [];

	public function validateUser($username, $password){
		global $db;
		if($this->validateUsername($username) && $this->validatePassword($password)){
			// success
			if($db->hasRows($result = $db->userNameExists($this->username))){
				$result = $db->fetchArray($result);
				//print_r($result);
				if(password_verify($this->password, $result['user_password']) ){
					$_SESSION['user_id'] = $result['user_id'];
					$_SESSION['username'] = $result['user_name'];
					$_SESSION['permission'] = $result['permission'];
					// the following checks to see if the response is an Ajax response.
					if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
						// ajax message
						echo false;
					} else {
						redirectTo('../public/workshop.php');
					}
				} else {
					echo $this->errors[] = "Incorrect password";
				}
			} else {
				$this->password = "";
				echo $this->errors[] = "No such username exists";
			}
		}
	}

	private function validateUsername($username) {
		global $db;
		if($db->hasPresence($db->isValidEmail($username))){
			return $this->username = $db->escapeValue($_POST['username']);
		} elseif ($db->hasPresence($username)) {
			return $this->username = $db->escapeValue($_POST['username']);
		} else {
			echo $this->errors[] = "Please enter your username or email to log in";
			return $this->username = "";
		}
	}

	private function validatePassword($password) {
		global $db;
		if($db->hasPresence($password)){
			return $this->password = $db->escapeValue($_POST['password']);
		} else {
			echo $this->errors[] = "Please enter your password";
		}
	}

}

?>
