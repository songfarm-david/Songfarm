<?php require_once('initialize.php');

class User extends MySQLDatabase{

	public $username;
	public $timezone;
	public $city;
	public $country;


	public function has_location($user_id){
		global $db;

		$sql = "SELECT timezone, country FROM user_timezone WHERE user_id = {$user_id}";
		$result = $db->query($sql);
		if($db->has_rows($result) > 0){
			$row = $db->fetch_array($result);
			$this->user_location($row['timezone'], $row['country']);
			return $this->timezone = $row['timezone'];
		} else {
			return false;
		}
	}

	public function user_location($timezone, $country){
		$this->city = $this->clean_city($timezone);
		$this->country = $country;
	}

	/* takes submitted country code from workshop.php
	* Enters it into the database
	*
	* @param int - the user's session id (from login)
	* @param string - the value of $_POST['timezone']
	*/
	public function insert_timezone($user_id, $timezone, $country){
		global $db;

		$sql = "INSERT INTO user_timezone (user_id, timezone, country) ";
		$sql.= "VALUES ($user_id, '$timezone', '$country')";
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

	/**
	* public function - takes a timezone
	* from PHP and cleans it by removing
	* '/'s and '_'.
	*
	* @param string PHP timezone
	* @return string
	*/
	public static function clean_city($city){
		if( ($pos = strpos($city, '/') ) !== false ) { // remove 'America/'
			$clean_timezone = substr($city, $pos+1);
			if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove second level '.../'
				$clean_timezone = substr($clean_timezone, $pos+1);
			}
		}
		return $clean_timezone = str_replace('_',' ',$clean_timezone); // remove the '_' in city names
	}

}

$user = new User;

?>
