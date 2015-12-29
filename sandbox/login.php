<?php
session_start();
include 'db_connect.php';

$name = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	// collect login form submission
	$username 	= mysqli_real_escape_string($db, $_POST['username']);
	$useremail 	= filter_var($_POST["username"],FILTER_SANITIZE_EMAIL);
	$password 	= mysqli_real_escape_string($db, $_POST['password']);

	// check that the user name exists
	$query 	= "SELECT id FROM sign_up WHERE artist_name = '$username' OR artist_email = '$useremail'";
	$result = mysqli_query($db, $query);

	if(!mysqli_num_rows($result))
	{
		echo 'Sorry. We don\'t have any users by that name';
	}
	else
	{
		// retrieve existing password based on username
		$query 	= "SELECT artist_password, artist_name FROM sign_up WHERE artist_name = '$username' OR artist_email = '$useremail'";
		$result = mysqli_query($db, $query);

		$fetched_array = mysqli_fetch_array($result);
		$retrieved_password = $fetched_array['artist_password'];
		$session_name = $fetched_array['artist_name'];

		// verify password
		if( password_verify($password, $retrieved_password) ) {
			$_SESSION['username'] = $session_name;
			header('location: workshop.php');
		}
		else {
			echo 'Your password is incorrect';
			$name = $username;
		}
	}
}


?>
