<?php require_once('initialize.php');
/**
* Removes a user from the site by email address
*/
if(isset($_GET['user_email']) && !empty($_GET['user_email'])){
	// init error array
	$error_msg = [];
	// sanitize the data
	$user_email = $db->escapeValue($_GET['user_email']);
	// verify IS valid email
	if(!$db->isValidEmail($user_email)){
		$error_msg[] = "The email provided is not a valid email. Unsubscribe failed.";
	} else {
		// query the database for rows
		$sql = "SELECT user_id FROM user_register WHERE user_email = '{$user_email}' LIMIT 1";
		if($result = $db->query($sql)){
			if($row = $db->hasRows($result)){
				if($data = $db->fetchArray($result)){
					// get user_id
					$user_id = $data['user_id'];
					// remove user from the system
					if($user->removeUserFromSongfarm($user_id)){
					// user successfully removed
						// construct unsubscribe text
						$log_text = ' Unsubscribe -- user_id: '.$user_id.'; email: '.$user_email;
						// write to USER log
						file_put_contents(SITE_ROOT.'/logs/user_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
						$success_msg = "You have successfully been unsubscribed from Songfarm.<br>";
						$success_msg.= "We're sorry to see you go but thanks for checking us out!";
					}	else {
						$error_msg[] = "There was an error unsubscribing you. Please contact support at <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a>";
					}
				}
			}
		} // end of: if($result = $db->query($sql))
		else
		{
			$error_msg[] = 'No record exists for that email.';
		}
	}
}
?>
<?php	include('layout/confirmation_template.php');

	/* above include reads success or error messages and displays them here */

?>
