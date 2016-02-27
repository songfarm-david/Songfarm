<?php

	require_once("../includes/initialize.php");
	require_once("../includes/cronjob_helper.php");
	set_time_limit(3000);
<<<<<<< HEAD
	global $db;
	
	$api = new CronApi();
=======

	/* who calls this page?? Where is this GET request originating from? */
>>>>>>> origin/master
	switch($_GET['method']) {

	 	case 'songcircleState':
	 	{	
	 		file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " CronJob songcircleState started", FILE_APPEND);
	 		
			// query database for a record of global user_id 0. if no rows come back, then run the create method.
			$sql = "SELECT * FROM songcircle_create WHERE songcircle_status <> 5";
			//$currentTime = date('Y-m-d H:i:s', time());

			$currentTime = time();

			//echo " currentTime " . $currentTime ;

			if($result = $db->query($sql))
			{
				foreach($result as $row)
				{
					$songcircle_id = $row['songcircle_id'];
					$date_of_songcircle = $row['date_of_songcircle'];
					$songcircledatetime = strtotime($date_of_songcircle);
					$diff= ($currentTime - $songcircledatetime);
					$diffinMin = $diff/60;
					//echo " diff in hours ". $diffinMin;

					if($diffinMin <= 0 && $diffinMin > -120)
					{
						//update the table with status to started
						$updatesql = "Update songcircle_create set songcircle_status = 1 where songcircle_id = '$songcircle_id'";
						echo $updatesql;
						$db->query($updatesql);
											}
					else if($diffinMin > 0 && $diffinMin < (3*60*60))
					{
						//update the table with status to completed
						$updatesql = "Update songcircle_create set songcircle_status = 5 where songcircle_id = '$songcircle_id'";
						echo $updatesql;
						$db->query($updatesql);
					}
				}
			}
			break;
		}
		
		case 'songcircleReminder':
		{
			file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " CronJob songcircleReminder started", FILE_APPEND);
			
			// query database for a record of global user_id 0. if no rows come back, then run the create method.
			$sql = "SELECT sc.songcircle_id songcircle_id, songcircle_name, date_of_songcircle, user_id, confirmation_key FROM songcircle_create sc, songcircle_register sr 
					where sc.songcircle_id = sr.songcircle_id and sc.songcircle_status=0 and date(date_of_songcircle) = date(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 4 DAY))";
		
			//$sql = "SELECT sc.songcircle_id songcircle_id, songcircle_name, date_of_songcircle, user_id, confirmation_key FROM songcircle_create sc, songcircle_register sr ";
		
			file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). ' Sql '. $sql, FILE_APPEND);
				
			if($result = $db->getRows($sql))
			{
					foreach($result as $row)
					{
						$songcircle_id = $row->songcircle_id;
						$songcircle_name = $row->songcircle_name;
						$date_of_songcircle = $row->date_of_songcircle;
						$user_id = $row->user_id;
						$confirmation_key = $row->confirmation_key;
						$songcircledatetime = strtotime($date_of_songcircle);
						
						file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " songcircle_id ". $songcircle_id .
								" date_of_songcircle ". $date_of_songcircle . " user_id ". $user_id. " confirmation_key ". $confirmation_key , FILE_APPEND);
							
						//get user email and send email
						$userEmail = $user->retrieve_user_email($user_id);
						$unregisterLink = $_SERVER['SERVER_ADDR'].'/public/userAction.php?songcircle_id='.$songcircle_id.'&userid='.$user_id.'&action=Unregister&confirmation='.$confirmation_key;
						
						//now send a mail
						$message ='Hi,'.PHP_EOL.'You have registered for songcirlce ' . $songcircle_name. ' dated '. $date_of_songcircle. PHP_EOL.
							'You can unregister the songcirlce using following link ' . $unregisterLink .PHP_EOL.
								PHP_EOL. 'Thanks for using our service.'.PHP_EOL.'Thank you,'.PHP_EOL.'SongFarm Team';
						$subject ="Gentle Reminder : Upcoming SongCirlce";
						$fromEmail = ADMIN_EMAIL;
							
						file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " message ". $message .
								" userEmail ". $userEmail . " subject ". $subject. " fromEmail ". $fromEmail , FILE_APPEND);
							
						$api->sendEmail($fromEmail, $fromEmail, $userEmail, $subject, $message);
					}
				}
			
			break;
		}
		
		case 'songcircleLastReminder':
			{
				file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " CronJob songcircleLastReminder started", FILE_APPEND);
					
				// query database for a record of global user_id 0. if no rows come back, then run the create method.
				//$sql = "SELECT sc.songcircle_id songcircle_id, songcircle_name, date_of_songcircle, user_id, confirmation_key FROM songcircle_create sc, songcircle_register sr
				//	where sc.songcircle_id = sr.songcircle_id and sc.songcircle_status=0 and date_of_songcircle < DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 HOUR)";
				
				$sql = "SELECT sc.songcircle_id songcircle_id, songcircle_name, date_of_songcircle, user_id, confirmation_key FROM songcircle_create sc, songcircle_register sr";
				
				file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " Sql ". $sql, FILE_APPEND);
					
				if($result = $db->getRows($sql))
				{
					foreach($result as $row)
					{
						$songcircle_id = $row->songcircle_id;
						$songcircle_name = $row->songcircle_name;
						$date_of_songcircle = $row->date_of_songcircle;
						$user_id = $row->user_id;
						$confirmation_key = $row->confirmation_key;
						$songcircledatetime = strtotime($date_of_songcircle);
						
						file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " songcircle_id ". $songcircle_id .
								" date_of_songcircle ". $date_of_songcircle . " user_id ". $user_id. " confirmation_key ". $confirmation_key , FILE_APPEND);
							
							
						//get user email and send email with unregister link
						$userEmail = $user->retrieve_user_email($user_id);
						$unregisterLink = $_SERVER['SERVER_ADDR']."/public/userAction.php?songcircle_id=$songcircle_id&userid=$user_id&action=Unregister&confirmation=$confirmation_key";
						
						//get user email and send email with Join link
						$joinLink = $_SERVER['SERVER_ADDR']."/public/userAction.php?songcircle_id=$songcircle_id&userid=$user_id&action=Join&confirmation=$confirmation_key";
						
						//now send a mail
						$message ='Hi,'.PHP_EOL.'You have registered for songcirlce ' . $songcircle_name. ' dated '. $date_of_songcircle. PHP_EOL.
						'You can unregister the songcirlce using following link ' . $unregisterLink .PHP_EOL.
						'You can Join the songcirlce using following link ' . $joinLink .PHP_EOL.
						PHP_EOL. 'Thanks for using our service.'.PHP_EOL.'Thank you,'.PHP_EOL.'SongFarm Team';
						$subject ="Gentle Reminder : Upcoming SongCirlce";
						$fromEmail = ADMIN_EMAIL;
		
						file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " message ". $message .
							" userEmail ". $userEmail . " subject ". $subject. " fromEmail ". $fromEmail , FILE_APPEND);
					
						$api->sendEmail($fromEmail, $fromEmail, $userEmail, $subject, $message);
					}
				}
				else
				{
					file_put_contents('../logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " No records". $sql, FILE_APPEND);
				}
			}
	}
	
	
	
	class CronApi 
	{
		public function getuserEmail($user_id)
		{
			$user_email = $user->retrieve_user_email($user_id);
			return $user_email;
		}
		
		//to send email to the user
		public function sendEmail($fromEmail, $replyEmail, $toEmail, $subject, $message)
		{
			$headers = 'From: '. $fromEmail . "\r\n" .
						'Reply-To: ' . $replyEmail . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
								
			//send a mail to me and victor
			mail($toEmail, $subject ,$message, $headers);
		}
	}
	
?>
