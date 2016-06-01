<?php require_once('initialize.php');
require_once(EMAIL_PATH.DS.'email_data.php');
// if request comes from a remote address
if(isset($_SERVER['REMOTE_ADDR'])){
	die('Permission Denied');
} else {
	if(isset($argv)){

		$cronlog_location 	= SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt';
		$errorLog_location = SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt';
		// $log_time = date("H:i:s", strtotime('4 hours')); // converts detroit time to UTC
		$log_time = date("H:i:s");

		foreach ($argv as $arg) {
			switch ($arg) {

			/**
			* Checks state of Songcircle and updates it where required
			*
			* NOTE: What is the optimal frequency of this?
			*
			* NOTE: Status STARTED should be active 15 minutes prior to scheduled start time
			* to allow users to connect early.
			* *** A pending message should be visible to users connected to the call
			*/
			case 'songcircle_state':
					// log call
					file_put_contents($cronlog_location,$log_time.' songcircle_state'.PHP_EOL,FILE_APPEND);

					// if Incomplete Songcircles Exist (Not Started OR Started)
					if($result = $songcircle->getIncompleteSongcircles()){

						foreach ($result as $songcircle_result) {

							$songcircle_id = $songcircle_result['songcircle_id'];
							// date of songcircle in minutes (in UTC)
							$date_of_songcircle = floor( (strtotime($songcircle_result['date_of_songcircle']) / 60) );
							// duration of songcircle in minutes
							$duration_of_songcircle = ( strtotime($songcircle_result['duration']) - strtotime('today') ) / 60;
							// current time plus 4 hours (for UTC)
							$currentUTCTime = date("H:i:s");
							// current time in minutes
							$currentUTCTime = floor( strtotime($currentUTCTime) / 60 );
							// get difference in MINUTES between now and $date_of_songcircle
							$diff_in_minutes = floor( $currentUTCTime - ($date_of_songcircle - 240) ); /* 240 = 4 hours */
							/*
							* NOTE: because server time is 4 hours behind we must subtract four hours from start of songcircle
							* Adding 4 hours to currentUTCTime does not work as intended
							*/

						  // if Songcircle is 15 minutes until start && has not finished && current status isn't already set to 1
							if ( ($diff_in_minutes >= -15) && ($diff_in_minutes < $duration_of_songcircle)
								&& ($songcircle_result['songcircle_status'] != 1)
								){

								// set status to 1 (started)
								$songcircle_status = 1;
								$songcircle->updateSongcircleState($songcircle_id,$songcircle_status);

								file_put_contents($cronlog_location,$log_time.' -- UPDATED_STATUS - '.$songcircle_id.' Status '.$songcircle_status.' (diff: '.$diff_in_minutes.' / duration: '.$duration_of_songcircle.')'.PHP_EOL,FILE_APPEND);

							}
							// if Songcircle has ended AND current status isn't already set to 5
							elseif ( ($diff_in_minutes >= $duration_of_songcircle) && ($songcircle_result['songcircle_status'] != 5 ) ){

								// set status to 5 (complete)
								$songcircle_status = 5;
								$songcircle->updateSongcircleState($songcircle_id,$songcircle_status);

								file_put_contents($cronlog_location,$log_time.' -- UPDATED_STATUS - '.$songcircle_id.' Status '.$songcircle_status.' (diff: '.$diff_in_minutes.' / duration: '.$duration_of_songcircle.')'.PHP_EOL,FILE_APPEND);

							}
							else
							{
								file_put_contents($cronlog_location,$log_time.' -- no action - '.$songcircle_id.' (diff: '.$diff_in_minutes.' / duration: '.$duration_of_songcircle.')'.PHP_EOL, FILE_APPEND);
							}
						} // end of: foreach ($result as $songcircle_result)
					} // end of: if($result = $songcircle->getIncompleteSongcircles())
					else
					{
						file_put_contents($cronlog_location,$log_time.' -- no songcircles found'.PHP_EOL,FILE_APPEND);
					}
						break;

			/**
			* Sends reminder email 3 DAYS before event
			*/
			case 'first_reminder_songcircle':

				// write to log
				file_put_contents($cronlog_location,$log_time.' first_reminder_songcircle'.PHP_EOL,FILE_APPEND);

				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, ur.user_name, user_email,
								ut.full_timezone, TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) AS diff
								FROM songcircle_create AS sc,	songcircle_register AS sr
								INNER JOIN user_register AS ur ON sr.user_id = ur.user_id
								INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id
								WHERE sc.songcircle_id = sr.songcircle_id
								AND sc.songcircle_status = 0
								AND sr.confirm_status = 1
								AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) > 4306
								AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) < 4321";

				if($result = $db->getRows($sql)){

					// foreach result/user
					foreach($result as $songcircle_user_data){

						// query for user information
						if( $user->setUserData($songcircle_user_data['user_id']) ){

							// convert time of songcircle to user timezone
							$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($songcircle_user_data['date_of_songcircle'],$user->timezone);

							// construct email
							$to = "{$user->username} <{$user->user_email}>";
							$subject = "3 days until ".$songcircle_user_data['songcircle_name']."!";
							$from = "Songfarm <noreply@songfarm.ca>";
							if($message = initiateEmail($email_data['first_reminder'],$songcircle_user_data)){
								$headers = "From: {$from}\r\n";
								$headers.= "Content-Type: text/html; charset=utf-8";

								// if mail successful
								if( mail($to,$subject,$message,$headers,'-fsongfarm') ){

									file_put_contents($cronlog_location, $log_time.' -- Email Sent: User '.$songcircle_user_data['user_id'].', '.$songcircle_user_data['songcircle_id'].PHP_EOL,FILE_APPEND);

								}
								else
								{
									// could not send email
										// log error
										file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not send email ('.$_SERVER['PHP_SELF'].__LINE__.') to '.$songcircle_user_data['user_id'].' for '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') on '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
								}

							} // end of: if($message = initiateEmail($email_data['reminder_email'],$songcircle_user_data))
							else
							{
								// could not construct email
									// log error
									file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not construct email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							}

						} // end of: if($user->setUserData($songcircle_user_data['user_id']))
						else
						{
							// No user information available for given user id
								// log error
								file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: No user information available for user id: '.$songcircle_user_data['user_id']. '('.$_SERVER['PHP_SELF'].__LINE__.') '.PHP_EOL,FILE_APPEND);
						}
					} // end of: foreach($result as $row)

				} // end of: if($result = $db->getRows($sql))
				else
				{
					// write to log
					file_put_contents($cronlog_location,$log_time.' -- no action'.PHP_EOL,FILE_APPEND);
				}
					break;

			/**
			* Sends reminder email 1 DAY before event
			*/
			case 'second_reminder_songcircle':

				// write to log
				file_put_contents($cronlog_location,$log_time.' second_reminder_songcircle'.PHP_EOL,FILE_APPEND);

				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, ur.user_name, user_email,
								ut.full_timezone, TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) AS diff
								FROM songcircle_create AS sc,	songcircle_register AS sr
								INNER JOIN user_register AS ur ON sr.user_id = ur.user_id
								INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id
								WHERE sc.songcircle_id = sr.songcircle_id
								AND sc.songcircle_status = 0
								AND sr.confirm_status = 1
								AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) > 1411
								AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) < 1441";

				if($result = $db->getRows($sql)){

					// foreach result/user
					foreach($result as $songcircle_user_data){

						// query for user information
						if( $user->setUserData($songcircle_user_data['user_id']) ){

							// convert time of songcircle to user timezone
							$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($songcircle_user_data['date_of_songcircle'],$user->timezone);

							// construct email
							$to = "{$user->username} <{$user->user_email}>";
							$subject = $songcircle_user_data['songcircle_name']." is tomorrow!";
							$from = "Songfarm <noreply@songfarm.ca>";
							if($message = initiateEmail($email_data['second_reminder'],$songcircle_user_data)){
								$headers = "From: {$from}\r\n";
								$headers.= "Content-Type: text/html; charset=utf-8";

								// if mail successful
								if( mail($to,$subject,$message,$headers,'-fsongfarm') ){

									file_put_contents($cronlog_location, $log_time.' -- Email Sent: User '.$songcircle_user_data['user_id'].', '.$songcircle_user_data['songcircle_id'].PHP_EOL,FILE_APPEND);

								}
								else
								{
									// could not send email
										// log error
										file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not send email ('.$_SERVER['PHP_SELF'].__LINE__.') to '.$songcircle_user_data['user_id'].' for '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') on '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
								}

							} // end of: if($message = initiateEmail($email_data['reminder_email'],$songcircle_user_data))
							else
							{
								// could not construct email
									// log error
									file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not construct email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							}

						} // end of: if($user->setUserData($songcircle_user_data['user_id']))
						else
						{
							// No user information available for given user id
								// log error
								file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: No user information available for user id: '.$songcircle_user_data['user_id']. '('.$_SERVER['PHP_SELF'].__LINE__.') '.PHP_EOL,FILE_APPEND);
						}
					} // end of: foreach($result as $row)

				} // end of: if($result = $db->getRows($sql))
				else
				{
					// write to log
					file_put_contents($cronlog_location,$log_time.' -- no action'.PHP_EOL,FILE_APPEND);
				}
					break;

			/**
			* Sends join link 15 MIN before event
			*/
			case 'join_songcircle':

				// write to log
				file_put_contents($cronlog_location,$log_time.' join_songcircle'.PHP_EOL,FILE_APPEND);

				$sql="SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, verification_key,
							confirm_status, ur.user_name, user_email, ut.full_timezone, TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(),   @@global.time_zone, '+0:00' ), date_of_songcircle ) AS diff
							FROM songcircle_create AS sc, songcircle_register AS sr
							INNER JOIN user_register AS ur ON sr.user_id = ur.user_id
							INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id
							WHERE sc.songcircle_id = sr.songcircle_id
							-- AND sc.songcircle_status = 0
							AND sr.confirm_status = 1
							-- get songcircles where difference between now (UTC) and date of songcircle (also UTC) is 1 hour
							AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) > 0
							AND TIMESTAMPDIFF( MINUTE, CONVERT_TZ( now(), @@global.time_zone, '+0:00' ), date_of_songcircle ) < 15";

				if($result = $db->getRows($sql)){

					foreach($result as $songcircle_user_data){

						file_put_contents($cronlog_location, $log_time.' -- Join link sent - User '.$songcircle_user_data['user_id'].'  verification_key: '.$songcircle_user_data['verification_key'].PHP_EOL,FILE_APPEND);

						// construct email
						$to = "{$songcircle_user_data['user_name']} <{$songcircle_user_data['user_email']}>";
						$subject = $songcircle_user_data['songcircle_name']." is starting!";
						$from = "Songfarm <noreply@songfarm.ca>";
						if( $message = initiateEmail($email_data['join_songcircle'],$songcircle_user_data) ){
							$headers = "From: {$from}\r\n";
							$headers.= "Content-Type: text/html; charset=utf-8";
							if( mail($to,$subject,$message,$headers,'-fsongfarm') )
							{
								file_put_contents($cronlog_location,$log_time.' -- Join link sent - User '.$songcircle_user_data['user_id'].', '.$songcircle_user_data['songcircle_id'].' diff: '.$songcircle_user_data['diff'].PHP_EOL,FILE_APPEND);
							}
							else
							{
								// could not send email
								file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not send email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							}

						} // end of: if($message = initiateEmail())
						else
						{
							// could not construct email
							file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: Could not construct email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
						}

					} // end of: foreach($result as $row)
				} // end of: if($result = $db->getRows($sql))
				else
				{
					// write to log
					file_put_contents($cronlog_location,$log_time.' -- no action'.PHP_EOL,FILE_APPEND);
				}
					break;

			/**
			* Clears completed/expired songcircles
			*/
			case 'clear_expired_songcircle':

				// write to log
				file_put_contents($cronlog_location, $log_time.' clear_expired_songcircle'.PHP_EOL,FILE_APPEND);

				// if songcircle start time + duration of songcircle is less than current time (in UTC)
				$sql = "SELECT id, songcircle_id, date_of_songcircle ";
				$sql.= "FROM songcircle_create ";
				$sql.= "WHERE DATE_ADD(date_of_songcircle, INTERVAL duration HOUR_SECOND) < DATE_ADD(NOW(), INTERVAL '4' HOUR)";

				if($result = $db->getRows($sql)){

					foreach ($result as $songcircle) {

						// get id for expired
						$id = $songcircle['id'];
						$songcircle_id = $songcircle['songcircle_id'];

						// delete record by id
						$sql = "DELETE FROM songcircle_create WHERE id = $id LIMIT 1";
						if($result = $db->query($sql)){
							if(mysqli_affected_rows($db->connection) > 0){
								// if successful delete, write to log
								file_put_contents($cronlog_location,$log_time.' -- REMOVED_EXPIRED: '.$songcircle_id.' '.$songcircle['date_of_songcircle'].PHP_EOL, FILE_APPEND);
							} else {
								// failed to delete record
								// log error
								file_put_contents($errorLog_location,$log_time.' '.error_get_last().' -- FAILED: No record deleted ('.$_SERVER['PHP_SELF'].__LINE__.') id: '.$id.', songcircle_id: '.$songcircle_id.PHP_EOL,FILE_APPEND);
							}
						} // end of: if($result = $db->query($sql))
					} // end of: foreach ($result as $songcircle)
				}
				else
				{
					// if NO result, write to log
					file_put_contents($cronlog_location,$log_time.' -- no action'.PHP_EOL,FILE_APPEND);
				}
					break;


			} // end of: switch ($arg)

		} // end of: foreach ($argv as $arg)
	} // end of: if(isset($argv)
}
