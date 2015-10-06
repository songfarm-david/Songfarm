<?php require_once('initialize.php');

class User extends MySQLDatabase{

	public $username;
	public $timezone;
	public $city;
	public $country;


	public function has_timezone($user_id){
		global $db;

		$sql = "SELECT timezone FROM user_timezone WHERE user_id = {$user_id}";
		$result = $db->query($sql);
		if($db->has_rows($result) > 0){
			$row = $db->fetch_array($result);
			$this->user_city($row['timezone']);
			return $this->timezone = $row['timezone'];
		} else {
			return false;
		}
	}

	public function user_city($timezone){
		if( ($pos = strpos($timezone, '/') ) !== false ) { // remove 'America/'
			$clean_timezone = substr($timezone, $pos+1);
			if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove second level '.../'
				$clean_timezone = substr($clean_timezone, $pos+1);
			}
		}
		$this->city = $clean_timezone;
	}

	/* takes submitted country code from workshop.php
	* Enters it into the database
	*
	* @param int - the user's session id (from login)
	* @param string - the value of $_POST['timezone']
	*/
	public function insert_timezone($user_id, $timezone){
		global $db;

		$sql = "INSERT INTO user_timezone (user_id, timezone) ";
		$sql.= "VALUES ($user_id, '$timezone')";
		if(!$result = $db->query($sql)){
			echo "There was an error updating your timezone";
		}
	}

	/*
	*	Used on profile.php
	* Retrieve user info based on their user_id
	*
	*/
	public function retrieve_user_data($profile_id){
		global $db;
		$sql = "SELECT user_id, user_type, user_name, user_email, reg_date ";
		$sql.= "FROM user_register ";
		$sql.= "WHERE user_id = $profile_id";
		$result = $db->query($sql);
		$user_array = $db->fetch_array($result);
		$this->username = $user_array['user_name'];
	}

}

$user = new User;

?>
