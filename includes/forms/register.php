<?php require_once('../includes/initialize.php');

$errors = $user_data = [];

if(isset($_POST['register_submit'])){
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	// get the value of user_type
	$user_data['user_type'] = (int) $_POST["user_type"];
	// check for the presence of a user_name
	if($db->hasPresence($_POST["user_name"])) {
		$user_name = htmlspecialchars($_POST["user_name"]);
		$user_data['user_name'] = $user_name;
	} else {
		$user_name = "";
		$errors[] = "Please enter your Artist name or real name.";
	}
	// check for the presence of an email
	if($db->hasPresence($_POST['user_email'])) {
    // make sure email is valid
    if($db->isValidEmail($_POST['user_email'])) {
      // assign clean, valid 'email' variable
      $user_email = htmlspecialchars($_POST['user_email']);
			$user_data['user_email'] = $user_email;
    } else {
      $user_email = htmlspecialchars($_POST['user_email']);
      $errors[] = "Please enter a valid email address";
    }
  } else {
    $user_email = "";
    $errors[] = "Please enter an email address";
  }
	// check for presence of a password
	if($db->hasPresence($_POST['user_password'])) {
		// make sure its at least 7 characters long
		if($db->hasMinLength($_POST['user_password'],7)) {
			// check for presence of a conf_password
			if($db->hasPresence($_POST['conf_password'])) {
				// compare the two password for exactness
				if($db->strIsExact($_POST['user_password'], $_POST['conf_password'])) {
					// passwords match
					$user_password = htmlspecialchars($_POST['conf_password']);
					$conf_password = htmlspecialchars($_POST['conf_password']);
					// hash protect password
					$hash_password = password_hash($user_password, PASSWORD_DEFAULT);
					$user_data['user_password'] = $hash_password;
				} else {
					$user_password = "";
					$conf_password = "";
					$errors[] = "Your passwords didn't match";
				}
			} else {
				$user_password = htmlspecialchars($_POST['user_password']);
				$conf_password = "";
				$errors[] = "Please confirm your password";
			}
		} else {
			$user_password = htmlspecialchars($_POST['user_password']);
			$conf_password = "";
			$errors[] = "Your password has to be at least 7 characters long";
		}
	} else {
		$user_password = "";
		$conf_password = "";
		$errors[] = "Please enter a password";
	}
	// if no errors, proceed to database
	if(empty($errors)){
		// check if email is unique
		if($db->hasRows($db->uniqueEmail($user_email))) {
			$messages[] = "That email address has already been registered.";
			// echo json_encode($messages);
		} else {
			// insert user into the database
			if($db->insertUser($user_data)) {
				// success
				$messages[] = "Thanks for registering!";
				$_SESSION['id'] = $db->lastInsertedID();
				$_SESSION['username'] = $user_data['user_name'];
				// $message[] = true;
				// echo json_encode($message);

				// NOTE: HERE WE SEND AN EMAIL ...

			} else {
				// failure
				$messages[] = "There was an error inserting into the database.";
				// echo json_encode($message);
			}
		}
	} else { // if there were $errors
		$messages[] = "There were errors in the form. Not ready to proceed to database.";
		// echo json_encode($message);
	}
} else {
	$user_name = $user_email = $user_password = $conf_password = $messages = "";
}
?>
<!-- Start of Registration Form -->
<div id="overlay" class="hide"></div>
<?php if($errors) { ?>
  <div class="errors_php">
		<span>Please fix the errors below before submitting:</span>
	  <ul>
	    <?php foreach ($errors as $error) {
	      echo "<li>{$error}</li>";
	    } ?>
	  </ul>
	</div>
<?php } ?>
	<form id="register-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="hide">
		<img src="images/buttons/close_button_24.png">
		<div>
			<p>Please Select Your User Type:</p>
			<div class="user active" value="1">Artist</div>
			<!-- Added inactive classes for user types Industry and Fans -->
			<div class="user active" value="2">Industry</div>
			<div class="user active" value="3">Fan</div>
			<input type="hidden" id="user_type" name="user_type" value="">
		</div>
		<div id="second" class="hide">
			<p>Complete the form below to register</p>
			<input type="text" id="username" name="user_name" value="<?php echo $user_name ?>" placeholder="Artist Name or Real Name" ><!--data-msg-required="The name field is required" required-->
			<input type="text" id="useremail" name="user_email" value="<?php echo $user_email ?>" placeholder="Email" ><!--data-msg-required="The email field is required" required type="email"-->
			<input type="password" id="userpassword" name="user_password" value="<?php echo $user_password ?>" placeholder="Password"  minlength="7"><!-- required -->
			<input type="password" id="confpassword" name="conf_password" value="<?php echo $conf_password ?>" placeholder="Confirm password" ><!-- data-msg-required="Please confirm your password" required -->
			<input type="submit" name="register_submit" id="submitForm" value="Register Me!"><br>
			<!-- form result message -->
			<div id="message" class="hide">
				<?php if($messages) { ?>
					<?php foreach ($messages as $message) {
						echo "<p>{$message}</p>";
					} ?>
				<?php } ?>
			</div>
		</div>
	</form>
<!-- End of Registration Form -->
