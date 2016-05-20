<?php require_once(LIB_PATH.DS.'initialize.php');
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
	* @var (string) a user timezone
	* @var (string) a unique id
	*	@var (int) permission rating for a given songcircle
	*/
	protected $user_id;
	protected $user_timezone;
	protected $songcircle_id;
	protected $duration_of_songcircle = '3:00:00'; // in minutes
	protected $songcircle_permission=0;

	/**
	* Private variables
	*
	* @var (int) a global user id representing Songfarm global user
	*/
	private $global_user_id = 0;


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
	* Displays all visible songcircles
	*
	*	Updated: 01/13/2016
	*
	* @return (string) an html table
	*/
	public function displaySongcircles(){
		global $db;

		// get all the data from table songcircle_create
		$sql = "SELECT * FROM songcircle_create WHERE created_by_id = $this->global_user_id";

		if( $result = $db->query($sql) ){

			// init count
			$count = 0;
			// begin output
			$output = '<table class="songcircle_table">';

			while( $row = $db->fetchArray($result) ){

				/*** collect dateList information here ***/

				$output.= '<tr data-row-count="'.$count.'">';

				// if no $_SESSION data
					if( empty($_SESSION['user_id']) || !isset($_SESSION['user_id']) || !isset($user->timezone) ){
						// format times in UTC
						$output.= '<td data-month-date="'.$this->createMonthDate($row['date_of_songcircle']).'">'.$this->formatUTC($row['date_of_songcircle']).'</td>';
					}
					else
					{
						// format times according to user timezone
						$output.= '<td>'.$this->userTimezone($row['date_of_songcircle'], $user->timezone).'</td>';

						/*** Can I add the span here through PHP ***/
					}

					$output.= '<td name="event_name"><div><p>'.$row['songcircle_name'].'</p>';
					$output.= '<span class="triggerParticipantsTable blue">('.$this->getParticipantCount($row['songcircle_id']).' of '.$row['max_participants'].' participants registered)</span></div>';

					$output.= '</td>';

					$output.= '<td name="songcircle_data_container">';

					// if songcircle not started
					if( $row['songcircle_status'] == 0 ){

						// write hidden inputs for songcircle values
						$output.= '<input type="hidden" name="songcircle_id" value="'.$row['songcircle_id'].'">';
						$output.= '<input type="hidden" name="date_of_songcircle" value="'.$row['date_of_songcircle'].'">';
						$output.= '<input type="hidden" name="songcircle_name" value="'.$row['songcircle_name'].'">';

						// is songcircle full
						if( $this->isFullSongcircle( $row['songcircle_id'], $row['max_participants'], $row['songcircle_permission']) ){

							// songcircle full, waiting list true
							$output.= '<input type="hidden" name="waiting_list" value="true">';

							// is waiting list full
							if( $this->isFullWaitingList($row['songcircle_id'], $this->max_wait_participants) ) {
								// waiting list full, display inactive button
								$output.= '<span class="button_container cannot_register">Songcircle Full</span>';
							} else {
								// not full waiting list, display join button
								$output.= '<span class="button_container" data-id="triggerRegForm">Join&nbsp;Waitlist</span>';
							}

						} else {

							// songcircle not full, waiting list false
							$output.= '<input type="hidden" name="waiting_list" value="false">';

							// if $_SESSION data
							if( isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ){
								// check if user is already registered
								if( $this->isRegisteredUser($row['songcircle_id'], $_SESSION['user_id']) ){
									// allow user to unregister
									$output.= '<span class="button_container">Unregister</span>';
									/**
									* Code unregister sequence
									**/
								}

							}	// end of: is $_SESSION data

							// allow user to register
							$output.= '<span class="button_container" data-id="triggerRegForm">Register</span>';

						}

						$output.= '<input type="submit" class="hide">';

					}
					// if songcircle started
					elseif ( $row['songcircle_status'] == 1 ) {

						if( !isset($_SESSION['user_id']) || empty($_SESSION['user_id']) ){

							// display join button
							$output.= "<button id=\"divJoin".$row['songcircle_id']."\" class=\"join\" style=\"display: block\">";
							$output.= "<a href=\"start_call.php?songcircleid=".$row['songcircle_id']."\" target=\"new\">Join Now</a>";
							$output.= "</button>";

						} else {

							// display "Songcircle In Progress" button
							$output.= "<div><p>".$row['songcircle_name']." is in progress</p></div>";

						}

					}
					// if songcircle complete
					else
					{
						// show message here that songcircle has completed
						// display "Songcircle has Completed" button
						$output.= "<div><p>".$row['songcircle_name']." has completed</p></div>";
					}
					// $output.= '<h4>'.$count.'</h4>';

					$output.= '</td>';

					// participants table -- hidden
					$output.= '<td class="participantsTable">';
					$output.= '<table class="participantsTable">';
					if($participants = $this->fetchParticipantData($row['songcircle_id'])){
						foreach ($participants as $participant) {
							$output.= '<tr>';
							$output.= '<td><a href="profile.php?id='.$participant['user_id'].'">'.$participant['user_name'].'</a></td>';
							$output.= '<td>'.$this->formatTimezone($participant['timezone']).', '.$participant['country_name'].'</td>';
							$output.= '</tr>';
						}
					} else {
					// no participants
						$output.= '<td class="empty">No one has yet to register for this Songcircle</td>';
					}
					$output.= '</table>';
					$output.= '</td>';
					// end of participantsTable

				$output.= '</tr>';

				// increment counter +1
				$count++;

			} // end of: while( $row = $db->fetchArray($result) )



			$output.= '</table>';

			return $output;

		} // end of: if( $result = $db->query($sql) )
	}


	/**
	* Update user status from unconfirmed to confirmed
	*
	* Updated: 01/12/2016
	*
	* @param (string) an SQL table name
	* @param (string) a songcircle id
	* @param (int) a user id
	* @param (string) a verification_key
	* @return (bool) true if row affected
	*/
	public function confirmUserRegistration($table_name, $songcircle_id, $user_id, $verification_key){
		global $db;

		// if this is a waitlist confirmation
		if( $table_name == 'songcircle_wait_register'){
			$sql = "DELETE FROM songcircle_wait_register ";
			$sql.= "WHERE user_id = {$user_id} AND songcircle_id = '{$songcircle_id}' ";
			$sql.= "LIMIT 1";

			if($result = $db->query($sql)){
				// confirm affected rows:
				if ( mysqli_affected_rows($db->connection) > 0 ){
					// insert into songcircle_register
					$sql = "INSERT INTO songcircle_register ";
					$sql.= "(songcircle_id, user_id, confirm_status, verification_key) ";
					$sql.= "VALUES ('$songcircle_id', $user_id, 1, '$verification_key')";

					if($result = $db->query($sql)){
						// confirm affected rows:
						if ( mysqli_affected_rows($db->connection) > 0 ){
							return true;
						}
					}

				}
			}

		} else {
			$sql = "UPDATE songcircle_register ";
			$sql.= "SET confirm_status = 1, confirmation_key = NULL, verification_key = '{$verification_key}' ";
			$sql.= "WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";

			if($result = $db->query($sql)){
				// confirm affected rows:
				if ( mysqli_affected_rows($db->connection) > 0 ){
					return true;
				}
			}

		}

	}

	/**
	* Queries table songcircle_create for songcircles that are not completed
	*
	* @return (object) query result
	*/
	public function getIncompleteSongcircles(){
		global $db;
		// select all songcircles that status is not 5
		$sql = "SELECT * FROM songcircle_create WHERE songcircle_status <> 5";
		if($result = $db->query($sql)){
			if($rows = mysqli_num_rows($result) > 0){
				return $result;
			}
		}
	}

	/**
	* Updates state of Songcircle ID
	*
	* @param (string) a songcircle_id
	* @param (int) a status state
	* @return (bool) true on successful update
	*/
	public function updateSongcircleState($songcircle_id, $int_status){
		global $db;
		$sql = "UPDATE songcircle_create SET songcircle_status = $int_status WHERE songcircle_id = '$songcircle_id'";
		if($result = $db->query($sql)){
			// check for affected rows
			if(mysqli_affected_rows($db->connection) > 0){
				// write to log
				file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").' Status Changed -- '.$songcircle_id.' => status: '.$int_status.PHP_EOL,FILE_APPEND);
				return true;
			}
		}
	}

	/**
	* Checks for match between a database-stored
	* verification key and a user provided one
	*
	* Created: 03/15/2016
	*
	* @param (string) a songcircle_id
	* @param (int) a user id
	* @param (string) a verification key
	* @return (bool) true on verification
	*/
	public function verifyKeys($songcircle_id,$user_id,$verification_key){
		global $db;
		// retrieve database-stored verification key by user id
		$sql = "SELECT verification_key ";
		$sql.= "FROM songcircle_register ";
		$sql.= "WHERE songcircle_id = '{$songcircle_id}' ";
		$sql.= "AND user_id = {$user_id}";
		// if rows
		if($database_key = $db->getRows($sql)){
			// return result from compare keys
			return $db->strIsExact($verification_key,$database_key[0]['verification_key']);
		}
	}

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
			if( ($row = $db->hasRows($result)) == 0 ){
				// no songcircle is present
				return true;
			}
			// else if there is ONE present
			elseif ( ($row = $db->hasRows($result)) == 1 ) {
				$data = $db->fetchArray($result);

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

			} // end of: if ($row = $db->hasRows($result)) == 0 )
		} // end of: if($result = $db->query($sql))
	} // end of: function isNotOpenSongcircle

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

		// if there is NO Open Songcircle Scheduled
		if(!$last_scheduled_time){
			// get the current time NOW
			$now = new DateTime(NULL, new DateTimeZone('UTC'));
			/**
			* NOTE: set global start time here
			*/
			$now->setTime(19,00,00);
			// add 1 week time
			$now->modify('+1 week');
			// assign reference variable
			$dt =& $now;
		} else {
			// use last scheduled time and add 1 week to it
			$dt = new DateTime($last_scheduled_time, new DateTimeZone("UTC"));
			$dt->modify('+1 week');
		}
		// format DateTime object and collect in variable
		$date_of_songcircle = $dt->format('Y-m-d\TH:i:00');
		// create new songcircle id
		$songcircle_id = uniqid('');

		// Insert new Open Songcircle
		$sql = "INSERT INTO songcircle_create (";
		$sql.= "songcircle_id, created_by_id, songcircle_name, date_of_songcircle, duration, max_participants";
		$sql.= ") VALUES (";
		$sql.= "'$songcircle_id', $this->global_user_id, '$this->songcircle_name', '$date_of_songcircle', '$this->duration_of_songcircle', '$this->max_participants')";

		if($result = $db->query($sql)){
			// check if affected rows
			while(mysqli_affected_rows($db->connection) > 0){

				// construct log text
				$log_text = ' Created: UTC --'.$date_of_songcircle.' '.$this->songcircle_name.' ('.$songcircle_id.') -- created by: '.$this->global_user_id;
				// write to log file
				file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+4 hours')).$log_text.PHP_EOL,FILE_APPEND);

				return true;
			}
		}
	} // end of: function createNewOpenSongcircle


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
			$count = $db->fetchArray($result);
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
			$count = $db->fetchArray($result);
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
			$count = $db->fetchArray($result);
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
			if($db->hasRows($result)){
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
				while( $row = $db->fetchArray($result) ){
					$user_id = $row['user_id'];
					$sql = "SELECT user_register.user_id, user_name, user_timezone.timezone, country_name FROM user_register, user_timezone WHERE user_register.user_id = {$user_id} AND user_timezone.user_id = {$user_id}";
					if($res = $db->query($sql)){
						$returnData[] = $db->fetchArray($res);
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
		return $date->format('l, \\<\\s\\p\\a\\n\\ \\c\\l\\a\\s\\s\\=\\"\\s\\e\\l\\e\\c\\t\\e\\d\ \\b\\l\\u\\e">F jS\\<\\/\\s\\p\\a\\n\\>, Y - \\<\\b\\r\\> g:i A T');
	}

	/**
	* Create a Month/Day format
	*
	* @param (datetime) date of songcircle
	*	@return (string) a formatted date
	*/
	protected function createMonthDate($date_of_songcircle){
		$date = new DateTime($date_of_songcircle, new DateTimeZone('UTC'));
		return $date->format('M j');
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
	* Checks by user id if user is already registered for a given songcircle by songcircle_id
	* (referenced in includes/songcircleRegisterUser.php)
	*
	* @param (string) songcircle_id
	* @param (int) user_id
	* @return (bool) returns true if user IS already registered
	*/
	public function userAlreadyRegistered($songcircle_id, $user_id){
		global $db;
		$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		if($result = $db->query($sql)){
			$rows = $db->hasRows($result);
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
	* An assistive function that laterally calls userTimezone function
	*
	* @param (datetime) a songcircle datetime
	* @param (name constant) php timezone name
	* @return a formatted datetime according to the user's timezone
	*/
	public function callUserTimezone($date_of_songcircle, $timezone) {
		$this->user_timezone = $timezone;
		return $this->userTimezone($date_of_songcircle);
	}

	/**
	* Private function - takes a datetime string (in UTC)
	* and converts it to a users timezone
	*
	* @param datetime a datetime description of a date
	*	@return string formatted date with user timezone
	*/
	protected function userTimezone($date_of_songcircle){
		$date = new DateTime($date_of_songcircle, new DateTimeZone('UTC'));
		$timezone = new DateTimeZone($this->user_timezone);
		$date->setTimezone($timezone);
		// debug_backtrace tells the last called function
		// depending on the function, display time format accordingly
		$callMethod = debug_backtrace();
		if (isset($callMethod[1]['function']) && $callMethod[1]['function'] == 'displaySongcircles')
		{
			return $date->format('l, F jS, Y - \\<\\b\\r\\> g:i A T');
		} else {
			return $date->format('l, F jS, Y - g:i A T');
		}
	}

	/**
	* Unregisters a user from a given songcircle
	*
	* @param (string) a songcircle id
	*	@param (int) a user id
	* @param (string) table name
	* @return (bool) true on successful unregister
	*/
	public function unregisterUserFromSongcircle($songcircle_id, $user_id, $table_name){
		global $db;

		$sql = "SELECT id FROM ".$table_name." ";
		$sql.= "WHERE songcircle_id = '$songcircle_id' ";
		$sql.= "AND user_id = $user_id LIMIT 1";

		if( $result = $db->query($sql) ){
			// if there are rows
			if( $row = $db->hasRows($result) > 0 ){
				$data = $db->fetchArray($result);
				// fetch the id of the row
				$id = $data['id'];
				// Perform row deletion
				$sql = "DELETE FROM ".$table_name." WHERE id = $id LIMIT 1";
				if($result = $db->query($sql)){
					return true;
				}
			}
		}
	}

	/**
	*	Checks for presence of waitlist registrant by songcircle id
	*
	* @param (string) songcircle_id
	* @return (array) waitlist array on success
	* @return (bool) false if waitlist is empty
	*/
	public function getWaitlist($songcircle_id){
		global $db;

		// get all waitlist registrants for given songcircle id
		$sql = "SELECT * FROM songcircle_wait_register ";
		$sql.= "WHERE songcircle_id = '$songcircle_id'";

		// if rows exist
		if( $row = $db->getRows($sql) ){

			foreach ($row as $key) {

				$user_id = $key['user_id'];
				$confirmation_key = $key['confirmation_key'];

				// confirm existence in database
				$sql = "SELECT ur.user_id, user_name, user_email, ut.timezone ";
				$sql.= "FROM user_register AS ur, user_timezone AS ut ";
				$sql.= "WHERE ur.user_id = {$user_id} AND ut.user_id = {$user_id}";

				// if successful in confirming presence of user id, email and timezone
				if( $user_data = $db->getRows($sql) ){
					// init. array and construct
					$waitlist_array = [];
					$waitlist_array['confirmation_key'] = $confirmation_key;

					foreach ($user_data as $key => $value) {
						$waitlist_array['user'] = $value;
					}
					$waitlist_array['songcircle'] = $this->songcircleDataByID($songcircle_id);
					// return the array
					return $waitlist_array;
					// if successful return, stop processing
					exit;
				}
			}
		}
	}

	/**
	* Get songcircle data by songcircle_id
	*
	* @param (string) songcircle_id
	*/
	public function songcircleDataByID($songcircle_id){
		global $db;
		$sql = "SELECT * FROM songcircle_create WHERE songcircle_id = '$songcircle_id'";
		if($result = $db->query($sql)){
			return $row = $db->fetchArray($result);
		} else {
			return false;
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
