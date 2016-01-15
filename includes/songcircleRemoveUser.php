<?php require_once('initialize.php');
/**
* Receives, processes and removes user from given songcircle
*
* Created: 01/12/2016
*/
if(	( isset($_GET['conference_id']) && !empty($_GET['conference_id']) ) &&
		( isset($_GET['user_email']) && !empty($_GET['user_email']) ) )
		{
			/* sanitize values */

			$songcircle_id = $db->escape_value($_GET['conference_id']);

			$user_email = $db->escape_value($_GET['user_email']);
			// check if NOT valid email
			if(!$user_email = $db->is_valid_email($user_email)){
				// redirect to generic error page.
				$error_msg[] = 'Invalid email sent with request. Failed to unregister user.';
				exit;
			}

			// if NOT user_id for given email
			if(!$user_id = $db->getIDbyEmail($user_email)){
				$error_msg[] = 'No user exists for the given email';
				exit;
			} else {

				/*
				* If user is already confirmed then they cannot unregister with this process
				*/

				// confirm selection
				$sql = "SELECT count(*) FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
				if(!$result = $db->query($sql)){
					exit;
				} else {
					if($row = $db->has_rows($result) == 1){
						// remove record from database
						$sql = "DELETE FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
						if($result = $db->query($sql)){
							// confirm affected rows
							if ( $db->hasAffectedRows() ){
								// success
								$success_msg = 'Record successfully deleted. <br><br>Redirecting now...';
							}
						} // end of: if($result = $db->query($sql))
					} // end of: if($row = $db->has_rows($result) == 1)
				}
			} // end of: if(!$user_id = $db->getIDbyEmail($user_email))
		} // end of: isset($_GET['conference_id'])... && isset($_GET['user_email'])...
?>
<?php include('layout/confirmationTemplate.php'); ?>
