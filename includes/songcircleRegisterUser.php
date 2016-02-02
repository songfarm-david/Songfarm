<?php require_once('initialize.php');
/**
*	This page receives user submitted data from public/songcircle.php
*
*	It will attempt to register a user for a specific songcircle
* If user is not registered, it will register them in user_register SQL table as well
*
*	Last Updated: 01/28/2016
*/
if(isset($_POST['formData']) && !empty($_POST['formData'])){

	// initialize all arrays
	$required_data = $form_data = $clean_data = $errors = [];

	// required data from user
	$required_data = [
		'username', 'user_email',
		'timezone','fullTimezone',
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
			displayMissingValues($diff);

		/* exit validation.. */
		return false;

	} else {
	// all required fields are present, continue processing...
		// sanitize data into $clean_data array
		foreach($form_data_array as $key => $value){
			$clean_data[$key] = $db->escape_value(urldecode($value));
		}

		// assign variables to each field in form
	 	$username				=	explode('=',$clean_data[0])[1];
	 	$email					=	explode('=',$clean_data[1])[1];
		$timezone 			= explode('=',$clean_data[2])[1];
		$fullTimezone		=	explode('=',$clean_data[3])[1];
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
		if(!$db->has_presence($username)){
			$errors['name_error'] = "Please enter your name";
		} elseif (strlen($username) < 2) { // if name too short
			$errors['name_error'] = 'Please ensure that your name is at least two characters long';
		}

		/* User Email */
		// if NOT has presence
		if(!$db->has_presence($email)) {
			$errors['email_error'] = "Please enter your email";
		}
		// if is NOT valid email
		elseif (!$db->is_valid_email($email)) {
			$errors['email_error'] = 'Please enter a valid email';
		}
		// if duplicate email in user register
		elseif ($db->has_rows($db->unique_email($email))){

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
		$errors['timezone_error'] = (!$db->has_presence($timezone) ? true : false);
		// if Country NOT has presence
		$errors['country_error'] = (!$db->has_presence($country_name)) ? true : false;
		// if Country Code NOT has presence
		$errors['code_error'] = (!$db->has_presence($country_code)) ? true : false;

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
					$user_id = $db->last_inserted_id($result);
					// construct second query
					$sql = "INSERT INTO user_timezone (user_id, timezone, full_timezone, country_name, country_code) ";
					$sql.= "VALUES ($user_id, '$timezone', '$fullTimezone', '$country_name', '$country_code')";
					// check for successful
					if(!$result = $db->query($sql)){
						// if no result, throw error
						throw new Exception("Error Processing Timezone Insert Request", 1);
					} else {
						// commit query to database
						$db->commit();
						// construct log text
						$log_text = 'Register User-- user_id: '.$user_id.'; username: '.$username.'; email: '.$email.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
						// write to log
						file_put_contents('../logs/user_register.txt',$log_text,FILE_APPEND);
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
		$songcircle_date 	= $songcircle->call_user_timezone($songcircle_date, $timezone);

		// make $songcircle_user_data array for emails
		$songcircle_user_data = [
			"username" 		=> $username,
			"eventTitle" 	=> $songcircle_name,
			"date_time" 	=> $songcircle_date,
			"linkParams" 	=> [
				"conference_id" 		=> $songcircle_id,
				"user_email" 				=> $email,
				"confirmation_key" 	=> $confirmation_key
			]
		];

		$confirmation_data['flag'] = true;
		$confirmation_data['username'] = $username;
		$confirmation_data['eventTitle'] = $songcircle_name;
		$confirmation_data['date_time'] = $songcircle_date;

		// if NO waiting list
		if( $waiting_list == 'false' ){
			// send registration confirmation email

			$to = "{$username} <{$email}>"; // this may cause a bug on Windows systems
			$subject = "{$songcircle_name} - Confirm your registration!";
			$from = "Songfarm <noreply@songfarm.ca>";
			if($message = constructHTMLEmail($email_data['confirmation'],$songcircle_user_data)){
				$headers = "From: {$from}\r\n";
				$headers.= "Content-Type: text/html; charset=utf-8";
				if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') ){
					// enter user into songcircle_register
					$sql = "INSERT INTO songcircle_register (songcircle_id, user_id, confirmation_key) ";
					$sql.= "VALUES ('$songcircle_id', $user_id, '$confirmation_key')";
					if($result = $db->query($sql)){
					// insert successful:
						// construct log text
						$log_text = 'Register-- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
						// write to log
						file_put_contents('../logs/user_songcircle.txt',$log_text,FILE_APPEND);
						// json encode and send confirmation data
						echo json_encode($confirmation_data);
					}
				} else {
				// email failed to send
					// construct error message
					$output = '<span>Oops!</span><br /><br />';
					$output.= 'We\'re sorry but registration for '.$songcircle_name.' on '.$songcircle_date.' could not be completed';
					$output.= '<br /><br />Please try again in a few minutes. <br>If you\'re still having trouble, please contact support at <a href="mailto:support@songfarm.ca">support@songfarm.ca</a>.';
					print $output;
				}
			} // end of: if($message = constructHTMLEmail())

		} else { // waiting list is open
			// send waiting list confirmation email

			// $to = "{$username} <{$email}>";
			// $subject = "You're on the Waiting List!";
			// $from = "Songfarm <noreply@songfarm.ca>";
			// if($message = constructHTMLEmail($email_data['waiting_list'],$songcircle_user_data)){
			// 	$headers = "From: {$from}\r\n";
			// 	$headers.= "Content-Type: text/html; charset=utf-8";
			// 	if( $result = mail($to,$subject,$message,$headers,'-fsongfarm') ){

					/*
						needs programming
					*/
					// enter user into songcircle_wait_register
					$sql = "INSERT INTO songcircle_wait_register (songcircle_id, user_id) ";
					$sql.= "VALUES ('$songcircle_id', $user_id)";
					if($result = $db->query($sql)){
					// insert successful:
						// construct log text
						$log_text = 'Wait List-- user_id: '.$user_id.'; songcircle_id: '.$songcircle_id.' ('.date('m/d/y g:iA T',time()).')'. PHP_EOL;
						// write to log
						file_put_contents('../logs/user_songcircle.txt',$log_text,FILE_APPEND);
						// create waitlist flag
						$confirmation_data['waitlist'] = true;
						// json encode and send confirmation data
						echo json_encode($confirmation_data);
					}
			// 	} else {
			// 	// email failed to send
			// 		// construct error message
			// 		$output = '<span>Oops!</span><br /><br />';
			// 		$output.= 'We\'re sorry but registration for '.$songcircle_name.' Waiting List on '.$songcircle_date.' could not be completed';
			// 		$output.= '<br /><br />Please try again in a few minutes. <br>If you\'re still having trouble, please contact support at <a href="mailto:support@songfarm.ca">support@songfarm.ca</a>.';
			// 		print $output;
			// 	}
			// }
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

/**
*	Formats and display missing values received by Registration Form
*
* @param (array) array of values
* @return (string) formatted string
*/
function displayMissingValues($array){
	$output = '<p>The following required values were missing:</p>';
	$output.= '<ul>';
	foreach ($array as $value) {
		$output.= '<li><b>'.ucfirst($value).'</b></li>';
	}
	$output.= '</ul>';
	$output.= '<p>Exiting from validation..</p>';
	echo $output;
}

?>
