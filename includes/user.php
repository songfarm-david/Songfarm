<?php require_once('initialize.php');

class User extends MySQLDatabase{

	/**
  *	Static variables
	*
	* @var $table_name user_timezone table on database
	*/
	private static $table_name = "user_timezone";

	/**
	* Public variables
	*
	* @var $username string name of user
	* @var $city string user's city
	* @var $country string user's country
	* @var $timezone string user's timezone
	*/
	public $username;
	public $city;
	public $country;
	public $full_timezone;
	public $timezone;


	/**
	* Public Static function - Remove's
	* unwanted characters from a
	* timezone
	*
	* @param string PHP timezone
	* @return string cleaned timezone
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

	/**
	* Public function - Checks if user
	* has timezone, country in the database
	*
	* @param int user id
	*	@return FALSE if no record
	*/
	public function has_location($user_id){
		global $db;

		$sql = "SELECT timezone, country, full_timezone FROM ".self::$table_name." WHERE user_id = {$user_id}";
		$result = $db->query($sql);
		if($db->has_rows($result) > 0){
			$row = $db->fetch_array($result);
			$this->full_timezone = $row['full_timezone'];
			$this->user_location($row['timezone'], $row['country']);
			return $this->timezone = $row['timezone'];
		} else {
			return false;
		}
	}

	/**
	* Public function - inserts user timezone and
	* country into database
	*
	* @param int user id
	* @param string timezone
	* @param string country
	*/
	public function insert_timezone($user_id, $timezone, $country, $full_timezone){
		global $db;

		$sql = "INSERT INTO ".self::$table_name." (user_id, timezone, country, full_timezone) ";
		$sql.= "VALUES ($user_id, '$timezone', '$country', '$full_timezone')";
		$result = $db->query($sql);
	}

	/**
	* Public function - Update user timezone
	*
	* @var int user id
	*/
	public function update_timezone($id, $timezone, $country, $full_timezone){
		global $db;

		$sql = "UPDATE ".self::$table_name." ";
		$sql.= "SET timezone = '$timezone', country = '$country', full_timezone = '$full_timezone' ";
		$sql.= "WHERE user_id = $id";
		$result = $db->query($sql);
	}

	/**
	* Public function - Takes a user id and queries
	* database for user info (username, reg_date, etc)
	*
	* @param int id of user
	* @return array user data
	*/
	public function retrieve_user_data($profile_id){
		global $db;
		$sql = "SELECT * FROM user_register WHERE user_id = $profile_id";
		if($result = $db->query($sql)){
			$user_data = $db->fetch_array($result);
			$this->username = $user_data['user_name'];
		}
	}

	/**
	* Protected function - Sets timezone
	* and country to class variables
	*
	* @param string timezone
	* @param string country
	*/
	protected function user_location($timezone, $country){
		$this->city = $this->clean_city($timezone);
		$this->country = $country;
	}


}

$user = new User;

?>
