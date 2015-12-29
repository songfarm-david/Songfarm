<?php require_once("../includes/initialize.php");
/* user image */
$image->user_id = $session->user_id;
if(isset($_POST['submit_image'])){
	$image->upload_image($_FILES['file_upload']);
} elseif ($session->user_id) {
	$image->retrieve_user_photo();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Test Image Upload Page</title>
	<style>.user_image{
	  width: 100px;
	  height: 100px;
	  border-radius: 50%;
	  background-repeat: no-repeat;
	  background-position: center center; /* Center image in the circle */
	  background-size: cover; /* Make sure it covers the circle, as there is no bg repeat*/
	  border:1px solid black;
	  position:absolute;
		top: 150px;
	  left:25px;
	  transition-duration: .250s;
	}</style>
</head>
<body>
	<h1>Test Image Upload Page</h1>
	<header>
		<div class="user_image" style="background-image:url('../uploaded_images/<?php echo $image->image_name; ?>')">
			<!-- Is there a better way to access these pictures?? Absolute Path? -->
		</div>
		<!-- Input form for user_image -->
		<form id="upload_user_image" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="hide">
			<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
			<input type="file" name="file_upload">
			<input type="submit" name="submit_image" value="Upload">
			<?php if($image->photo_errors) { ?>
				<span>Photo error:</span>
				<ul>
					<?php foreach ($image->photo_errors as $error) {
						echo "<li>{$error}</li>";
					} ?>
				</ul>
			<?php } ?>
		</form>
	</header>
</body>
</html>
