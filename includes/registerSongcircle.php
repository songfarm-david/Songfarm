<?php require_once('initialize.php');
/**
*	This page receives user submitted data from songcircle.php (public)
*
*	It will register a user for a specific pre-set songcircle and
* increment the registered user number
*
*/

// NOTE: cannot use name of 'submit' value
if(isset($_POST['formData']) && isset($_POST['songcircleId'])){
	// do validations
	// print_r($_POST['formData']);

		$data = [];
		$formData = explode('&',$_POST['formData']);

		foreach ($formData as $key => $value) {
			$data[$key] = $db->escape_value(urldecode($value));
		}

		$username			=	explode('=',$data[0])[1];
		$email				=	explode('=',$data[1])[1];
		$timezone 		= explode('=',$data[2])[1];
		$fullTimezone	=	explode('=',$data[3])[1];
		// $city_name		=	ucfirst(explode('=',$data[4])[1]); // turn all first letters into uppercase
		$country_name	= explode('=',$data[5])[1];
		$country_code	=	explode('=',$data[6])[1];

		$songcircleId	=	$_POST['songcircleId'];

		$errors = [];

		// if NOT has presence
		if(!$db->has_presence($username)){
			$errors[] = "Please make sure to enter your name";
		}

		// if NOT has presence and is NOT valid email
		if(!$db->has_presence($email)) {
			$errors[] = "Please enter your email";
		} elseif (!$db->is_valid_email($email)) {
			$errors[] = 'Email entered is not a valid email';
		}
		elseif ($db->has_rows($db->unique_email($email)))
		{ // if duplicate email
			$errors[] = 'That email has already been registered';
		}

		// if NOT has presence timezone -- this error should not occur
		if(!$db->has_presence($timezone)){
			$errors[] = 'There was an error receiving your timezone. Please go back and make sure to enter one.'; // un-usable?
		}

		// if city NOT has presence
		// if (!$db->has_presence($city_name)) {
		// 	$errors[] = 'Please enter a city name';
		// }

		// if NOT has presence
		if (!$db->has_presence($country_name)) {
			$errors[] = 'Please select a country';
		}

		// if NOT has presence
		if (!$db->has_presence($country_code)) {
			// unnecessary??
			return false;
		}

		// make sure there is adequate validation for timezone, city, country and country code

		// if errors
		if($errors){
			$output = '<ul>';
			foreach ($errors as $error) {
				$output.= '<li>'.$error.'</li>';
			}
			$output.= '</ul>';
			echo $output;
		} else {
			// do insertion

			// user_type set to NULL for testing
			// $user_type = 1;

			$sql = "INSERT INTO user_register (user_name, user_email, reg_date)";
			$sql.= "VALUES ('$username', '$email', NOW())";
			if(!$result = $db->query($sql)){
				// if no result
				return false;
			} else { // get last inserted id
				$user_id = $db->last_inserted_id($result);
				// perform second insert
				$sql = "INSERT INTO user_timezone (user_id, timezone, full_timezone, country_name, country_code) "; // city_name omitted
				$sql.= "VALUES ($user_id, '$timezone', '$fullTimezone', '$country_name', '$country_code')";
				if(!$db->query($sql)){
					// if error..
					return false;
				} else {
					// return notification here...

					// create array of data pertaining to the registered songcircle
					$songcircle_data = [];

					// to return successfully to Ajax
					$songcircle_data['flag'] = true;

					// user timezone from registration form
					$songcircle_data['user_timezone'] = $user->clean_city($timezone); // formats timezone into friendly format

					// get songcircle information...
					$sql = "SELECT songcircle_name, date_of_songcircle ";
					$sql.= "FROM songcircle_create WHERE songcircle_id = '$songcircleId'";
					if(!$result = $db->query($sql)){
						return false;
					} else {
						// put rows results into $row
						$row = $db->fetch_array($result);

						$songcircle_data['name'] = $row['songcircle_name'];
						$songcircle_data['date_time'] = $songcircle->call_user_timezone($row['date_of_songcircle'], $timezone);

						// register for the songcircle

						if($songcircle->register($songcircleId, $user_id, $username, $songcircle_data['name'], $row['date_of_songcircle'], $timezone, $country_name)){
							// registration successful
							echo json_encode($songcircle_data);
						} else {
							// registration failed

							// construct error message
							echo 'Unknown system error<br /><br />: ';
							echo 'Could not register <b>'.$username.'</b> for <b>'.$songcircle_data['name'].'</b>';
							echo '<br /><br />Please try again later or <a href="#">contact us directly.</a>'; // put in a valid email

						}
					}

					/*
						Send email:
						/ get user email
					*/
					//console.log('sending email here...');

					/*
						Construct message to ajax
						/ the message:
						You have successfully registered for (name) on (date)
						at (time), (timezone)

						Please check your email for all the details plus tips
						on how to make the most out of this upcoming Songcircle!
					*/

					// these 3 values come from songcircle table
				}
			}
		}

} // end of: if(isset($_POST['formData']) && isset($_POST['songcircleId'])){

?>
