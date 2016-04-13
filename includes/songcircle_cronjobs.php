<?php require_once('initialize.php');
require_once(EMAIL_PATH.DS.'email_data.php');
// if request comes from a remote address
if(isset($_SERVER['REMOTE_ADDR'])){
	die('Permission Denied');
} else {
	// check for argument
	if(isset($argv)){
		foreach ($argv as $arg) {
			switch ($arg) {

			/**
			* Check State of Songcircle
			*
			* NOTE: Cron runs on 1,16,31,46 minute of every hour
			*/
				case 'songcircle_state':

					// log call
					file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' CALL: songcircle_state'.PHP_EOL,FILE_APPEND);

				// if Incomplete Songcircles Exist (Not Started OR Started)
				if($result = $songcircle->getIncompleteSongcircles()){

					// TEST:
					// if result, write to log
					// file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- returned result:'.PHP_EOL,FILE_APPEND);

					foreach ($result as $songcircle_result) {
						// get the songcircle_id
						$songcircle_id = $songcircle_result['songcircle_id'];
						// date of songcircle in minutes (in UTC)
						$date_of_songcircle = floor( strtotime($songcircle_result['date_of_songcircle']) / 60 );
						// duration of songcircle in minutes
						$duration_of_songcircle = ( strtotime($songcircle_result['duration']) - strtotime('today') ) / 60;
						// current time plus 5 hours (for UTC)
						$current_time = date("G:i:s",strtotime('+5 hours'));
						// current time in minutes
						$current_time = floor( strtotime($current_time) / 60);
						// get different in MINUTES between now and $date_of_songcircle
						$diff_in_minutes = floor( ($current_time - $date_of_songcircle) );

						// TEST:
						// if result, write to log
						// file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- songcircle_id: '.$songcircle_id.PHP_EOL,FILE_APPEND);

					  // if Songcircle has started and hasn't finished yet AND current status isn't already set to 1
						if ( ($diff_in_minutes >= 0) && ($diff_in_minutes < $duration_of_songcircle)
							&& ($songcircle_result['songcircle_status'] != 1) ){

							// set status to 1 (started)
							$songcircle_status = 1;
							// update status of songcircle
							$songcircle->updateSongcircleState($songcircle_id,$songcircle_status);

							// write to log
							file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- UPDATE_STATUS: songcircle_id: '.$songcircle_id.' (songcircle_status: '.$songcircle_status.')'.PHP_EOL,FILE_APPEND);

						}
						// if Songcircle has ended AND current status isn't already set to 5
						elseif ( ($diff_in_minutes > $duration_of_songcircle) && ($songcircle_result['songcircle_status'] != 5 ) ){

							// set status to 5 (complete)
							$songcircle_status = 5;
							// update status of songcircle
							$songcircle->updateSongcircleState($songcircle_id,$songcircle_status);

							// write to log
							file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- UPDATE_STATUS: songcircle_id: '.$songcircle_id.' (songcircle_status: '.$songcircle_status.')'.PHP_EOL,FILE_APPEND);

						}
					} // end of: foreach ($result as $songcircle_result)
				} // end of: if($result = $songcircle->getIncompleteSongcircles())
				else {

					// no action, write to log
					file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- no action'.PHP_EOL,FILE_APPEND);

				}
					break;

			/**
			* Sends reminder email, RELATIVE TO USER TIMEZONE, 3 days out from event
			*
			* NOTE: Cron runs every 15 minutes on the 5
			*/
				case 'reminder_songcircle':

				// write to log
				file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' CALL: reminder_songcircle'.PHP_EOL,FILE_APPEND);

				// query database
// 				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, ur.user_name, user_email, SUBSTR(ut.full_timezone,5,6) AS user_timezone ";
// 				$sql.= "FROM songcircle_create AS sc, songcircle_register AS sr ";
// 				$sql.= "INNER JOIN user_register AS ur ON sr.user_id = ur.user_id ";
// 				$sql.= "INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id ";
// 				$sql.= "WHERE sc.songcircle_id = sr.songcircle_id ";
// 				$sql.= "AND sc.songcircle_status = 0 ";
// 				$sql.= "AND sr.confirm_status = 1";

				
				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, ur.user_name, user_email, 
						SUBSTR(ut.full_timezone,5,6) AS user_timezone,
						convert_tz(date_of_songcircle, @@global.time_zone, '+0:00') as date_of_songcircle_UTC,
						convert_tz(now(), @@global.time_zone, '+0:00') as today_time_UTC,
						convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00') as user_time_UTC
						/*,TIMESTAMPDIFF( MINUTE, convert_tz(date_of_songcircle, @@global.time_zone, '+0:00'), convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00') ) */

						FROM songcircle_create AS sc, 
						songcircle_register AS sr 
						INNER JOIN user_register AS ur 
						ON sr.user_id = ur.user_id 
						INNER JOIN user_timezone AS ut 
						ON ur.user_id = ut.user_id 
						
						WHERE sc.songcircle_id = sr.songcircle_id AND sc.songcircle_status = 0 AND sr.confirm_status =1
						and TIMESTAMPDIFF( MINUTE, convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00')  , convert_tz(date_of_songcircle, @@global.time_zone, '+0:00')) > 4305 and
						TIMESTAMPDIFF( MINUTE, convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00')  , convert_tz(date_of_songcircle, @@global.time_zone, '+0:00')) < 4320"; 

				if($result = $db->getRows($sql)){

					// foreach result/user
					foreach($result as $songcircle_user_data){

						// query for user information
						if($user->setUserData($songcircle_user_data['user_id'])){

							// convert time of songcircle to user timezone
							$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($songcircle_user_data['date_of_songcircle'],$user->timezone);

							// construct email
							$to = "{$user->username} <{$user->user_email}>";
							$subject = $songcircle_user_data['songcircle_name']." is happening soon!";
							$from = "Songfarm <noreply@songfarm.ca>";
							if($message = constructHTMLEmail($email_data['first_reminder'],$songcircle_user_data)){
								$headers = "From: {$from}\r\n";
								$headers.= "Content-Type: text/html; charset=utf-8";

								// if mail successful
								if( mail($to,$subject,$message,$headers,'-fsongfarm') ){
									// write to log
									file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- Email sent: User '.$songcircle_user_data['user_id'].', '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
								} else {
									// could not send email
									// log error
									file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: Could not send email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
								}

							} // end of: if($message = constructHTMLEmail($email_data['reminder_email'],$songcircle_user_data))
							else {
								// could not construct email
								// log error
								file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: Could not construct email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							}

						} // end of: if($user->setUserData($songcircle_user_data['user_id']))
							else {
								// No user information available for given user id
								// log error
								file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: No user information available for user id: '.$songcircle_user_data['user_id']. '('.$_SERVER['PHP_SELF'].__LINE__.') '.PHP_EOL,FILE_APPEND);
							}
					} // end of: foreach($result as $row)

				} // end of: if($result = $db->getRows($sql))
				else {
					// TEST
					// write to log
					file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- NO result'.PHP_EOL,FILE_APPEND);
				}
					break;

			/**
			* Sends join link to user, RELATIVE TO USER TIMEZONE, 15 minutes before event
			*
			* NOTE: Cron runs every 15 minutes
			*/
				case 'join_songcircle':

				// write to log
				file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' CALL: join_songcircle'.PHP_EOL,FILE_APPEND);

				// query database
// 				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, ";
// 				$sql.= "sr.user_id, verification_key, confirm_status, ur.user_name, user_email, SUBSTR(ut.full_timezone,5,6) AS user_timezone ";
// 				$sql.= "FROM songcircle_create AS sc, songcircle_register AS sr	";
// 				$sql.= "INNER JOIN user_register AS ur ON sr.user_id = ur.user_id ";
// 				$sql.= "INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id ";
// 				$sql.= "WHERE sc.songcircle_id = sr.songcircle_id ";
// 				$sql.= "AND sc.songcircle_status = 0 AND sr.confirm_status = 1 ";
// 				/* NOTE: need to input user timezone variable below somewhere */
// 				$sql.= "AND DATE(date_of_songcircle) = DATE(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 15 MINUTES))";

				$sql = "SELECT sc.songcircle_id, songcircle_name, date_of_songcircle, sr.user_id, verification_key, confirm_status,ur.user_name, user_email,
				SUBSTR(ut.full_timezone,5,6) AS user_timezone,
				convert_tz(date_of_songcircle, @@global.time_zone, '+0:00') as date_of_songcircle_UTC,
				convert_tz(now(), @@global.time_zone, '+0:00') as today_time_UTC,
				convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00') as user_time_UTC
				/*,TIMESTAMPDIFF( MINUTE, convert_tz(date_of_songcircle, @@global.time_zone, '+0:00'), convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00') ) */
				
				FROM songcircle_create AS sc, songcircle_register AS sr
				INNER JOIN user_register AS ur ON sr.user_id = ur.user_id
				INNER JOIN user_timezone AS ut ON ur.user_id = ut.user_id
				WHERE sc.songcircle_id = sr.songcircle_id AND sc.songcircle_status = 0 AND sr.confirm_status =1
				
				and TIMESTAMPDIFF( MINUTE, convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00')  , convert_tz(date_of_songcircle, @@global.time_zone, '+0:00')) > -1 and
				TIMESTAMPDIFF( MINUTE, convert_tz(now(), SUBSTR(ut.full_timezone,5,6), '+0:00')  , convert_tz(date_of_songcircle, @@global.time_zone, '+0:00')) < 16 ";
				
				
				
				
				if($result = $db->getRows($sql)){

					foreach($result as $songcircle_user_data){

						// construct email
						$to = "{$songcircle_user_data['user_name']} <{$songcircle_user_data['user_email']}>";
						$subject = "Join ".$songcircle_user_data['songcircle_name']." now!";
						$from = "Songfarm <noreply@songfarm.ca>";
						if($message = constructHTMLEmail($email_data['join_songcircle'],$songcircle_user_data)){
							$headers = "From: {$from}\r\n";
							$headers.= "Content-Type: text/html; charset=utf-8";

							// if mail successful
							if( mail($to,$subject,$message,$headers,'-fsongfarm') ){
								// write to log
								file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- Join link sent: User '.$songcircle_user_data['user_id'].', '.$songcircle_user_data['songcircle_id'].PHP_EOL,FILE_APPEND);
							} else {
								// could not send email
								// log error
								file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: Could not send email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							}

						} // end of: if($message = constructHTMLEmail())
						else {
							// could not construct email
							// log error
							file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: Could not construct email ('.$_SERVER['PHP_SELF'].__LINE__.') '.$songcircle_user_data['songcircle_id'].' ('.$songcircle_user_data['songcircle_name'].') '.$songcircle_user_data['date_of_songcircle'].PHP_EOL,FILE_APPEND);
						}

					} // end of: foreach($result as $row)
				} // end of: if($result = $db->getRows($sql))

					break;

			/**
			* Clears completed/expired songcircles
			*
			* NOTE: Cron runs every 2 hours on the 3 minute
			*/
				case 'clear_expired_songcircle':

				// write to log
				file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' CALL: clear_expired_songcircle'.PHP_EOL,FILE_APPEND);

				// if songcircle start time + duration of songcircle is less than current time (in UTC)
				$sql = "SELECT id, songcircle_id, date_of_songcircle " .
					"FROM songcircle_create " .
					"WHERE DATE_ADD(date_of_songcircle, INTERVAL duration HOUR_SECOND) < DATE_ADD(NOW(), INTERVAL '5' HOUR)";

				if($result = $db->getRows($sql)){

					// test log
					// file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- got result: '.$result.PHP_EOL,FILE_APPEND);

					// foreach result
					foreach ($result as $songcircle) {

						// test log
						// file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- got result'.PHP_EOL,FILE_APPEND);

						// get id for expired
						$id = $songcircle['id'];
						$songcircle_id = $songcircle['songcircle_id'];

						// delete record by id
						$sql = "DELETE FROM songcircle_create WHERE id = $id LIMIT 1";
						if($result = $db->query($sql)){
							// check affected rows
							if(mysqli_affected_rows($db->connection) > 0){
								// if successful delete, write to log
								file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- REMOVE_EXPIRED: '.$songcircle_id.', date_of_songcircle: '.$songcircle['date_of_songcircle'].PHP_EOL,FILE_APPEND);
							} else {
								// failed to delete record
								// log error
								file_put_contents(SITE_ROOT.'/logs/cronjobs/error_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' '.error_get_last().' -- FAILED: No record deleted ('.$_SERVER['PHP_SELF'].__LINE__.') id: '.$id.', songcircle_id: '.$songcircle_id.PHP_EOL,FILE_APPEND);
							}
						} // end of: if($result = $db->query($sql))
					} // end of: foreach ($result as $songcircle)
				}
				else {

					// if NO result, write to log
					file_put_contents(SITE_ROOT.'/logs/cronjobs/cronlog_'.date("m-d-Y").'.txt',date("G:i:s",strtotime('+5 hours')).' -- no action'.PHP_EOL,FILE_APPEND);

				}
					break;

			} // end of: switch ($arg)
		} // end of: foreach ($argv as $arg)
	} // end of: if(isset($argv)
}
