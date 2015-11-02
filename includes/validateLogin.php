<?php require_once('database.php');

class validateLogin extends MySQLDatabase{

	public $username;
	public $password;
	public $errors = [];

	public function validateUser($username, $password){
		global $db;
		if($this->validateUsername($username) && $this->validatePassword($password)){
			// success
			if($db->has_rows($result = $db->user_name_exists($this->username))){
				$result = $db->fetch_array($result);
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
						redirect_to('../public/workshop.php');
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
		if($db->has_presence($db->is_valid_email($username))){
			return $this->username = $db->escape_value($_POST['username']);
		} elseif ($db->has_presence($username)) {
			return $this->username = $db->escape_value($_POST['username']);
		} else {
			echo $this->errors[] = "Please enter your username or email to log in";
			return $this->username = "";
		}
	}

	private function validatePassword($password) {
		global $db;
		if($db->has_presence($password)){
			return $this->password = $db->escape_value($_POST['password']);
		} else {
			echo $this->errors[] = "Please enter your password";
		}
	}

}

?>
