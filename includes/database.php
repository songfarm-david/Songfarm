<?php require_once('initialize.php');

class MySQLDatabase{

	protected $connection;

	function __construct() {
		$this->open_connection();
	}

	function __destruct() {
		$this->close_connection();
	}

	private function open_connection(){
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if(mysqli_connect_errno()) {
			die("Database connect failed: " .
					mysqli_connect_error() .
					" (" . mysqli_connect_errno() . ")"
			);
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
		//echo " dbresult " . print_r($result);
		$this->confirm_query($result);
		return $result;
	}

	private function confirm_query($result) {
		if(!$result) {
			die("Database query failed." . mysqli_error($this->connection));
			file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). ' errors '. mysqli_error($this->connection), FILE_APPEND);
				
		}
	}

	/**
	* Created: 01/12/2016
	*/
	public function hasAffectedRows(){
		return mysqli_affected_rows($this->connection);
	}

	public function escape_value($value) {
		return $value = mysqli_real_escape_string($this->connection, $value);
	}

	function escape_values($array=[]) {
		foreach ($array as $key => $value) {
			return $array[$key] = mysqli_real_escape_string($this->connection, $value);
		}
	}

	function user_name_exists($username) {
		$sql = "SELECT * FROM user_register ";
		$sql.= "WHERE user_name = '{$username}'";
		$sql.= " OR user_email = '{$username}'";
		return $this->query($sql);
	}

	function has_rows($result) {
		return mysqli_num_rows($result);
	}

	function fetch_array($result) {
		return mysqli_fetch_assoc($result);
	}

	function insert_user($array=[]) {
		$this->escape_values($array);
		$sql = "INSERT INTO user_register (";
		$sql.= join(", ", array_keys($array)).", reg_date";
		$sql.= ") VALUES ('";
		$sql.= join("', '",array_values($array))."', NOW())";
		return $this->query($sql);
	}

	function last_inserted_id(){
		return mysqli_insert_id($this->connection);
	}

	function is_valid_email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	*	Checks an email against the database
	* for duplicate
	*
	* @param (string) a valid email
	* @return (object) if result
	*/
	function unique_email($email) {
		$sql = "SELECT user_email FROM user_register ";
		$sql.= "WHERE user_email='{$email}' ";
		$sql.= "LIMIT 1";
		return $this->query($sql);
	}

	/**
	* Looks for a user_id against a unique email -- 01/05/2016
	*
	* @param string email
	*/
	public function getIDbyEmail($email){
		global $db;
		$sql = "SELECT user_id FROM user_register WHERE user_email='{$email}' LIMIT 1";
		if($result = $this->query($sql)){
			$row = $this->fetch_array($result);
			$user_id = $row['user_id'];
			return $user_id;
		} else {
			return false;
		}
	}

	/**
	*	Checks for presence of value
	*
	* @param mixed a value
	* @return $value
	*/
	public function has_presence($value) {
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
	public function has_min_length($string, $min){
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
	* @param string the string to check
	* @param string the string to check against
	*/
	public function string_is_exact($str1, $str2){
		if($str1 !== $str2){
			return false;
		} else {
			return true;
		}
	}
	
	public function getRows($sql)
	{
		$result = $this->query( $sql);
		$data = array();
	
		while ($row = $result->fetch_object()){
	        $data[] = $row;
	    }
		
		return $data;
	}

}

$db = new MySQLDatabase;

?>
