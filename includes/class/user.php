<?php require_once(LIB_PATH.DS.'initialize.php');
/**
* NOTE: this page requires testing!
* Updated: Mar 1, 2016
*/
class User extends MySQLDatabase{

	/**
  *	Static variables
	*
	* @var $user_timezone_table (string) user_timezone table in database
	* @var $user_register_table (string) user_register table in database
	*/
	private static $user_timezone_table = 'user_timezone';
	private static $user_register_table = 'user_register';

	/**
	* Public variables
	*
	* @var $timezone (string) formatted user timezone
	* @var $full_timezone (string) unformatted user timezone
	* @var $city_name (string) user city
	* @var $country_name (string) user country
	* @var $country_code (string) user country code
	*
	* @var $username (string)
	* @var $user_email (string) email of user
	* @var $user_permission (int) permission of user
	* @var $reg_date (date) date of registration
	*/
	public $timezone;
	public $full_timezone;
	public $city_name = NULL;
	public $country_name;
	public $country_code;
	// // //
	public $user_id;
	public $username;
	public $user_email;
	public $user_permission;
	public $reg_date;


	/**
	* Retrieves user data by a user ID
	* AND sets public vars
	*
	* @param (int) an id
	* @return (bool) true on success
	*/
	public function setUserData($user_id){
		global $db;
		$sql = "SELECT * FROM user_register WHERE user_id = $user_id";
		if($result = $db->query($sql)){
			$user_data = $db->fetchArray($result);
			$this->user_id = $user_data['user_id'];
			$this->username = $user_data['user_name'];
			$this->user_email = $user_data['user_email'];
			$this->user_permission = $user_data['permission'];
			$this->reg_date = $user_data['reg_date'];
			// call hasLocation
			$this->hasLocation($user_id);
			return true;
		}
	}

	/**
	* Retrieves user email by ID
	*
	* @param (int) an id
	* @return (bool) true on success
	*/
	public function retrieve_user_email($id){
		global $db;
		$sql = "SELECT user_email FROM user_register WHERE user_id = $id";
		if($user_data = $db->getRows($sql)){
			$this->user_email = $user_data['user_email'];
			return true;
		}
	}

	/**
	* Checks database for user location (timezone and country)
	* by user id
	*
	* @param (int) user id
	*	@return (bool) FALSE if no record
	*/
	public function hasLocation($user_id){
		global $db;
		$sql = "SELECT timezone, full_timezone, country_name, country_code ";
		$sql.= "FROM ".self::$user_timezone_table." WHERE user_id = {$user_id}";
		if($result = $db->query($sql)){
			if($row = $db->fetchArray($result)){
				// call user location function
				$this->userLocation($row['timezone'],$row['country_name']);
				$this->full_timezone = $row['full_timezone'];
				$this->timezone = $row['timezone'];
				return true;
			} else {
				// no rows
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	* Deletes all user information from Database for a given ID
	*
	* @param (int) a user id
	* @return (bool) true on success
	*/
	public function removeUserFromSongfarm($user_id){
		global $db;

		$sql = "SELECT * FROM user_register WHERE user_id = $user_id LIMIT 1";
		if( $result = $db->query($sql) ){
			if ( $db->hasRows($result) ){
				// echo 'Row exists in <b>user_register</b> for User Id '.$user_id.' (line '.__LINE__.') '.'<br>';
				mysqli_free_result($result);
				$sql = "DELETE FROM user_register WHERE user_id = $user_id";
				if( $result = $db->query($sql) ){
					if( mysqli_affected_rows($db->connection) > 0 ){
							// echo 'record deleted';
							return true; // record deleted successfully
						} else {
							// log error: unable to delete user id X from database
							return false;
						}
				}
			} else {
				// log error here: request to delete user id x -- no user id exists in database table: user_register
				return false;
			}
		}
	}

	/**
	* Retrieve user timezone by user id
	*/
	// public function getTimezoneByID($id){}

	/**
	* Cleans timezone and sets public $city_name var
	* Set public $country_name var
	*
	* @param (string) a timezone
	* @param (string) a country name
	* @return no return value
	*/
	protected function userLocation($timezone, $country_name){
		$this->city = $this->cleanCity($timezone);
		$this->country = $country_name;
	}

	/**
	* Inserts user timezone data into Database
	*
	* Updated: Mar 1, 2016
	*
	* @param (int) user id
	* @param (string) formatted timezone
	* @param (string) unformatted timezone
	* @param (string) country name
	* @param (string) country code
	*
	* NOTE: untested function, specifically city name and country code variables.
	* Confirm SQL query string before advancing
	*
	* @return (bool) true on success
	*/
	public function insert_timezone($user_id, $timezone, $full_timezone, $country_name, $country_code){
		global $db;
		$sql = "INSERT INTO ".self::$user_timezone_table." (user_id, timezone, full_timezone, city_name, country_name, country_code) ";
		$sql.= "VALUES ($user_id, '$timezone', '$full_timezone', ".$this->city_name.", '$country_name', '$country_code')";
		// echo 'insert timezone ' . $sql; // for testing
		if($result = $db->query($sql)){
			return true;
		}
	}

	/**
	* Update user timezone
	*
	* @param (int) user id
	* @param (string) formatted timezone
	* @param (string) unformatted timezone
	* @param (string) country name
	* @param (string) country code
	* @return (bool) true on success
	*/
	public function update_timezone($user_id, $timezone, $full_timezone, $country_name, $country_code){
		global $db;
		if($this->hasLocation($user_id)){ // if user already has location
			// update location in database
			$sql = "UPDATE ".self::$user_timezone_table." ";
			$sql.= "SET timezone = '$timezone', full_timezone = '$full_timezone', city_name = ".$this->city_name.", country_name = '$country_name', country_code = '$country_code' ";
			$sql.= "WHERE user_id = $id";
			// echo 'update_timezone' .$sql; // for testing
			if($result = $db->query($sql)){
				return true;
			}
		}
		/* NOTE: is the following necessary?? */
		// else {
		// 	$this->insert_timezone($user_id, $timezone, $full_timezone, $country_name, $country_code);
		// }
	}


	/* Helper Functions */

	/**
	* Removes unwanted characters (/_)
	* from a timezone string
	*
	* @param (string) PHP formatted timezone
	* @return (string) cleaned timezone
	*/
	public static function cleanCity($city_name){
		if( ($pos = strpos($city_name, '/') ) !== false ) { // remove 'America/'
			$clean_timezone = substr($city_name, $pos+1);
			if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove second level '.../'
				$clean_timezone = substr($clean_timezone, $pos+1);
			}
		}
		return $clean_timezone = str_replace('_',' ',$clean_timezone); // remove the '_' in city names
	}



}

$user = new User();

?>
