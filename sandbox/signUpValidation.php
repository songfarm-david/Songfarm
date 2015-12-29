<?php
session_start();
include 'db_connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$artist_name     = mysqli_real_escape_string($db, $_POST['artist_name']);
  $artist_email    = filter_var($_POST["artist_email"],FILTER_SANITIZE_EMAIL);
  $artist_password = mysqli_real_escape_string($db, $_POST['artist_password']);
  $conf_password   = mysqli_real_escape_string($db, $_POST['conf_password']);

  // test password for sameness
  if($artist_password !== $conf_password){
    echo "Password's don't match";
    die();
  } else {
    $hash_pass = password_hash($conf_password, PASSWORD_DEFAULT);
  }

  // check that artist_email is unique
  $query  = "SELECT id FROM sign_up WHERE artist_email = '$artist_email'";
  $result = mysqli_query($db, $query) or trigger_error("Query: $query \n <br /> MySql Error: " . mysqli_errno($db));

  // if there is a result
  if(mysqli_num_rows($result)){
    echo 'There already exists a user with that email. Please choose a unique email';
  } else {

    // insert values into database
  	$query   = "INSERT INTO sign_up (artist_name, artist_email, artist_password, reg_date) ";
    $query  .= "VALUES ('$artist_name','$artist_email', '$hash_pass', NOW())";
  	$result  = mysqli_query($db, $query) or trigger_error("Query: $query \n <br /> MySql Error: " . mysqli_errno($db));

    // test for successful database entry
  	if($result){
			$_SESSION['username'] = $artist_name;
  		header('location: workshop.php');
  	} else {
  		echo 'There was an error with your sign up!';
  	}

  } // end of INSERT INTO else


} // end of IF(SERVER_REQUEST == POST)


?>
