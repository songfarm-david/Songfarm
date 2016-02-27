<?php require_once('initialize.php');

if( (isset($GET['songcircle_id']) && !empty($GET['songcircle_id'])) &&
		(isset($GET['user_id']) && !empty($GET['user_id']))
	)
	{

		$songcircle_id = $db->escape_value($GET['songcircle_id']);
		$user_id = $db->escape_value($GET['user_id']);

	if($songcircle->unregisterUserFromSongcircle($songcircle_id,$user_id)){
		// construct log text
		$log_text = 'Unregister-- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
		// write to log
		file_put_contents('../logs/user_songcircle.txt',$log_text,FILE_APPEND);
		// create success message
		$success_msg = "You have been successfully unregistered from this Songcircle";
	} else {
		$error_msg[] = "There was an error unregistering you from this event. Please contact <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a> to resolve this.";
	}
}


?>
