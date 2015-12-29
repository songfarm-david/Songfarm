<?php // if user submits photo
$photo_errors = [];
$php_upload_errors = array(
		0 => 'There is no error, the file uploaded with success',
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk.',
		8 => 'A PHP extension stopped the file upload.',
);


if(isset($_POST['submit_image'])) {
	// make sure uploaded file is valid and not empty
	if(!$_FILES['file_upload'] || empty($_FILES['file_upload']) || !is_array($_FILES['file_upload'])) {
		$photo_errors[] = "No file was uploaded";
	}	elseif (!valid_photo_filename($_FILES['file_upload']['name'])) {
		$photo_errors[] = "Invalid characters in file name. Only characters, numbers and (_-.) allowed.";
		$image_name = "";
	}	elseif ($_FILES['file_upload']['error'] != 0) {
		foreach($php_upload_errors as $key => $value) {
			if($_FILES['file_upload']['error'] == $key) {
				$photo_errors[] = $value;
				$image_name = "";
			}
		}
	} elseif (!is_valid_extension($_FILES['file_upload']['name'])) {
		$photo_errors[] = "Files must be of either type jpeg, jpg, jpe, gif or png.";
		$image_name = "";
	}
	else // if no errors..
	{
		// target directory of file system
		$target_dir = SITE_ROOT.DS."uploaded_images";
		// specify attribute variables
		$user_id 		= $_GET['id'];
		$filename 	= uniqid('', true); // creates unique id
		$type 			= str_replace('image/','.',$_FILES['file_upload']['type']);
		$size 			= $_FILES['file_upload']['size'];
		$tmp_name 	= $_FILES['file_upload']['tmp_name'];
		// check if an image already exists
		$sql = "SELECT * FROM user_photo WHERE id = $user_id";
		$result = $db->query($sql);
		$res_array = $db->fetch_array($result);
		$old_filename = $res_array['filename'];
		$old_type = $res_array['type'];
		if($db->has_rows($result) != 0){ // if a row exists
			// try to delete the existing file from the file system, if fails...
			if(file_exists($target_dir.DS.$old_filename.$old_type)) {
				if(!unlink($target_dir.DS.$old_filename.$old_type)){
					 $photo_errors[] = "Could not delete existing file in file system. Update failed.";
				} else {
					$sql = "UPDATE user_photo ";
					$sql.= "SET filename = '$filename', type = '$type', size = $size ";
					$sql.= "WHERE user_id = $user_id";
					if($result = $db->query($sql)) {
						if(!move_uploaded_file($tmp_name, "$target_dir/$filename$type")){
							$photo_errors[] = "Error moving file to file system";
						} else {
							$image_name = $filename.$type;
						}
					} else {
						$photo_errors[] = "There was an error updating your profile picture. Please try again at a later time.";
						$image_name = "";
					}
				}
			} else {
				$image_name = "";
			}// end of: if(file_exists);
		} else {	// if no rows exist perform an insert
			if(file_exists($target_dir.DS.$filename.$type)){
				$photo_errors[] = "This photo has already been uploaded";
			} elseif(!move_uploaded_file($tmp_name, "$target_dir/$filename$type")){
				$photo_errors[] = "There was an error uploading your file. Please try again, or upload a different image";
			} else {
				$sql = "INSERT INTO user_photo (";
				$sql.= "user_id, filename, type, size ";
				$sql.= ") VALUES (";
				$sql.= "$user_id, '$filename', '$type', $size)";
				if($result = $db->query($sql)){
					if(mysqli_affected_rows($db->connection) == 0){
						$photo_errors[] = "There was an error inserting your file into our records. Please try again at a later time.";
					} else {
						// success
						$image_name = $filename.$type;
					}
				}
			}
		}
	} // end of: if no file upload errors
} else {
	## retrieve user photo from file system
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$user_id = $_GET['id'];
		$sql = "SELECT filename, type FROM user_photo WHERE ";
		$sql.= "user_id = $user_id";
		$result = query_db($sql);
		if(mysqli_num_rows($result) != 0){
			$res_array = fetch_array($result);
			$filename = $res_array['filename'];
			$type = str_replace('image/','',$res_array['type']);
			$image_dir = SITE_ROOT.DS."uploaded_images".DS;
			if(!file_exists($image_dir.$filename.$type)){
				// $photo_errors[] = 'We don\'t have a profile picture in our systems of you. You should upload one.';
				$image_name = "";
			} else {
				$image_name = $filename.$type;
			}
		} else {
			$image_name = "";
			// here you could assign a standard image prompting upload of a profile pic
		}
	} // end of: if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
	$image_name = ""; // should be an image that says 'Post Your Photo'
}
?>
