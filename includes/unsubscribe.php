<?php require_once('initialize.php');
/**
* Removes a user from the site by email address
*/
if(isset($_GET['user_key']) && !empty($_GET['user_key'])){
	// init error array
	$error_msg = [];
	// sanitize the data
	$user_key = $db->escapeValue($_GET['user_key']);
	$sql = "SELECT user_id FROM user_register WHERE user_key = '{$user_key}' LIMIT 1";
	if($result = $db->query($sql)){
		if($row = $db->hasRows($result)){
			if($data = $db->fetchArray($result)){
				// get user_id
				$user_id = $data['user_id'];
				// remove user from the system
				if($user->removeUserFromSongfarm($user_id)){
				// user successfully removed
					// construct unsubscribe text
					$log_text = ' Unsubscribed -- user_id: '.$user_id; //.'; email: '.$user_email
					// write to USER log
					file_put_contents(SITE_ROOT.'/logs/user_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
					$success_msg[] = "You have been successfully unsubscribed from Songfarm.<br>";
					$success_msg[] = "We're sorry to see you go but thanks for checking us out!";
				}	else {
					$error_msg[] = "An error when we were trying to unsubscribe you from the site.";
					$error_msg[] = "Our team is looking into it and will contact you to confirm your unsubscription.";
					$error_msg[] = "Feel free to contact support at <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a>";
					$error_msg[] = "Our apologies for the inconvenience.";
				}
			} else {
				$error_msg[] = "No data found.";
			}
		} else {
			$error_msg[] = "No records exist for this account.";
		}
	} // end of: if($result = $db->query($sql))
	else
	{
		$error_msg[] = 'No record exists for that email.';
	}
} else {
	redirectTo('../public/index.php');
}
?>
<?php	include('layout/confirmation_page.php'); ?>
