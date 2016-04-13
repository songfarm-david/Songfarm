<?php require_once('initialize.php');
require_once('../email/email_data.php');
/**
* All links to this page will contain an 'action' parameter
*/
// if a certain condition is met
if(isset($_GET['action']) && !empty($_GET['action'])){
	// collect action into variable
	$action = $db->escapeValue($_GET['action']);
	// construct switch statement
	switch ($action) {

	/**
	* Register user for songcircle (and Songfarm if applicable)
	*/
		case 'register' :

		// set confirmation_template variables to nothing
		$success_msg = $error_msg = '';

		if(isset($_POST['formData']) && !empty($_POST['formData'])){

			// initialize all arrays
			$required_data = $form_data = $clean_data = $errors = [];

			// required data from user
			$required_data = [
				'username', 'user_email',
				'timezone','full_timezone',
				'country_name','country_code',
				'codeOfConduct','songcircle_id',
				'date_of_songcircle','songcircle_name',
				'waiting_list'
			];

			// explode string into array
			$form_data_array = explode('&',$_POST['formData']);

			// loop through array and clean values
			foreach($form_data_array as $value){
				// remove anything after '='
				$value = substr($value, 0, strpos($value,'='));
				// put cleaned values into a new array
				$form_data[] = $value;
			}

			// check for difference between $form_data and $required_data
			if($diff = array_diff($required_data, $form_data)){
				// if diff, return error

					/* function for displaying which values were missing */
					echo displayMissingValues($diff);

				/* exit validation.. */
				return false;

			} else {
			// all required fields are present, continue processing...
				// sanitize data into $clean_data array
				foreach($form_data_array as $key => $value){
					$clean_data[$key] = $db->escapeValue(urldecode($value));
				}

				// assign variables to each field in form
			 	$username				=	explode('=',$clean_data[0])[1];
			 	$email					=	explode('=',$clean_data[1])[1];
				$timezone 			= explode('=',$clean_data[2])[1];
				$full_timezone	=	explode('=',$clean_data[3])[1];
			 	$country_name		= explode('=',$clean_data[4])[1];
				$country_code		=	explode('=',$clean_data[5])[1];
				$codeOfConduct 	= explode('=',$clean_data[6])[1];
				$songcircle_id  = explode('=',$clean_data[7])[1];
				$songcircle_date= explode('=',$clean_data[8])[1];
				$songcircle_name= explode('=',$clean_data[9])[1];
				$waiting_list		= explode('=',$clean_data[10])[1];

			/*
			* Begin validation processing
			*/
				/* Username */
				// if NOT has presence
				if(!$db->hasPresence($username)){
					$errors['name_error'] = "Please enter your name";
				} elseif (strlen($username) < 2) { // if name too short
					$errors['name_error'] = 'Please ensure that your name is at least two characters long';
				}

				/* User Email */
				// if NOT has presence
				if(!$db->hasPresence($email)) {
					$errors['email_error'] = "Please enter your email";
				}
				// if is NOT valid email
				elseif (!$db->isValidEmail($email)) {
					$errors['email_error'] = 'Please enter a valid email';
				}
				// if duplicate email in user register
				elseif ($db->hasRows($db->uniqueEmail($email))){

					// set flag if email is already in database
					$emailInDatabase = true;

					// check user ID against email in user_register table
					$user_id = $db->getIDbyEmail($email);
					// set type to integer
					settype($user_id, 'int');

					// check function to see if user is already registered for this songcircle
					if($songcircle->userAlreadyRegistered($songcircle_id, $user_id)){
						$errors['email_error'] = 'That email is already registered for this songcircle.';
					}
				}	else {
					// email is not in database, set flag
					$emailInDatabase = false;
				}

				/* Location */
				// if Timezone NOT has presence
				$errors['timezone_error'] = (!$db->hasPresence($timezone) ? true : false);
				// if Country NOT has presence
				$errors['country_error'] = (!$db->hasPresence($country_name)) ? true : false;
				// if Country Code NOT has presence
				$errors['code_error'] = (!$db->hasPresence($country_code)) ? true : false;

				/* If Errors */
				if( $errors['timezone_error'] || $errors['country_error'] || $errors['code_error']){
					echo 'There was a problem processing your location. Please try again later or <a href="mailto:support@songfarm.ca">contact support</a> at support@songfarm.ca.';
				} elseif ( isset($errors['name_error']) || isset($errors['email_error']) ) {
					$output = '<ul>';
					foreach ($errors as $key => $error) {
						if( $error !== false ) // excludes booleans values (flags)
						$output.= '<li data-error-key="'.$key.'">'.$error.'</li>';
					}
					$output.= '</ul>';
					echo $output;

					// exit processing
					exit;

				} elseif (!$emailInDatabase) {
				// if emailInDatabase is false, user is not registered in the database

				// begin transaction
				$db->beginTransaction();

					try {
						// construct first query
						$sql = "INSERT INTO user_register (user_name, user_email, reg_date)";
						$sql.= "VALUES ('$username', '$email', NOW())";
						if(!$result = $db->query($sql)){
							// if no result, throw error
							throw new Exception("Error Processing Registration Insert Request", 1);
						} else {
							// if result, get last inserted id
							$user_id = $db->lastInsertedID($result);
							// construct second query
							$sql = "INSERT INTO user_timezone (user_id, timezone, full_timezone, country_name, country_code) ";
							$sql.= "VALUES ($user_id, '$timezone', '$full_timezone', '$country_name', '$country_code')";
							// check for successful
							if(!$result = $db->query($sql)){
								// if no result, throw error
								throw new Exception("Error Processing Timezone Insert Request", 1);
							} else {
								// commit query to database
								$db->commit();
								// construct log text
								$log_text = ' Subscribe -- user_id: '.$user_id.'; email: '.$email;
								// write to log
								file_put_contents(SITE_ROOT.'/logs/user_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);

							}
						}
					} catch (Exception $e) {
						// rollback any committed inserts
						$db->rollback();
					}

				} // end of: elseif (!$emailInDatabase)

				/*
				No errors..
				User either was already IN the database or is NOW in the database
				*/

				// create unique confirmation key
				$confirmation_key = getToken(40);
				// format date of songcircle
				$songcircle_date 	= $songcircle->callUserTimezone($songcircle_date, $timezone);

				// make $songcircle_user_data array for emails
				$songcircle_user_data = [
					"username" 						=> $username,
					"songcircle_name" 		=> $songcircle_name,
					"date_of_songcircle" 	=> $songcircle_date,
					"link_params" 	=> [
						"songcircle_id" 		=> $songcircle_id,
						"user_email" 				=> $email,
						"confirmation_key" 	=> $confirmation_key
					]
				];

				$confirmation_data['flag'] = true;
				$confirmation_data['username'] = $username;
				$confirmation_data['songcircle_name'] = $songcircle_name;
				$confirmation_data['date_of_songcircle'] = $songcircle_date;

				// if NO waiting list
				if( $waiting_list == 'false' ){
					// send registration confirmation email

					$to = "{$username} <{$email}>"; // this may cause a bug on Windows systems
					$subject = "Confirm your registration!";
					$from = "Songfarm <noreply@songfarm.ca>";
					if($message = constructHTMLEmail($email_data['confirm_registration'],$songcircle_user_data)){
						$headers = "From: {$from}\r\n";
						$headers.= "Content-Type: text/html; charset=utf-8";
						//if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') )
						{
							// enter user into songcircle_register
							$sql = "INSERT INTO songcircle_register (songcircle_id, user_id, confirmation_key) ";
							$sql.= "VALUES ('$songcircle_id', $user_id, '$confirmation_key')";
							if($result = $db->query($sql)){
							// insert successful:
								// construct log text
								$log_text = ' Registered -- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id;
								// write to log
								file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
								// json encode and send confirmation data
								echo json_encode($confirmation_data);
							}
						} 
						//else 
						{
						// email failed to send
							// construct error message
							$output = '<span>Oops!</span><br /><br />';
							$output.= 'We\'re sorry but registration for <b>'.$songcircle_name.'</b> on <b>'.$songcircle_date.'</b> could not be completed';
							$output.= '<br /><br />Please try again in a few minutes. <br>If you\'re still having trouble, please contact support at <a href="mailto:support@songfarm.ca"><b>support@songfarm.ca</b></a>.';
							print $output;
						}
					} // end of: if($message = constructHTMLEmail())
					else
					{
						// write error notice
						$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'register' - ";
						$err_msg.= "Failed to send confirm_registration email to ".$username." ".$email." for ".$songcircle_id." on ".$songcircle_date;
						file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
					}
				} else { // waiting list is open
					// enter user into songcircle_wait_register
					$sql = "INSERT INTO songcircle_wait_register (songcircle_id, user_id, confirmation_key) ";
					$sql.= "VALUES ('$songcircle_id', $user_id, '$confirmation_key')";
					if($result = $db->query($sql)){
						// insert successful:
						/**
						* NOTE: can send waitlist registration email notice here
						*/
						// construct log text
						$log_text = ' Waitlist Added -- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id;
						// write to log
						file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
						// create waitlist flag
						$confirmation_data['waitlist'] = true;
						// json encode and send confirmation data
						echo json_encode($confirmation_data);
					} else {
						// write error notice
						$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'register' - ";
						$err_msg.= "Failed to insert values into songcircle_wait_register for ".$username." ".$email." for ".$songcircle_id." on ".$songcircle_date;
						file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
					}
				}
			} // end of: else ($diff = array_diff($required_data, $form_data))
		}
		else // end of: if(isset($_POST['formData']) && isset($_POST['songcircleId']))
		{
			// if there is an error in the $_POST data sent from songcircle.php
			$output = '<p>Our system has experienced a problem processing your request. Please <a href="mailto:support@songfarm.ca">contact support</a> at support@songfarm.ca.</p><br>';
			$output.= '<p>We are sorry for the inconvenience</p>.';
			print $output;
		}
			break;

	/**
	* Confirms user registration for a songcircle
	*/
		case 'confirm_registration':
		if(	( isset($_GET['songcircle_id']) && !empty($_GET['songcircle_id']) ) &&
				( isset($_GET['user_email']) && !empty($_GET['user_email']) ) &&
				( isset($_GET['confirmation_key']) && !empty($_GET['confirmation_key']) )	&&
				( isset($_GET['username']) && !empty($_GET['username']) )
				)
			{
				if($_GET['waitlist'] == 'true'){
					$table_name = 'songcircle_wait_register';
				} else {
					$table_name = 'songcircle_register';
				}

				$success_msg = ''; $error_msg = array();
			// Sanitize values
			$songcircle_id = $db->escapeValue($_GET['songcircle_id']);
			if(strlen($songcircle_id) != 13){
				$error_msg[] = 'Invalid conference id';
				$songcircle_id = false;
			}
			$user_email = $db->escapeValue($_GET['user_email']);
			// check if NOT valid email
			if(!$user_email = $db->isValidEmail($user_email)){
				// redirect to generic error page.
				$error_msg[] = 'Invalid email sent with request. User confirmation failed.';
				$user_email = false;
			}
			$confirmation_key = (string) $db->escapeValue($_GET['confirmation_key']);
			if(strlen($confirmation_key) != 40){
				$error_msg[] = 'Invalid confirmation key';
				$confirmation_key = false;
			}
			$username = $db->escapeValue($_GET['username']);
			// if all values are valid
			if($songcircle_id && $user_email && $confirmation_key && $username){
				// all values have been accounted for and sanitized
				// get start time of songcircle
				$sql = "SELECT songcircle_name, date_of_songcircle, UNIX_TIMESTAMP(date_of_songcircle) ";
				$sql.= "FROM songcircle_create ";
				$sql.= "WHERE songcircle_id = '$songcircle_id'";
				if($result = $db->query($sql)){
					// fetch data array
					$songcircle_data = $db->fetchArray($result);
					// get variables from array
					$songcircle_name = $songcircle_data['songcircle_name'];
					$date_of_songcircle = $songcircle_data['date_of_songcircle'];
					// capture unix formatted start time in variable
					$start_time = $songcircle_data['UNIX_TIMESTAMP(date_of_songcircle)'];
					if(empty($start_time)){
						// craft err_msg
						$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'fonfirm_registration' - ";
						$err_msg.= 'Unable to retrieve start_time for songcircle '.$songcircle_id;
						// write to log
						file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
						// construct error_msg for user
						$error_msg[] = 'An error has occurred retrieving start time of Songcircle. We are very sorry for the inconvenience.';
						// exit script
						exit('An error has occurred retrieving start time of Songcircle. We are very sorry for the inconvenience.');
					} else {
						$current_time = time();
						// if the link is expired
						if($songcircle->isExpiredLink($current_time,$start_time)){
							$error_msg[] = 'The confirmation period for this songcircle has expired. Please visit <a href="http://www.songfarm.ca/public/songcircle.php">http://songfarm.ca/songcircle</a> to sign up for another one.';
							/**
							* NOTE: Remove user from songcircle registry || this will be executed by clear_expired_songcircle()
							*/
						} else {
						// if the link is not expired
							// if NOT user_id for given email
							if(!$user_id = $db->getIDbyEmail($user_email)){
								$error_msg[] = 'No user exists for the email provided.';
							} else {
							// user id exists for given email
								// Select all data where songcircle_id and user_id match
								$sql = "SELECT * FROM ".$table_name." WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
								if($result = $db->query($sql)){
									// if rows
									if($row = $db->hasRows($result)){
										// if row
										$user_array = $db->fetchArray($result);
										// if confirmation key exists
										if( isset($user_array['confirmation_key']) && !empty($user_array['confirmation_key']) ){
											// assign confirmation key to variable
											$user_key = (string) $user_array['confirmation_key'];
											//compare confirmation key from email with database retrieved user key
											if( $confirmation_key !== $user_key ){
												$error_msg[] = 'Invalid confirmation key provided.';
											} else {
												// keys do match
												// create unique verification_key
												$verification_key = getToken(40);
												// attempt to update user status for songcircle
												if($songcircle->confirmUserRegistration($songcircle_id, $user_id, $verification_key)){
													// retrieve user's timezone
													if($user->hasLocation($user_id)){
														// convert songcircle date_of_songcircle to user timezone
														$user_date_of_songcircle = $songcircle->callUserTimezone($date_of_songcircle,$user->timezone);
														// create user_data array
														$songcircle_user_data = [
															"username" 						=> $username,
															"user_email" 					=> $user_email,
															"songcircle_name" 		=> $songcircle_name,
															"date_of_songcircle"	=> $user_date_of_songcircle,
															"link_params" => [
																"songcircle_id" => $songcircle_id,
																"user_id" 			=> $user_id
															]
														];
														// craft email
														$to = "{$username} <{$user_email}>"; // this may cause a bug on Windows systems
														$subject = "Registration Confirmed!";
														$from = "Songfarm <noreply@songfarm.ca>";
														if($message = constructHTMLEmail($email_data['registered'],$songcircle_user_data)){
															$headers = "From: {$from}\r\n";
															$headers.= "Content-Type: text/html; charset=utf-8";
															if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') ){
																// construct log text
																$log_text = ' Confirmed --- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id;
																// write to log
																file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
																$error_msg = false;
																$success_msg = 'Thanks!<br> You have successfully been confirmed for '.$songcircle_name.'.';
															}
														} // end of: if($message = constructHTMLEmail())
													}	else {
														// craft err_msg
														$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'confirm_registration' - ";
														$err_msg.= 'Unable to retrieve user location for user id '.$user_id;
														// write to log
														file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
														$error_msg[] = 'No timezone exists for '.$username.' ('.$user_id.').';
														exit('No timezone exists for '.$username.' ('.$user_id.').');
													}
												} else {
													$error_msg[] = 'Error: user confirmation update failed.';
												}
											}
										} else {
											$error_msg[] = 'User is already registered for the given songcircle.';
										}
									} else {
										// no rows exist
										$error_msg[] = 'User has not registered.';
									}
								}
							}
						}
					}
				}
			} // end of: if($songcircle_id && $user_email && $confirmation_key)
		} // end of: if isset($_GET) and !empty($_GET)..
			else {
				$error_msg[] = "There was an error unregistering you from this event. Please contact <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a> to resolve this.";
			}
			break;

	/**
	* Unregister user from a songcircle
	*/
		case 'unregister':
		if( (isset($_GET['songcircle_id']) && !empty($_GET['songcircle_id'])) &&
				(isset($_GET['user_id']) && !empty($_GET['user_id']))
			)
			{

				$songcircle_id = $db->escapeValue($_GET['songcircle_id']);
				$user_id = $db->escapeValue($_GET['user_id']);

				if($songcircle->unregisterUserFromSongcircle($songcircle_id,$user_id)){
					// construct log text
					$log_text = ' Unregistered -- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id;
					// write to log
					file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
					// create success message
					$success_msg = "You have been successfully unregistered from this Songcircle";

					if($waitlist_data = $songcircle->getWaitlist($songcircle_id)){
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
						if($user->setUserData($songcircle_user_data['user_id'])){
							// set user data variables
							$songcircle_user_data['username'] = $user->username;
							$songcircle_user_data['user_email'] = $user->user_email;
							// get date_of_songcircle by user timezone
							$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($date_of_songcircle,$user->timezone);

							// construct email
							$to = "{$user->username} <{$user->user_email}>";
							$subject = "A spot has opened up for ".$songcircle_user_data['songcircle_name']."!";
							$from = "Songfarm <noreply@songfarm.ca>";
							if($message = constructHTMLEmail($email_data['waitlist'], $songcircle_user_data)){
								$headers = "From: {$from}\r\n";
								$headers.= "Content-Type: text/html; charset=utf-8";
								if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') ){
									// write log text
									$log_text = ' Waitlist -- User id: '.$user->user_id.' was notified for '.$songcircle_user_data['songcircle_name'].' ('.$songcircle_user_data['songcircle_id'].')';
									// write to log
									file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
								} // end of: if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') )
								else {
									$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") Failed to send email.";
									// write to log
									file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
								}
							} // end of: if($message = constructHTMLEmail($email_data['waitlist_notice'], $songcircle_user_data))
							else {
								// write error msg
								$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'unregister' - ";
								$err_msg.= "Unable to send waitlist notice to User id: ".$user->user_id." for ".$songcircle_user_data['songcircle_name']." (".$songcircle_user_data['songcircle_id'].")";
								// write to log
								file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
							}
						} else {
							echo 'Error retrieving user data';
						}
					} // end of: if($waitlist_data)
				} else {
					$error_msg[] = "No records exist for this songcircle.";
				}
			} // (isset($GET['songcircle_id']) && (isset($GET['user_id'])
				else {
					$error_msg[] = "There was an error processing your request. If this problem persists, please contact support at <a href=\"mailto:support@songfarm.ca\">support@songfarm.ca</a>. We apologize for the inconvenience.";
					// error text for logging
					$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'unregister' - Failed to execute script";
					file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
				}
				break;

	/**
	* Join user to a songcircle
	*/
		case 'join_songcircle':
		if( (isset($_GET['songcircle_id']) && !empty($_GET['songcircle_id'])) &&
				(isset($_GET['user_id']) && !empty($_GET['user_id']))	&&
				(isset($_GET['verification_key']) && !empty($_GET['verification_key']))
			)
			{
				// set variables
				$songcircle_id = $db->escapeValue($_GET['songcircle_id']);
				$user_id = $db->escapeValue($_GET['user_id']);
				$verification_key = $_GET['verification_key'];

				if($songcircle->userAlreadyRegistered($songcircle_id,$user_id)){
					// construct join link
					$link_join_songcircle = $_SERVER['SERVER_ADDR'].'public/start_call.php?';
					$link_join_songcircle.= 'songcircle_id='.$songcircle_id.'&';
					$link_join_songcircle.= 'user_id='.$user_id.'&';
					$link_join_songcircle.= 'verification_key='.$verification_key;
					// redirect to start_call.php
					redirectTo($link_join_songcircle);
				}
			} else {
				$error_msg[] = "Unknown Error: Unable to Join Songcircle";
			}
			break;

	/**
	* If user wants to be taken off the waiting list
	*/
		case 'waitlist_unregister':
			if( (isset($GET['songcircle_id']) && !empty($GET['songcircle_id'])) &&
					(isset($GET['user_id']) && !empty($GET['user_id']))
				){

					$songcircle_id = $db->escapeValue($GET['songcircle_id']);
					$user_id = $db->escapeValue($GET['user_id']);

					if($songcircle->unregisterUserFromWaitlist($songcircle_id,$user_id)){
					// if true, log unregister
						// call waitlist function de nuevo
						if($waitlist_data = $songcircle->getWaitlist($songcircle_id)){
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
							if($user->setUserData($songcircle_user_data['user_id'])){
								// set user data variables
								$songcircle_user_data['username'] = $user->username;
								$songcircle_user_data['user_email'] = $user->user_email;
								// get date_of_songcircle by user timezone
								$songcircle_user_data['date_of_songcircle'] = $songcircle->callUserTimezone($date_of_songcircle,$user->timezone);
								// construct email
								$to = "{$user->username} <{$user->email}>"; // this may cause a bug on Windows systems
								$subject = "A spot has opened up for ".$songcircle_user_data['songcircle_name'];
								$from = "Songfarm <noreply@songfarm.ca>";
								if($message = constructHTMLEmail($email_data['waitlist'], $songcircle_user_data)){
									$headers = "From: {$from}\r\n";
									$headers.= "Content-Type: text/html; charset=utf-8";
									if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') ){
										// write log text
										$log_text = ' Waitlist -- User id: '.$user->user_id.' was notified for '.$songcircle_user_data['songcircle_name'].' ('.$songcircle_user_data['songcircle_id'].')';
										// write to log
										file_put_contents(SITE_ROOT.'/logs/songcircle_'.date("m-d-Y").'.txt',date("G:i:s").$log_text.PHP_EOL,FILE_APPEND);
									} // end of: if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') )
								} // end of: if($message = constructHTMLEmail($email_data['waitlist_notice'], $songcircle_user_data))
								else {
									// write error msg
									$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'waitlist_unregister' - ";
									$err_msg.= 'Unable to send waitlist notice to User id: '.$user->user_id.' for '.$songcircle_user_data['songcircle_name'].' ('.$songcircle_user_data['songcircle_id'].')';
									// write to log
									file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
								}
							} else {
								//Write to user log here
								$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'waitlist_unregister' - ";
								$err_msg.= "Unable to retrieve user data for user id: ".$user_id;
								file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
							}
						} // end of: if($waitlist_data)
					} else {
						//Write to songcircle log here
						$err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") case 'waitlist_unregister' - ";
						$err_msg.= 'Unable to unregister from waitlist user id '.$user_id.'; songcircle_id '.$songcircle_id;
						file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);
					}
				} else {
					$error_msg[] = "Unregistration from Waitlist failed.";
				}
			break;

		// if no cases match, redirect somewhere
		default:
			redirectTo('../public/index.php');
			break;
	} // end of: switch
} // end of: if(isset($_GET['action']) && !empty($_GET['action'])){
else {
	redirectTo('../public/index.php');
}
?>
<?php	if($action != 'register'){
	include('layout/confirmation_template.php');
		/* above include reads success or error messages and displays them here */
}
?>
