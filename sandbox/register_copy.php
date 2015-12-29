<?php require('../initialize.php'); 
$errors = [];
$user_data = [];
if(isset($_POST['submit'])){
	// get the value of user_type
	$user_type = (int)$_POST["user_type"];
	// assign user_type to user_data array..
	$user_data['user_type'] = $user_type;
	// check for the presence of a user_name
	if(has_presence($_POST["user_name"])) {
		$user_name = htmlspecialchars($_POST["user_name"]);
		$user_data['user_name'] = $user_name;
	} else {
		$user_name = "";
		$errors[] = "Please enter your Artist name or real name.";
	}
	// check for the presence of an email
	if(has_presence($_POST['user_email'])) {
    // make sure email is valid
    if(is_valid_email($_POST['user_email'])) {
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
	if(has_presence($_POST['user_password'])) {
		// make sure its at least 7 characters long
		if(has_min_length($_POST['user_password'],7)) {
			// check for presence of a conf_password
			if(has_presence($_POST['conf_password'])) {
				// compare the two password for exactness
				if(is_exact($_POST['user_password'], $_POST['conf_password'])) {
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
		if(has_rows(unique_email($user_email))) {
			$messages[] = "That email address has already been registered.";
			//echo json_encode($message);
		} else {
			// insert user into the database
			if(insert_user($user_data)) {
				// success
				$messages[] = "Thanks for registering";
				$_SESSION['id'] = last_inserted_id($db);
				$_SESSION['username'] = $user_data['user_name'];
				// $message[] = true;
				// echo json_encode($message);
			} else {
				// failure
				$messages[] = "There was an error inserting into the database.";
				echo json_encode($message);
			}
		}
	} else { // if there were $errors
		$messages[] = "There were errors in the form. Not ready to proceed to database.";
		echo json_encode($message);
	}
} else {
	$user_name = $user_email = $user_password = $conf_password = $messages = "";
}
?>
<!-- Start of Registration Form -->
<div id="overlay" class="hide"></div>
<?php if($errors) { ?>
  <span>Please fix the errors below before submitting:</span>
  <ul>
    <?php foreach ($errors as $error) {
      echo "<li>{$error}</li>";
    } ?>
  </ul>
<?php } ?>
	<form id="register-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="hide">
		<img src="images/buttons/close_button_24.png">
		<div>
			<p>Please Select Your User Type:</p>
			<div class="user active" value="1">Artist</div>
			<!-- Added inactive classes for user types Industry and Fans -->
			<div class="user inactive" value="2">Industry</div>
			<div class="user inactive" value="3">Fan</div>
			<input type="hidden" id="user_type" name="user_type" value="">
		</div>
		<div id="second" class="hide">
			<p>Complete the form below to register</p>
			<input type="text" id="username" name="user_name" value="David" data-msg-required="The name field is required" placeholder="Artist Name or Real Name" required>
			<input type="email" id="useremail" name="user_email" value="<?php echo $user_email ?>" data-msg-required="The email field is required" placeholder="Email" required>
			<input type="password" id="userpassword" name="user_password" value="password" minlength="7" placeholder="Password" required>
			<input type="password" id="confpassword" name="conf_password" value="password" data-msg-required="Please confirm your password" placeholder="Confirm password" required>
			<input type="submit" name="submit" id="submitForm" value="Register Me!"><br>
			<!-- form result message -->
			<div id="message"><!--class="hide"-->
				<?php if($messages) { ?>
			    <?php foreach ($messages as $message) {
			      echo "<p>{$message}</p>";
			    } ?>
				<?php } ?>
			</div>
		</div>

	</form>
<!-- End of Registration Form -->
