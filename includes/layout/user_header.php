<header>
<?php
// if profile
if( strstr($_SERVER['PHP_SELF'], 'profile') ){
	$image->retrieve_user_photo($_GET['id']);
	$user->has_location($_GET['id']); ?>
<?php
// if workshop
 }	else { ?>
	<?php
	if($image->message){ echo $image->message; }
	echo isset($_FILES['file_upload']) ? $image->upload_image($_FILES['file_upload'], $session->user_id) : $image->retrieve_user_photo($session->user_id); ?>
	<form id="upload_user_image" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$session->user_id ?>" method="post" enctype="multipart/form-data" class="hide">
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
		<p>
			Please select a profile photo
		</p>
		<input type="file" name="file_upload">
		<input type="submit" name="submit_image" value="Upload">
		<br>
		<p class="cancel">Cancel</p>
		<?php if(isset($image->photo_errors) && !empty($image->photo_errors)){ ?>
			<div>
				<ul>
				<?php foreach($image->photo_errors as $error){
						echo "<li>{$error}</li>";
					} ?>
				</ul>
			</div>
		<?php } ?>
	</form>
<?php } ?>
<!-- following code applies to both pages -->
	<div class="user_image" style="background-image:url('../uploaded_images/<?php echo $image->image_name; ?>')"></div>
	<h1><?php echo $session->username; ?></h1>
	<?php
	if(isset($user->city)){ echo "<span style=\"font-weight:bold;\">".$user->city; }
	if(isset($user->country)){ echo ", ".$user->country;	}
	echo "</span>";
	?>
</header>
<script>
// if user clicks on div.user_image
$('div.user_image').on('click', function(){
	// show image upload form and overlay
	$('form#upload_user_image, div#overlay').fadeIn('fast').removeClass('hide');
});
// if user click on 'cancel'
$('form#upload_user_image p.cancel').on('click', function(){
	// hide form and overlay
	$('form#upload_user_image, div#overlay').fadeOut('fast', function(){
		$('form#upload_user_image div').remove();
	}).addClass('hide');
});

// on form submission
$("form#upload_user_image").submit(function( event ) {
	event.preventDefault();
	//grab all form data
  var formData = new FormData($(this)[0]);
	var user_id = "<?php echo $_SESSION['user_id'] ?>"
	$.ajax({
		url : '../includes/photo_errors.php',
		method : 'POST',
		data : formData,
		processData: false,
		contentType: false,
		cache: false,
		success: function(data){
			// if errors came back
			if(data){
				// if the errors div exists, remove contents before appending new data
				if($('div.errorsDiv').length){
					$('div.errorsDiv').remove();
					$('form#upload_user_image').append(data);
				} else {
					$('form#upload_user_image').append(data);
				}
				return false;
			} else {
				// if no errors came back
				$("form#upload_user_image").unbind().submit();
				return;
			}
		}
	}); // end of ajax
});
</script>
