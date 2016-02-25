<?php require_once('initialize.php');
/**
* Receives and processes confirmation email link from user
* after registration for a given songcircle occurs
*
* Updated: 01/29/2016
*
*/
if(	( isset($_GET['conference_id']) && !empty($_GET['conference_id']) ) &&
		( isset($_GET['user_email']) && !empty($_GET['user_email']) ) &&
		( isset($_GET['confirmation_key']) && !empty($_GET['confirmation_key']) )	)
	{

	$error_msg = array();
	$success_msg = '';
	// necesary data has been provided in the $_GET request

	/* sanitize values */
	$songcircle_id = $db->escape_value($_GET['conference_id']);
	if(strlen($songcircle_id) != 13){
		$error_msg[] = 'Invalid conference id';
		$songcircle_id = false;
	}
	$user_email = $db->escape_value($_GET['user_email']);
	// check if NOT valid email
	if(!$user_email = $db->is_valid_email($user_email)){
		// redirect to generic error page.
		$error_msg[] = 'Invalid email sent with request. User confirmation failed.';
		$user_email = false;
	}
	$confirmation_key = (string) $db->escape_value($_GET['confirmation_key']);
	if(strlen($confirmation_key) != 40){
		$error_msg[] = 'Invalid confirmation key';
		$confirmation_key = false;
	}

	/* values sanitized */
	if($songcircle_id && $user_email && $confirmation_key){
	// all values have been accounted for and sanitized
		// get start time of songcircle
		$sql = "SELECT UNIX_TIMESTAMP(date_of_songcircle) FROM songcircle_create WHERE songcircle_id = '$songcircle_id'";
		if($result = $db->query($sql)){
			// fetch data array
			$songcircle_data = $db->fetch_array($result);
			// capture unix formatted start time in variable
			$start_time = $songcircle_data['UNIX_TIMESTAMP(date_of_songcircle)'];
			if(empty($start_time)){
				$error_msg[] = 'Could not acquire start time of Songcircle.';
			} else {
				$current_time = time();
				// if the link is expired
				if($songcircle->isExpiredLink($current_time,$start_time)){
					$error_msg[] = 'The confirmation period for this songcircle has expired.';
					// maybe remove user here too
				} else {
				// if the link is not expired
					// if NOT user_id for given email
					if(!$user_id = $db->getIDbyEmail($user_email)){
						$error_msg[] = 'No user exists for the email provided.';
					} else {
						// user id exists for given email
						// Select all data where songcircle_id and user_id match
						$sql = "SELECT * FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
						if($result = $db->query($sql)){
							// if rows
							if($row = $db->has_rows($result)){
								// if row
								$user_array = $db->fetch_array($result);
								if( isset($user_array['confirmation_key']) && !empty($user_array['confirmation_key']) ){
									// assign confirmation key to variable
									$user_key = (string) $user_array['confirmation_key'];
									//compare confirmation key from email with database retrieved user key
									if( $confirmation_key !== $user_key ){
										$error_msg[] = 'Invalid confirmation key provided.';
									} else {
										// keys do match
										// attempt to update user status for songcircle
										if($songcircle->confirmUserRegistration($songcircle_id, $user_id)){

											// construct log text
											$log_text = 'Confirm--- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
											// write to log
											file_put_contents('../logs/user_songcircle.txt',$log_text,FILE_APPEND);

											// update successful
											$error_msg = false;
											$success_msg = 'Thank you. Confirmation successful. <br><br>Redirecting now...';

										} else {
											$error_msg[] = 'Error: user confirmation update failed.';
										}
									}
								} else {
									$error_msg[] = 'User is already registered for the given songcircle.';
								}
							} else {
								// no rows exist
								$error_msg[] = 'User has not registered.';
							}
						}
					}
				}
			}
		}
	} // end of: if($songcircle_id && $user_email && $confirmation_key)
} // end of: if isset($_GET) and !empty($_GET)..
else
{
	// redirect back to index.php
	redirect_to('../public/index.php');
}
?>
<?php	include('layout/confirmationTemplate.php');

	/* above include reads success or error messages and displays them here */

?>
