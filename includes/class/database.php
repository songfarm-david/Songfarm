<?php require_once(LIB_PATH.DS.'initialize.php');

class MySQLDatabase{

	public $connection;

	function __construct() {
		$this->open_connection();
	}

	function __destruct() {
		$this->close_connection();
	}

	private function open_connection(){
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if(mysqli_connect_errno()) {
			$mysli_connect_error = mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" .PHP_EOL;
			error_log($mysli_connect_error, 3, ERROR_PATH);
			die("Database connect failed: " . $mysli_connect_error);
		}
	}

	private function close_connection(){
		if(isset($this->connection)){
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	public function query($sql) {
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	/**
	* Confirms a query result exists
	*
	* @param (sql object) an SQL result
	* @return (bool) true if NO result
	**
	* writes database query error to logfile
	*/
	private function confirm_query($result) {
		if(!$result) {
			$mysli_error = mysqli_error($this->connection).PHP_EOL;
			// write to error log
			error_log($mysli_error, 3, ERROR_PATH);
			file_put_contents(SITE_ROOT.DS.'logs'.DS.'error_'.date("m-d-Y").'.txt',$mysli_error,FILE_APPEND);
			die("Query error. Support has been notified. We apologize for the inconvenience.");
		}
	}

	public function beginTransaction(){
		if( phpversion() < '5.5.0' ) {
			return mysqli_autocommit($this->connection, FALSE);
		} else {
			return mysqli_begin_transaction($this->connection);
		}
	}

	public function commit(){
		if( phpversion() < '5.5.0' ){
			mysqli_autocommit($this->connection, TRUE);
		}
		// return mysqli_autocommit($this->connection, TRUE);
		mysqli_commit($this->connection);

	}

	public function rollback(){
		return mysqli_rollback($this->connection);
	}

	/**
	* Created: 01/12/2016
	*/
	public function hasAffectedRows(){
		return mysqli_affected_rows($this->connection);
	}

	public function escapeValue($value) {
		return $value = mysqli_real_escape_string($this->connection, $value);
	}

	/**
	*	Escapes all values in an array
	* @param (array)
	* @return (array) an escaped array
	*/
	public function escapeValues($array=[]) {
		foreach ($array as $key => $value) {
			$array[$key] = mysqli_real_escape_string($this->connection, $value);
		}
		return $array;
	}

	function userNameExists($username) {
		$sql = "SELECT * FROM user_register ";
		$sql.= "WHERE user_name = '{$username}'";
		$sql.= " OR user_email = '{$username}'";
		return $this->query($sql);
	}

	function hasRows($result) {
		return mysqli_num_rows($result);
	}

	function fetchArray($result) {
		return mysqli_fetch_assoc($result);
	}

	function insertUser($array=[]) {
		$this->escape_values($array);
		$sql = "INSERT INTO user_register (";
		$sql.= join(", ", array_keys($array)).", reg_date";
		$sql.= ") VALUES ('";
		$sql.= join("', '",array_values($array))."', NOW())";
		return $this->query($sql);
	}

	function lastInsertedID(){
		return mysqli_insert_id($this->connection);
	}

	function isValidEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	*	Checks an email against the database
	* for duplicate
	*
	* @param (string) a valid email
	* @return (object) if result
	*/
	function uniqueEmail($email) {
		$sql = "SELECT user_email FROM user_register ";
		$sql.= "WHERE user_email='{$email}' ";
		$sql.= "LIMIT 1";
		return $this->query($sql);
	}

	/**
	* Looks for a user_id against a unique email in the database
	*
	* Updated: 01/17/2016
	*
	* @param (string) an email
	* @return (bool) true if user_id is found
	*/
	public function getIDbyEmail($email){
		global $db;
		$sql = "SELECT user_id FROM user_register WHERE user_email='{$email}' LIMIT 1";
		if($result = $this->query($sql)){
			if($row = $this->fetchArray($result)){
				$user_id = $row['user_id'];
				return $user_id;
			}
		}
	}

	/**
	*	Checks for presence of value
	*
	* @param mixed a value
	* @return $value
	*/
	public function hasPresence($value) {
		$value = trim($value);
		if(isset($value) && !empty($value)){
			return $value;
		}
	}

	/**
	* public function - takes a string and
	* checks to see if it is at least a minimum
	* length
	*
	* @param string the string to be checked
	* @param int the minimum length to be checked against
	* @return true on success, false if not has min length
	*/
	public function hasMinLength($string, $min){
		if((strlen($string)) < $min){
			return false;
		} else {
			return true;
		}
	}

	/**
	* public function - checks to see if
	* two values are exact
	*
	* @param (string) the string to check
	* @param (string) the string to check against
	* @return (bool) true if exact, false if not exact
	*/
	public function strIsExact($str1, $str2){
		if($str1 === $str2){
			return true;
		} else {
			return false;
		}
	}

	/**
	* Written by Pradip -- 02/21/16
	*
	* @param (mysql string) an SQL query string
	* @return (array) array of data
	*/
	public function getRows($sql){
		$result = $this->query($sql);
		$data = array();
		while ( $row = $result->fetch_array(MYSQLI_ASSOC) ){ // changed this from $result->fetch_object()
	        $data[] = $row;
	    }
		return $data;
	}


}

$db = new MySQLDatabase;

?>
