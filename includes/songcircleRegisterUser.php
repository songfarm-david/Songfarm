<?php require_once('initialize.php');
/**
*	This page receives user submitted data from public/songcircle.php
*
*	It will attempt to register a user for a specific songcircle
* If user is not registered, it will register them in user_register SQL table as well
*
*	Last Updated: 01/11/2016
*/
// NOTE: cannot use name of 'submit' value
if(isset($_POST['formData']) && isset($_POST['songcircleData'])){

	// required data from user
	$required_data = array('username','user_email','timezone','fullTimezone','country_name','country_code', 'codeOfConduct');

	// explode string into array
	$form_data_array = explode('&',$_POST['formData']);

	// loop through array and clean values
	foreach ($form_data_array as $value) {
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

		// init array $clean_data and $errors
		$clean_data = $errors = array();

		// sanitize data into $clean_data array
		foreach ($form_data_array as $key => $value) {
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

		// assign variable to songcircleId
		$songcircle_id 		= $_POST['songcircleData'][0];
		$songcircle_title = $_POST['songcircleData'][1];
		$songcircle_date = str_replace('- ','at',$_POST['songcircleData'][2]);


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
		elseif ( $db->has_rows($db->unique_email($email)) ){

			// flag if email is already in database
			$emailInDatabase = true;

			// check user ID against email in user_register table
			$user_id = $db->getIDbyEmail($email);
			// set type to integer
			settype($user_id, 'int');

			// check function to see if user is already registered for this songcircle
			if($songcircle->userAlreadyRegistered($songcircle_id, $user_id)){
				// echo $user_id;
				$is_already_registered = true;
				$errors['email_error'] = 'That email is already registered for this songcircle.';
			}
		}	else {
			// email is not in database
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

		} elseif (!$emailInDatabase) { // if emailInDatabase is false
			// User is not registered in the database

			// insert user into database
			$sql = "INSERT INTO user_register (user_name, user_email, reg_date)";
			$sql.= "VALUES ('$username', '$email', NOW())";
			if($result = $db->query($sql)){
				// if result, get last inserted id
				$user_id = $db->last_inserted_id($result);
				// insert timezone into database
				$sql = "INSERT INTO user_timezone (user_id, timezone, full_timezone, country_name, country_code) "; // city_name omitted
				$sql.= "VALUES ($user_id, '$timezone', '$fullTimezone', '$country_name', '$country_code')";
				// check for successful
				if(!$db->query($sql)){
					echo 'error inserting';
					/* need a system whereif the second insert fails, it rolls back the first insert
					and exits the whole process */

					// NOTE: Look into transactions, roll backs, etc..

				}

			} // end of: INSERT INTO user_register

		} // end of: elseif (!$emailInDatabase)

		/*
		So, no errors..
		User either was already IN the database or is NOW in the database

		/*
		So now enter user into songcircle_register table
		*/

		// create unique confirmation key
		$confirmation_key = getToken(40);

		// enter user into songcircle_register
		$sql = "INSERT INTO songcircle_register (songcircle_id, user_id, confirmation_key) ";
		$sql.= "VALUES ('$songcircle_id', $user_id, '$confirmation_key')";
		if($result = $db->query($sql)){
		// insert successful:

			/* for testing purposes */
			// $confirmation_data['flag'] = true;
			// $confirmation_data[] = 'User successfully registered. (Email would send here)';
			// // json encode and send confirmation flag
			// echo json_encode($confirmation_data);
			/* end of test */

			// attempt to send confirmation email
			$to = "{$username} <{$email}>"; // this may cause a bug on Windows systems
			$subject = "Registration Confirmation: {$songcircle_title}";
			$from = "Songfarm <noreply@songfarm.ca>";
			// construct message
			$message = "You have registered for {$songcircle_title} on {$songcircle_date}.\r\n\r\n";
			$message.= "Please confirm your registration by clicking the link below:\r\n\r\n";
			$message.= "http://test.songfarm.ca/includes/songcircleConfirmUser.php?";
			$message.= "conference_id={$songcircle_id}&user_email={$email}&confirmation_key={$confirmation_key}";
			$message.= "\r\n\r\n";
			$message.= "If you have received this email by mistake, please click the following link to unregister from this Songcircle:\r\n";
			$message.= "http://test.songfarm.ca/includes/songcircleRemoveUser.php?conference_id={$songcircle_id}&user_email={$email}";
			// construct headers
			$headers = "From: {$from}\r\n";
			$headers.= "MIME-Version: 1.0\r\n"; // unsure of this?
			$headers.= "Content-Type: text/plain; charset=utf-8"; // unsure of this, too
			/* use 'X-' ... in your headers to append non-standard headers */
			if($result = mail($to, $subject, $message, $headers, '-fsongfarm')){ // 5th arg. possible bug
				// email sent
				// create confirmation flag
				$confirmation_data['flag'] = true;
				// json encode and send confirmation flag
				echo json_encode($confirmation_data);
			} else {
			// email failed to send
				// construct error message
				$output = '<span>Oops!</span><br /><br />';
				$output.= 'We\'re sorry but registration for '.$songcircle_title.' on '.$songcircle_date.' could not be completed';
				$output.= '<br /><br />Please try again in a few minutes. <br>If you\'re still having trouble, please contact support at <a href="mailto:support@songfarm.ca">support@songfarm.ca</a>.';
				print $output;
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
