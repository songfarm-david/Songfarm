<?php require_once('initialize.php');

class Songcircle extends MySQLDatabase{

	public $user_id;
	public $timezone;
	public $songcircle_name="Songfarm Open Songcircle";
	public $date_of_songcircle;
	public $max_participants=12;
	public $reg_participants;
	public $message;

	protected $songcircle_id;
	protected $songcircle_permission=0;

	/* public function
	*
	* checks to see if an Open Songcircle exists
	* if yes, call is max participants (which creates new open songcircle when true)
	* if no, create new Open Songcircle
	*/
	public function open_songcircle_exists(){
		global $db;
		// query database for a record of global user_id 0. if no rows come back, then run the create method.
		$sql = "SELECT songcircle_id, participants, date_of_songcircle FROM songcircle_create WHERE user_id = 0";
		if($result = $db->query($sql)){
			// fetch array from query
			$result_array = $db->fetch_array($result);
			// assign values to class properties
			$this->songcircle_id = $result_array['songcircle_id'];
			$this->date_of_songcircle = $result_array['date_of_songcircle'];
			// count num rows..
			$row = $db->has_rows($result);
			if($row == 1){ // if Open Songcircle exists..
				$this->open_songcircle_is_max_parts($this->songcircle_id, $this->max_participants, $this->date_of_songcircle);
			} elseif(!$row) { // if NO open songcircle exists..
				$this->create_open_songcircle($this->date_of_songcircle);
			}
		}
	}

	private function create_open_songcircle($last_scheduled_time){
		global $db;

		if(!$last_scheduled_time){
			$dt=new DateTime("2015-11-15T19:00:00", new DateTimeZone("UTC"));
		} else {
			$dt = new DateTime($last_scheduled_time, new DateTimeZone("UTC"));
			$dt->modify('+1 week');
		}
		$date = $dt->format('Y-m-d\TH:i:00');

		$this->user_id=0;
		$this->songcircle_id = uniqid('');
		$this->date_of_songcircle = $date;

		$sql = "INSERT INTO songcircle_create (";
		$sql.= "user_id, songcircle_id, songcircle_name, date_of_songcircle, songcircle_permission, participants";
		$sql.= ") VALUES (";
		$sql.= "'$this->user_id', '$this->songcircle_id', '$this->songcircle_name', '$this->date_of_songcircle', '$this->songcircle_permission', $this->max_participants)";
		if(!$db->query($sql)) {
			$this->message = "There was an error creating the {$this->songcircle_name}.";
		}
		// $tz = $dt->getTimezone();
		// echo $tz->getName(); // returns 'UTC'
	}

	function open_songcircle_is_max_parts($songcircle_id, $max_participants, $date_of_songcircle){
		global $db;
		// if open songcircle reaches max participants then create a new one
		$reg_participants = $this->num_of_parts($songcircle_id);
		if($reg_participants == $max_participants){
			return $this->create_open_songcircle($date_of_songcircle);
		}
	}

	function remove_expired_songcircles(){
		// 3 hours past active songcircle OR when songcircle ends, remove it from database and list
		// log record of past songcircles
	}

	/**
	* Public function - queries database
	* for songcircles and displays them
	*
	* @return string creates a table display on workshop.php
	*/
	public function display_songcircles(){
		global $db;
		$sql = "SELECT ";
		$sql.= "songcircle_id, date_of_songcircle, songcircle_name, ";
		$sql.= "songcircle_permission, participants, user_register.user_id, user_name ";
		$sql.= "FROM songcircle_create, user_register ";
		$sql.= "WHERE songcircle_create.user_id = user_register.user_id";
		if(!$result = $db->query($sql)){
			echo "Error retrieving songcircles";
		} elseif($db->has_rows($result) == 0) {
			echo $this->message = "No songcircles currently scheduled. Why don't you be the first and create one now?";
		} else {
			// display the songcircles
			$output = "<table>";
			while ($row = $db->fetch_array($result)) {
					$output.= "<tr>";
					$output.= "<input type=\"hidden\" data-conference-id=\"".$row['songcircle_id']."\">";
					$output.= "<td class=\"date\">".$this->user_timezone($row['date_of_songcircle'])."</td>";
					// $output.= "<td class=\"type\"><span class=\"permission\">".$row['songcircle_permission']."</span>&nbsp;";
					$output.= "<td class=\"type\">".$row['songcircle_name']."<br>";
					$output.= "<span class=\"registered\"><a href=\"#\">(".$this->num_of_parts($row['songcircle_id'])." of " .$row['participants']. " participants registered)</a></span></td>";
					$output.= "<td class=\"created\">Created by: <br><a href=\"profile.php?id=".$row['user_id']."\">".$row['user_name']."</a></td>";
					// check to see if registered users equals max users
					if($this->is_full_songcircle($row['participants'], $this->num_of_parts($row['songcircle_id'])) && $this->is_not_registered($row['songcircle_id'])){
						$output.= "<td class=\"cannot_register\"><input type=\"submit\" value=\"Register\"></td>";
					} else {
						$output.= "<td><form method=\"post\" action=".$_SERVER['PHP_SELF'].">";
						$output.= "<input type=\"hidden\" name=\"songcircle_id\" value=\"".$row['songcircle_id']."\">";
						$output.= "<input type=\"hidden\" name=\"date_of_songcircle\" value=\"".$row['date_of_songcircle']."\">";
						$output.= "<input type=\"hidden\" name=\"songcircle_name\" value=\"".$row['songcircle_name']."\">";
						$output.= "<input type=\"submit\"".$this->is_registered($row['songcircle_id']);
						$output.= "</form>";
					}
					// delete button if created user is logged in user
					if($_SESSION['user_id'] == $row['user_id']){
						$output.= "<br><span class=\"delete\"><a href=\"../includes/delete_songcircle.php?songcircle_id=".$row['songcircle_id']."\">Delete</a></span>";
					}
					$output.= "</td>";
					$output.= "</tr>";
			}
			$output.= "</table>";
			echo $output;
		}
	}

	private function num_of_parts($songcircle_id){
		global $db;
		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id'";
		return $rows = $db->has_rows($db->query($sql));
	}

	private function is_full_songcircle($participants, $num_reg_participants){
		if($participants == $num_reg_participants){
			return true;
		} else {
			return false;
		}
	}

	function register($songcircle_id, $user_id, $username, $songcircle_name, $date_of_songcircle){
		global $db;

		$sql = "INSERT INTO songcircle_register (songcircle_id, user_id, username) ";
		$sql.= "VALUES ('$songcircle_id', $user_id, '$username')";
		if(!$result = $db->query($sql)){
			return $this->$message = "Failed to register you for this songcircle.";
		} else {
			$this->message = "You successfully registered for \"{$songcircle_name}\" on ".$this->user_timezone($date_of_songcircle).". Check your inbox for more information.";
		}
	}

	public function unregister($songcircle_id, $user_id, $songcircle_name, $date_of_songcircle){
		global $db;
		// delete record
		$sql = "DELETE FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		// echo $sql;
		if(!$result = $db->query($sql)){
			return $this->message = "Could not unregister you from this songcircle";
		} else {
			$this->message = "You have unregistered from \"{$songcircle_name}\" on ".$this->user_timezone($date_of_songcircle).".";
		}
	}

	protected function is_registered($songcircle_id){
		global $db;
		// if something is true, button says register
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		// echo $sql . '<br>';
		if($result = $db->query($sql)){
			$rows = $db->has_rows($result);
			if($rows > 0){
				return "value=\"Unregister\" name=\"unregister\"> &nbsp; &nbsp; <a href=startCall.php?songcircleid=". $songcircle_id. " target=new>join</a>";
			} else {
				return "value=\"Register\" name=\"register\">";
			}
		}
	}

	protected function is_not_registered($songcircle_id){
		global $db;
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		$result = $db->query($sql);
		if(!$db->has_rows($result)){
			return true;
		}
	}

	public function create_songcircle($user_id){
		global $db;

		$this->user_id = $user_id;
		$this->songcircle_id = uniqid('');
		$this->songcircle_name = mysqli_real_escape_string($db->connection, $_POST['songcircle_name']);
		$this->date_of_songcircle = $_POST['date_of_songcircle'].":00";
		$this->songcircle_permission = $_POST['songcircle_permission'];
		$this->max_participants = $_POST['max_participants'];

		$sql = "INSERT INTO songcircle_create (";
		$sql.= "user_id, songcircle_id, songcircle_name, date_of_songcircle, songcircle_permission, participants";
		$sql.= ") VALUES (";
		$sql.= "$this->user_id, '$this->songcircle_id', '$this->songcircle_name', '$this->date_of_songcircle', '$this->songcircle_permission', $this->max_participants)";
		if($db->query($sql)) {
			// success
			$this->message = "Success! New Songcircle scheduled.";
		}
	}

	public function delete_songcircle($songcircle_id){
		global $db;
		$sql = "DELETE FROM songcircle_create WHERE songcircle_id = '$songcircle_id' LIMIT 1";
		if($db->query($sql)){
			$_SESSION['message'] = "Successfully deleted songcircle.";
			redirect_to('../public/workshop.php');
		} else {
			return $this->message = "error deleting songcircle";
		}
	}

	/**
	* Private function - takes a datetime string
	* set to UTC and converts it to the users timezone
	*
	* @param datetime a datetime description of a date
	*	@return string formatted date with user timezone
	*/
	private function user_timezone($date_of_songcircle){
		$date = new DateTime($date_of_songcircle, new DateTimeZone('UTC'));
		$timezone = new DateTimeZone($this->timezone);
		$date->setTimezone($timezone);
		$callMethod = debug_backtrace();
		if (isset($callMethod[1]['function']) && $callMethod[1]['function'] == 'display_songcircles')
    {
        return $date->format('l, F jS, Y - \\<\\b\\r\\> g:i A T');
    } else {
			return $date->format('l, F jS, Y - g:i A T');
		}
	}

}

$songcircle = new songcircle;

?>
