<?php require_once('initialize.php');
/**
*	This page receives user submitted data from songcircle.php (public)
*
*	It will attempt to register a user for a specific songcircle
* If user is already registered for given songcircle, return error
*
*	Updated: 01/07/2016
*/

// expected array AND required array init
$requiredData = array('username','user_email','timezone','fullTimezone','country_name','country_code', 'codeOfConduct');
// init array for missing keys
// $missing = array();

// NOTE: cannot use name of 'submit' value
if(isset($_POST['formData']) && isset($_POST['songcircleId'])){
	// do validations

	// explode string into array
	$formDataArray = explode('&',$_POST['formData']);

	// loop through array and clean values
	foreach ($formDataArray as $value) {
		// remove anything after '='
		$value = substr($value, 0, strpos($value,'='));
		// put cleaned values into a new array
		$formData[] = $value;
	}

	// check for difference between $formData and $requiredData
	if($diff = array_diff($requiredData, $formData)){
		// if diff, return error
			/* function for displaying which values were missing */
			displayMissingValues($diff);

		/* exit validation.. */
		return false;

	} else {
		// all required fields are present, continue on...

		// init array $cleanData and $errors
		$cleanData = $errors = array();

		// sanitize data into $cleanData array
		foreach ($formDataArray as $key => $value) {
			$cleanData[$key] = $db->escape_value(urldecode($value));
		}

		// assign variables to each field in form
	 	$username				=	explode('=',$cleanData[0])[1];
	 	$email					=	explode('=',$cleanData[1])[1];
		$timezone 			= explode('=',$cleanData[2])[1];
		$fullTimezone		=	explode('=',$cleanData[3])[1];
	 	$country_name		= explode('=',$cleanData[4])[1];
		$country_code		=	explode('=',$cleanData[5])[1];
		$codeOfConduct 	= explode('=',$cleanData[6])[1];

		// assign variable to songcircleId
		$songcircleId = $_POST['songcircleId'];

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

			// get user ID that pertains to given email
			$user_id = $db->getIDbyEmail($email);
			// set type to integer
			settype($user_id, 'int');
			// check function to see if user is already registered for this email
			if($songcircle->alreadyRegistered($songcircleId, $user_id)){
				$is_already_registered = true;
				$errors['email_error'] = 'That email is already registered for this songcircle.';
			}

		}	else {
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
			// If user is not registered in the database
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

					/* need a system whereif the second insert fails, it rolls back the first insert
					and exits the whole process */

					// NOTE: Look into transactions, roll backs, etc..

				} else {
					//echo 'timezone insert was successful<br>';
				}

			} // end of: apparent insert

		} // end of: elseif (!$emailInDatabase)

		// register user for songcircle

		// create array of data pertaining to the registered songcircle
		$songcircle_data = [];

		// to return successfully to Ajax
		$songcircle_data['flag'] = true;

		// user timezone from registration form
		$songcircle_data['user_timezone'] = $user->clean_city($timezone); // formats timezone into friendly format

		// get songcircle information...
		$sql = "SELECT songcircle_name, date_of_songcircle ";
		$sql.= "FROM songcircle_create WHERE songcircle_id = '$songcircleId'";
		if($result = $db->query($sql)){
			// if result, fetch row array
			$row = $db->fetch_array($result);

			$songcircle_data['name'] = $row['songcircle_name'];
			$songcircle_data['date_time'] = $songcircle->call_user_timezone($row['date_of_songcircle'], $timezone);

			// call $songcircle->register() function
			if($songcircle->register($songcircleId, $user_id, $username, $songcircle_data['name'], $row['date_of_songcircle'], $timezone, $country_name)){

				// registration successful
				echo json_encode($songcircle_data);

			} else {
				// registration failed
				// construct error message
				$output = '<span>Oops!</span><br /><br />';
				$output.= 'We\'re sorry but we could not register you for <b>'.$songcircle_data['name'].'</b> on '.$songcircle_data['date_time'];
				$output.= '<br /><br />Please try again in a few minutes. <br>If you\'re still having trouble, please contact support at <a href="mailto:support@songfarm.ca">support@songfarm.ca</a>.';
				print $output;

			}

		} // end of: if($result = $db->query($sql)) SELECT songcircle_name, date_of_songcircle...

	} // end of: else ($diff = array_diff($requiredData, $formData))

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
