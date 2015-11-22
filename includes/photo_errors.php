<?php include('../includes/initialize.php');

// recieves Ajax request from user_header.php
if(isset($_FILES['file_upload'])){
	$image = new Image();
	$image->upload_image($_FILES['file_upload'], $session->user_id);
	if($image->photo_errors){
		echo "<div class=\"errorsDiv\">";
		echo "<p>The following errors occurred:</p>";
		echo "<ul>";
		foreach($image->photo_errors as $errors){
			echo "<li>{$errors}</li>";
		}
		echo "</ul>";
		echo "</div>";
	}
} else {
	echo "<div class=\"errorsDiv\">";
	echo "<p>There was an error uploading your file. Please make sure that:</p>";
	echo "<ul>";
	echo "<li>File is of either type jpeg, jpg, jpe, gif or png</li>";
	echo "<li>File does not exceed 2MB</li>";
	echo "</ul>";
	echo "</div>";
}

?>
