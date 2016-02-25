<?php require_once('initialize.php');
/*
	This page will clear data from the database by email
*/
if(isset($_GET['user_email']) && !empty($_GET['user_email'])){

	// init error array
	$error_msg = [];
	// sanitize the data
	$user_email = $db->escape_value($_GET['user_email']);
	// verify IS valid email
	if(!$db->is_valid_email($user_email)){
		$error_msg[] = "The email provided is not a valid email. Unsubscribe failed.";
	} else {
		// query the database for rows
		// $sqlTrigger = "CREATE TRIGGER get_last_id BEFORE DELETE ON user_register"
		$sql = "SELECT user_id FROM user_register WHERE user_email = '{$user_email}'";
		if($result = $db->query($sql)){
			if($row = $db->has_rows($result)){
				if($data = $db->fetch_array($result)){
					$user_id = $data['user_id'];
					mysqli_free_result($result);

					// delete from tables anywhere the user id matches
					$sql = "DELETE user_register, user_timezone, songcircle_register FROM user_register INNER JOIN user_timezone INNER JOIN songcircle_register WHERE user_register.user_id = $user_id AND user_timezone.user_id = $user_id AND songcircle_register.user_id = $user_id";
					if($result = $db->query($sql)){
						$success_msg = 'You have successfully been unsubscribed from Songfarm';
					} else {
						$error_msg[] = 'There was an error unsubscribing you. Please contact support at support@songfarm.ca';
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
<?php	include('layout/confirmationTemplate.php');

	/* above include reads success or error messages and displays them here */

?>
