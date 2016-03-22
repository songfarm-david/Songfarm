<?php
// if user has a location in the database
// skip timezone selection and display songcircles
if($user->hasLocation($session->user_id)) {
	// set songcircle timezone to user timezone
	$songcircle->timezone = $user->timezone;
	// display songcircles
	$songcircle->displaySongcircles();
}
else // if user does NOT have a location set, display a countries select dropdown
{
?>
<div id="overlay"></div>

<!-- div which holds the timezone dialog -->
<div id="timezone-container">

	<?php
	// if the system receives a country name from generate_ip_data()
	if(!empty($country_name)){ ?>
	<h1>Timezone based on <span class=country><?php echo $country_name ?></span></h1>
	<?php } ?>

	<p>Please select your country and most accurate timezone from the list to begin..</p>

	<!-- Timezone form -->
	<?php include_once('../includes/forms/timezone_form.php'); ?>
	<!-- end of Timezone form -->

	<!-- if generate_ip_data returns a country code, put into value of hidden input -->
	<input type="hidden" name="country_code" value="<?php if(isset($country_code)){echo $country_code;} ?>">

</div>
<?php	} // end of else user does NOT have location ?>
