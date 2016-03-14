<?php require_once('initialize.php');
/**
* NOTE: this function has been relocated to songcircle_user_action
*/

if( (isset($GET['songcircle_id']) && !empty($GET['songcircle_id'])) &&
		(isset($GET['user_id']) && !empty($GET['user_id']))
	)
	{

		$songcircle_id = $db->escapeValue($GET['songcircle_id']);
		$user_id = $db->escapeValue($GET['user_id']);

	if($songcircle->unregisterUserFromSongcircle($songcircle_id,$user_id)){
		// construct log text
		$log_text = 'Unregister-- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
		// write to log
		file_put_contents('../logs/user_songcircle.txt',$log_text,FILE_APPEND);
		// create success message
		$success_msg = "You have been successfully unregistered from this Songcircle";

		if($waitlist_data = $songcircle->waitlist($songcircle_id)){

			// put user data into variables
			$songcircle_user_data['user_id'] = $waitlist_data['user']['user_id'];
			$songcircle_user_data['confirm_status'] = $waitlist_data['user']['confirm_status'];
			$songcircle_user_data['confirmation_key'] = $waitlist_data['user']['confirmation_key'];
			// put songcircle data into variables
			$songcircle_user_data['songcircle_id'] = $waitlist_data['songcircle']['songcircle_id'];
			$songcircle_user_data['songcircle_name'] = $waitlist_data['songcircle']['songcircle_name'];
			// get the date of the songcircle in UTC
			$date_of_songcircle = $waitlist_data['songcircle']['date_of_songcircle'];

			// get user data by user id
			if($user->retrieveUserData($songcircle_user_data['user_id'])){
				// set user data variables
				// NOTE: calling retrieveUserData() sets various class variables
				$songcircle_user_data['username'] = $user->username;
				$songcircle_user_data['user_email'] = $user->user_email;
				// get date_of_songcircle by user timezone
				$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($date_of_songcircle,$user->timezone);

				// construct email
				if(constructHTMLEmail($email_data['confirm_waitlist'], $songcircle_user_data)){
					echo 'success';
				} else {
					echo 'constructHTMLEmail failed';
				}
			} else {
				echo 'Error retrieving user data';
			}
		} // end of: if($waitlist_data)

	} else {
		$error_msg[] = "There was an error unregistering you from this event. Please contact <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a> to resolve this.";
	}
}


?>
