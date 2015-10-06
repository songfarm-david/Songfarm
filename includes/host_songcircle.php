<?php
if(isset($_POST['submit'])) {

	$user_id = $_SESSION['user_id'];
	$songcircle_id = uniqid('');
	$songcircle_name = mysqli_real_escape_string($db, $_POST['songcircle_name']);
	$date_of_songcircle = $_POST['date_of_songcircle'].":00";
	$songcircle_permission = $_POST['songcircle_permission'];
	$participants = $_POST['max_participants'];
	$duration = $_POST['duration'];

	$sql = "INSERT INTO songcircle (";
	$sql.= "user_id, songcircle_id, songcircle_name, date_of_songcircle, songcircle_permission, participants, duration";
	$sql.= ") VALUES (";
	$sql.= "$user_id, '$songcircle_id', '$songcircle_name', '$date_of_songcircle', '$songcircle_permission', $participants, '$duration')";
	if($result = mysqli_query($db, $sql)) {
		// success
		$message = "Success! New Songcircle scheduled.";
	} else {
		$message = "Query error: " . mysqli_error($db);
	}
} else {
	$message = "";
}
?>
