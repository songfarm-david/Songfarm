<?php require_once('initialize.php');
/**
* Songcircle class
*
*/
class Songcircle extends MySQLDatabase{

	/**
	* Public variables
	*
	* @var (int) user_id of creator of a given songcircle
	* @var (string) songcircle name
	*	@var (datetime) date of songcircle
	*	@var (int) number of participants allowed for a given songcircle
	*	@var (int) number of waiting list participants allowed for a given songcircle
	*/
	public $created_by_id;
	public $songcircle_name="Songfarm Open Songcircle";
	public $date_of_songcircle;
	public $max_participants=2;
	public $max_wait_participants=2;

	/**
	*	Protected variables
	*
	* @var (int) user_id
	* @var (string) a unique id
	*	@var (int) permission rating for a given songcircle
	*/
	protected $user_id;
	protected $songcircle_id;
	protected $songcircle_permission=0;

	/**
	* Private variables
	*
	* @var (int) a global user id representing Songfarm global user
	*/
	private $global_user_id = 0;

	/**
	* Checks if an Open Songcircle exists, is open
	* AND status is equal to 0 (not started)
	*
	* Updated: 01/13/2016
	*
	* @param (int) Songfarm global id = 0
	* @return (bool) true if Open Songcircle does not exist
	* @return (bool) true if Open Songcircle has not started AND is full
	* @return (bool) true if Open Songcircle has started
	*/
	private function isNotOpenSongcircle($user_id=0){
		global $db;

		$sql = "SELECT * ";
		$sql.= "FROM songcircle_create ";
		$sql.= "WHERE created_by_id = {$user_id} AND songcircle_permission = 0";
		if( $result = $db->query($sql) ){

			// if no songcircle is present
			if( ($row = $db->has_rows($result)) == 0 ){
				// no songcircle is present
				return true;
			}
			// else if there is ONE present
			elseif ( ($row = $db->has_rows($result)) == 1 ) {
				$data = $db->fetch_array($result);

				// if status is not started
				if( $data['songcircle_status'] == 0 ){
					// check if songcircle is full
					if ( $this->isFullSongcircle($data['songcircle_id'],$data['max_participants'],$this->songcircle_permission) ){
						// if full, return date to be used in createNewOpenSongcircle function
						return $this->date_of_songcircle = $data['date_of_songcircle'];
					}
				} elseif( $data['songcircle_status'] == 1 ){
					// if already started, return date to be used in createNewOpenSongcircle function
					return $this->date_of_songcircle = $data['date_of_songcircle'];
				}

			} // end of: if ($row = $db->has_rows($result)) == 0 )
		} // end of: if($result = $db->query($sql))
	} // end of: function isNotOpenSongcircle

	/**
	* Automatically checks if an open songcircle exists
	*
	* Created: 01/13/2016
	*/
	function __construct(){
		// if open songcircle DOES NOT exist
		if($this->isNotOpenSongcircle($this->global_user_id)){
			// create a new one
			$this->createNewOpenSongcircle($this->date_of_songcircle);
		}
	}

	/**
	* Inserts new Open Songcircle into Database
	*
	* Also logs the creation of said songcircle into logs/
	*
	* Updated: 01/13/2016
	*
	* @param (datetime) last scheduled time of previous Open Songcircle
	* @return (bool) true if insert successful
	*/
	private function createNewOpenSongcircle($last_scheduled_time){
		global $db;

		// initialize date of new open songcircle
		if(!$last_scheduled_time){
			// initiate a new custom time here
			$dt = new DateTime("2016-02-16T22:00:00", new DateTimeZone("UTC"));
		} else {
			// use last scheduled time and add 1 week to it
			$dt = new DateTime($last_scheduled_time, new DateTimeZone("UTC"));
			$dt->modify('+1 week');
		}
		// collect target date into variable
		$date_of_songcircle = $dt->format('Y-m-d\TH:i:00');
		// create new songcircle id
		$songcircle_id = uniqid('');

		// Insert new Open Songcircle
		$sql = "INSERT INTO songcircle_create (";
		$sql.= "songcircle_id, created_by_id, songcircle_name, date_of_songcircle, max_participants";
		$sql.= ") VALUES (";
		$sql.= "'$songcircle_id', $this->global_user_id, '$this->songcircle_name', '$date_of_songcircle', '$this->max_participants')";

		if($result = $db->query($sql)){
			// check if affected rows
			while(mysqli_affected_rows($db->connection) > 0){

				// construct log text
				$log_text = 'UTC-'.$date_of_songcircle.' '.$this->songcircle_name.' ('.$songcircle_id.') -- created by: '.$this->global_user_id.' on '.date('m/d/y g:iA T',time()). PHP_EOL;
				// write to log file
				file_put_contents('../logs/songcircle_create.txt',$log_text,FILE_APPEND);

				return true;
			}
		}
	} // end of: function createNewOpenSongcircle

	/**
	* Displays all visible songcircles
	*
	*	Updated: 01/13/2016
	*
	* @return (string) an html table
	*/
	public function display_songcircles(){
		global $db;

		// init row counter
		$row_counter=0;

		// select all from songcircle_create table
		$sql = "SELECT * FROM songcircle_create WHERE created_by_id = $this->global_user_id";
		// if result
		if($result = $db->query($sql)){

			// begin display output:
			$output = "<table id=\"songcircleTable\">";

			// while array rows..
			while($row = $db->fetch_array($result)){
				// begin table row
				$output.= "<tr data-row=\"".$row_counter."\">";

				// if no $_SESSION data
					if( empty($_SESSION['user_id']) && !isset($_SESSION['user_id']) ){
						// format scheduled times in UTC
						$output.= "<td class=\"date\">".$this->formatUTC($row['date_of_songcircle'])."</td>";
					} else {
						// format scheduled times in user timezone
						// $output.= "<td class=\"date\">".$this->user_timezone($row['date_of_songcircle'], $this->timezone)."</td>";
						/*
						Needs coding...
						*/
					}

			// table data: SONGCIRCLE NAME
				$output.= "<td class=\"name\">".$row['songcircle_name']."<br>";

				// registered participants display & jQuery Trigger
					$output.= "<span class=\"triggerParticipantsTable\">";
					$output.= "(".$this->getParticipantCount($row['songcircle_id'])." of ".$row['max_participants']." participants registered)";
					$output.= "</span>";
				// end of display

				// Hidden participants TABLE
					$output.= "<table class=\"participantsTable hide\">";
					// if partipants
					if($participants = $this->fetchParticipantData($row['songcircle_id'])){

						foreach ($participants as $participant) {
							$output.= "<tr><td><a href=\"profile.php?id=".$participant['user_id']."\">".$participant['user_name']."</a></td>";
							$output.= "<td>".$this->formatTimezone($participant['timezone']).", ".$participant['country_name']."</td></tr>";
						}

					}	else {
						// if no participants
						$output.= '<td>No one has yet to register for this Songcircle</td>';
					}
					$output.= "</table>";
				// end of hidden participants table

				$output.= "</td>";
			// end of name of songcircle td

			// optional: "created by" display:
				// $output.= "<td class=\"created\">Created by: <br><a href=\"profile.php?id=user_id_here\">User name here</a></td>";

			// FORM td
			$output.= "<td class=\"form\">";
				// if songcircle not started
				if( $row['songcircle_status'] == 0 ){

					$output.= "<input type=\"hidden\" name=\"songcircle_id\" value=\"".$row['songcircle_id']."\">";
					$output.= "<input type=\"hidden\" name=\"date_of_songcircle\" value=\"".$row['date_of_songcircle']."\">";
					$output.= "<input type=\"hidden\" name=\"songcircle_name\" value=\"".$row['songcircle_name']."\">";

					// if $_SESSION data is provided
					if( isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ){

						// if user is registered
						if( $this->isRegisteredUser($row['songcircle_id'], $_SESSION['user_id']) ){

							// display unregister button
							$output.= "<input type=\"submit\" value=\"Unregister\" name=\"unregister\">";

						} // end of: if( $this->isRegisteredUser($row['songcircle_id']) )
					}
					else // end of: isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])
					{
						if( $this->isFullSongcircle($row['songcircle_id'], $row['max_participants'], $row['songcircle_permission']) ){

							$output.= "<input type=\"hidden\" name=\"waiting_list\" value=\"true\">";

							// if full waiting list
							if( $this->isFullWaitingList($row['songcircle_id'],$this->max_wait_participants) ) {
								// disable register button
								$output.= "<input type=\"submit\" class=\"cannot_register\" value=\"Register\">";
							} else {
								// show waiting list button
								$output.= "<input type=\"submit\" value=\"Join Waiting List Now\" data-id=\"triggerWaitList\" data-row=\"".$row_counter."\">";
							}

						} else {

							$output.= "<input type=\"hidden\" name=\"waiting_list\" value=\"false\">";

							// display register button
								$output.= "<input type=\"submit\" value=\"Register\" data-id=\"triggerRegForm\" data-row=\"".$row_counter."\">";

						}
					}
				}
				// if songcircle started
				elseif ( $row['songcircle_status'] == 1 )
				{
					if( !isset($_SESSION['user_id']) || empty($_SESSION['user_id']) ){

						// display join button
						$output.= "<button id=\"divJoin".$row['songcircle_id']."\" class=\"join\" style=\"display: block\">";
						$output.= "<a href=\"startCall.php?songcircleid=".$row['songcircle_id']."\" target=\"new\">Join Now</a>";
						$output.= "</button>";

					} else {

						// display "Songcircle In Progress" button
						$output.= "<div><p>".$row['songcircle_name']." is in progress</p></div>";

					}
				}
				// if songcircle completed
				else
				{
					// display "Songcircle has Completed" button
					$output.= "<div><p>".$row['songcircle_name']." has completed</p></div>";
				}

				// end of FORM td
				$output.= "</td>";

			// end of table row
			$output.= "</tr>";

			// increment row count
			$row_counter++;

			} // end of: while($row = $db->fetch_array($result))

			$output.= "</table>";
			return $output;

		} // end of: if($result = $db->query($sql))

	} // end of: function display_songcircles()

	/**
	* Update user status from unconfirmed to confirmed
	*
	* Updated: 01/12/2016
	*
	* @param (string) a songcircle id
	* @param (int) a user id
	* @return (bool) true if row affected
	*/
	public function confirmUserRegistration($songcircle_id, $user_id){
		global $db;

		$sql = "UPDATE songcircle_register SET confirm_status = 1, confirmation_key = NULL ";
		$sql.= "WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
		if($result = $db->query($sql)){
			// confirm affected rows:
			if ( mysqli_affected_rows($db->connection) > 0 ){
				return true;
			}
		} // end of: if($result = $db->query($sql))
	}


	/* Auxillary Functions */

	/**
	*	Determines if a given songcircle is "full"
	*
	* Updated: 01/26/2016
	*
	* @param (string) a songcircle_id
	* @param (int) the number of max_participants allowed for given songcircle
	* @param (int) permission of songcircle
	* @return (bool) true if given songcircle is full
	*/
	protected function isFullSongcircle($songcircle_id, $max_participants, $songcircle_permission){
		global $db;
		$sql = "SELECT COUNT(*) AS registered_participants FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND confirm_status = 1";
		if($result = $db->query($sql)){
			// fetch array set
			$count = $db->fetch_array($result);
			// compare registered participants with max participants allowed
			if( $count['registered_participants'] == $max_participants ){

				mysqli_free_result($result);
				// if permission is public
				// if( $songcircle_permission == 0 ){
				// 	// create new row into songcircle_wait_list
				// 	$sql = "INSERT INTO songcircle_wait_list ";
				// }
				return true;
			}
		}
	}

	/**
	* Checks to see if Waiting List for a given songcircle is full
	*
	* Created: 01/28/2016
	*
	* @param (srting) songcircle id
	* @param (int) maximimum waiting list participants allowed
	* @return (bool) true if given waiting list is full
	*/
	protected function isFullWaitingList($songcircle_id, $max_wait_participants){
		global $db;

		$sql = "SELECT COUNT(*) AS registered_wait_participants FROM songcircle_wait_register WHERE songcircle_id = '{$songcircle_id}'";
		if($result = $db->query($sql)){
			// fetch array set
			$count = $db->fetch_array($result);
			// compare registered wait participants with max wait participants allowed
			if( $count['registered_wait_participants'] == $max_wait_participants ){
				return true;
			}
		}
	}

	/**
	*	Gets number of registered members for a particular songcircle
	*
	*	Created: 01/13/2015
	*
	* @param (string) a songcircle id
	* @return (int) number of registered members
	*/
	protected function getParticipantCount($songcircle_id){
		global $db;
		$sql = "SELECT COUNT(*) AS registered_participants FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND confirm_status = 1";
		if($result = $db->query($sql)){
			$count = $db->fetch_array($result);
			return $count['registered_participants'];
		}
	}

	/**
	*	Checks to see if a user is registered for a given songcircle
	*
	* Updated: 01/13/2016
	*
	* @param (string) a songcircle id
	* @param ($_SESSION variable) a user id
	* @return	(bool) return true if user is registered for given songcircle
	*/
	private function isRegisteredUser($songcircle_id, $user_id){
		global $db;

		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		if($result = $db->query($sql)){
			if($db->has_rows($result)){
				return true;
			}
		}
	}

	/**
	*	Gets user information for a given songcircle
	*
	* Created: 01/13/2016
	*
	* @param (string) songcircle id
	* @return (array) an array of user data
	*/
	protected function fetchParticipantData($songcircle_id){
		global $db;

		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND confirm_status = 1";
		if($result = $db->query($sql)){
			if($row = mysqli_num_rows($result) > 0){
				while( $row = $db->fetch_array($result) ){
					$user_id = $row['user_id'];
					$sql = "SELECT user_register.user_id, user_name, user_timezone.timezone, country_name FROM user_register, user_timezone WHERE user_register.user_id = {$user_id} AND user_timezone.user_id = {$user_id}";
					if($res = $db->query($sql)){
						$returnData[] = $db->fetch_array($res);
						mysqli_free_result($res);
					}
				}
				return $returnData;
			}
		}
	}


	/**
	* Takes a datetime object and formats it into UTC
	*
	* Reviewed: 01/13/2016
	*
	* @param (datetime) date of Songcircle
	* @return (string) a formatted date time string in UTC
	*/
	protected function formatUTC($date_of_songcircle){
		$date = new DateTime($date_of_songcircle, new DateTimeZone('UTC'));
		return $date->format('l, F jS, Y - \\<\\b\\r\\> g:i A T');
	}

	/**
	*	Checks to see if current time is
	* greater than start time of a songcircle
	*
	*	Created: 01/15/2016
	*
	* @param (datetime) the current time
	* @param (datetime) start time of a songcircle
	* @return (bool) true is expired
	*/
	public function isExpiredLink($current_time, $start_time){
		if($current_time > $start_time){
			return true;
		}
	}


	/**
	* Checks if a user is already registered for a given songcircle
	* (referenced in includes/songcircleRegisterUser.php)
	*
	* Updated: 01/13/2016
	*
	* NOTE: used on public/songcircle.php in the songcircle schedule table
	*
	* @param (string) songcircle_id
	* @param (int) user_id
	* @return (bool) returns true if user IS already registered
	*/
	public function userAlreadyRegistered($songcircle_id, $user_id){
		global $db;
		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		if($result = $db->query($sql)){
			$rows = $db->has_rows($result);
			if($rows > 0){
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	* Formats a timezone string into a friendlier format
	*
	* Updated: 01/30/2016
	*
	* @param (string) a user timezone
	* @return (string) formatted timezone
	*/
	public function formatTimezone($timezone){
		if( ($pos = strpos($timezone, '/') ) !== false ) { // remove 'America/'
			$clean_timezone = substr($timezone, $pos+1);
			if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove second level '.../'
				$clean_timezone = substr($clean_timezone, $pos+1);
				if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove third level if exist'.../'
					$clean_timezone = substr($clean_timezone, $pos+1);
				}
			}
		}
		return $clean_timezone = str_replace('_',' ',$clean_timezone); // remove the '_' in city names
	}

	/**
	* An assistive function that laterally calls private user_timezone function
	*
	* @param (datetime) a songcircle datetime
	* @param (name constant) php timezone name
	* @return a formatted datetime according to the user's timezone
	*/
	public function call_user_timezone($date_of_songcircle, $timezone) {
		$this->timezone = $timezone;
		return $this->user_timezone($date_of_songcircle);
	}

	/**
	* Private function - takes a datetime string (in UTC)
	* and converts it to a users timezone
	*
	* @param datetime a datetime description of a date
	*	@return string formatted date with user timezone
	*/
	protected function user_timezone($date_of_songcircle){
		$date = new DateTime($date_of_songcircle, new DateTimeZone('UTC'));
		$timezone = new DateTimeZone($this->timezone);
		$date->setTimezone($timezone);
		// debug_backtrace tells the last called function
		// depending on the function, display time format accordingly
		$callMethod = debug_backtrace();
		if (isset($callMethod[1]['function']) && $callMethod[1]['function'] == 'display_songcircles')
		{
			return $date->format('l, F jS, Y - \\<\\b\\r\\> g:i A T');
		} else {
			return $date->format('l, F jS, Y - g:i A T');
		}
	}

	/**
	* Currently unwritten!
	*
	* Will remove expired songcircles
	*
	*/
	// function remove_expired_songcircles(){
	// 	// 3 hours past active songcircle OR when songcircle ends, remove it from database and list
	// 	// log record of past songcircles
	// }

	/**
	* UNTESTED
	*
	* Will delete a songcircle from the database by id
	*
	*/
	// public function delete_songcircle($songcircle_id){
	// 	global $db;
	// 	$sql = "DELETE FROM songcircle_create WHERE songcircle_id = '$songcircle_id' LIMIT 1";
	// 	if($db->query($sql)){
	// 		$_SESSION['message'] = "Successfully deleted songcircle.";
	// 		redirect_to('../public/workshop.php');
	// 	} else {
	// 		Message::$message = "We were unable to delete this songcircle"; // should be more specific
	// 	}
	// }

	// public function create_songcircle($user_id){
	// 	global $db;
	//
	// 	$this->user_id = $user_id;
	// 	$this->songcircle_id = uniqid('');
	// 	$this->songcircle_name = mysqli_real_escape_string($db->connection, $_POST['songcircle_name']);
	// 	$this->date_of_songcircle = $_POST['date_of_songcircle'].":00";
	// 	$this->songcircle_permission = $_POST['songcircle_permission'];
	// 	$this->max_participants = $_POST['max_participants'];
	//
	// 	$sql = "INSERT INTO songcircle_create (";
	// 	$sql.= "user_id, songcircle_id, songcircle_name, date_of_songcircle, songcircle_permission, participants";
	// 	$sql.= ") VALUES (";
	// 	$sql.= "$this->user_id, '$this->songcircle_id', '$this->songcircle_name', '$this->date_of_songcircle', '$this->songcircle_permission', $this->max_participants)";
	// 	if($db->query($sql)) {
	// 		// success
	// 		Message::$message = "Success! New Songcircle scheduled.";
	// 	}
	// }




} // end of Class Songcircle

$songcircle = new songcircle();

?>
