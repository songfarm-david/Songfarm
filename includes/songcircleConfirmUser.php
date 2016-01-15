<?php require_once('initialize.php');
/**
* Receives and processes confirmation link from user
*
* Updated: 01/12/2016
*
*/
if(	( isset($_GET['conference_id']) && !empty($_GET['conference_id']) ) &&
		( isset($_GET['user_email']) && !empty($_GET['user_email']) ) &&
		( isset($_GET['confirmation_key']) && !empty($_GET['confirmation_key']) )	)
	{

	// necesary data has been provided in the $_GET request

	/* sanitize values */

	$songcircle_id = $db->escape_value($_GET['conference_id']);

	$user_email = $db->escape_value($_GET['user_email']);
	// check if NOT valid email
	if(!$user_email = $db->is_valid_email($user_email)){
		// redirect to generic error page.
		$error_msg[] = 'Invalid email sent with request. User confirmation failed';
		exit;
	}

	$confirmation_key = (string) $db->escape_value($_GET['confirmation_key']);

	/* values sanitized */

	// if NOT user_id for given email
	if(!$user_id = $db->getIDbyEmail($user_email)){
		$error_msg[] = 'No user exists for the given email';
		exit;
	} else {
		// user id exists for given email

		// Select all data where songcircle_id and user_id match
		$sql = "SELECT * FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
		if(!$result = $db->query($sql)){
			$error_msg[] = 'Database query failed';
			exit;
		} else {
			// fetch array from the result
			$user_set = $db->fetch_array($result);
			// retrieve confirmation key from database result
			if( isset($user_set['confirmation_key']) && !empty($user_set['confirmation_key']) ){
				// assign confirmation key to variable
				$user_key = (string) $user_set['confirmation_key'];
			} else {
				$error_msg[] = 'User is not registered for given songcircle';
			}
		}

		// compare confirmation key from email with database retrieved user key
		if( $confirmation_key !== $user_key ){
			$error_msg[] = 'Invalid confirmation key provided';
			exit;
		} else {
			// keys do match
			// attempt to update user status for songcircle
			if($songcircle->updateUserRegistration($songcircle_id, $user_id)){
				// update successful
				$success_msg = 'Thank you. Confirmation successful. <br><br>Redirecting now...';
			} else {
				$error_msg[] = 'Error: user confirmation update failed';
			}

		}

	}

} // end of: if isset($_GET) and !empty($_GET)..

/*
* NOTES:
* http vs https?
* .txt and .html versions of email
*
* how to avoid the junk mail folder
*	set activation expires after songcircle has expired
*/



?>
<?php include('layout/confirmationTemplate.php'); ?>
