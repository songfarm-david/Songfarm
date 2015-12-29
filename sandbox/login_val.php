<?php
// session_start(); require_once('initialize.php');

// if(isset($_POST['submit'])) {
// 	// check if value is present as is email
// 	if(has_presence(is_valid_email($_POST['username']))) {
// 		// sanitize username
// 		$username = escape_value($_POST['username']);
// 	}	elseif(has_presence($_POST['username'])) {
// 		// sanitize username
// 		$username = escape_value($_POST['username']);
// 	} else {
// 		$username = "";
// 		$errors[] = "Please enter your username or email to log in";
// 	}
// 	// check for presence of password
// 	if(has_presence($_POST['password'])) {
// 		//sanitize password
// 		$password = escape_value($_POST['password']);
// 	} else {
// 		$errors[] = "Please enter your password";
// 	}
	// if $errors array is empty
// 	if(empty($errors)){
// 		// check that username exists
// 		if(has_rows($result = user_name_exists($username))) {
// 			// fetch result information
// 			$result = fetch_array($result);
// 			// compare passwords
// 			if(password_verify($password, $result['user_password'])) {
// 				$_SESSION['user_id'] = $result['user_id'];
// 				$_SESSION['username'] = $result['user_name'];
// 				$_SESSION['permission'] = $result['permission'];
// 				$_SESSION['logged_in'] = true;
// 				$messages[] = $result['user_id'];
// 				$messages[] = true;
// 				echo json_encode($messages);
// 			} else {
// 				$messages[] = "Incorrect password";
// 				echo json_encode($messages);
// 			}
// 		} else {
// 			// no result found
// 			$messages[] = "The username you entered is not registered";
// 			echo json_encode($messages);
// 		}
// 	} // end of: if(empty($errors))
// } else { // end of: isset($_POST['submit'])
// 	$username = $messages = "";
// }
?>
