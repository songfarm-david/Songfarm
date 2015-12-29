<?php require_once("../includes/initialize.php");

if($country_array = generate_ip_data()){
	// ip address generates a country code and country name
	list($country_code, $country_name, $city_name) = $country_array;
}

// var_dump($_POST);


?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Songcircle - Virtual Songwriter's Circle</title>
        <meta name="description" content="Share your newest song in a live virtual songwriter's circle">
        <!-- a meta "description" can and should be included on each independent page to DESCRIBE it -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta property="og:url" content="http://www.songfarm.ca/songcircle.php">
        <meta property="og:title" content="Songcircle - Virtual Songwriter's Circle">
        <meta property="og:description" content="Participate in a virtual songwriter's circle and workshop your songs with other songwriters - all from the comfort of your home. Register Today!">
        <meta property="og:image" content="http://www.songfarm.ca/images/songfarm_logo_l.png">
        <meta property="og:image:width" content="1772">
        <meta property="og:image:height" content="1170">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/songcircle.css">
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <!-- <script type="text/javascript" src="//platform.linkedin.com/in.js">
            api_key:   77fxwmu499ca9c
            authorize: true
        </script> -->
        <!--[if lt IE 9]>
          <script src="js/html5-shiv/html5shiv.min.js"></script>
          <script src="js/html5-shiv/html5shiv-printshiv.min.js"></script>
          <script src="js/respond.js" type="text/javascript"></script>
          <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <![end if]-->

    </head>
		<body>
      <?php include("../includes/layout/header.php") ?>
      <section>
        <article><!-- holds background image -->
          <!-- Introduction to Songcircle -->
          <p>
            Workshop your latest song in a <span class="bold">professional environment</span> with other songwriters just like you!
          </p>
          <p>
            Register for a Songcircle today!
          </p>
          <!-- <h1>Songcircle</h1>
          <h2>What is a Songcircle?</h2>
          <p>
            A Songcircle is a virtual songwriter's circle - a group of songwriters coming together, sharing their songs and ideas, inspiring and helping one another, and having a good time doing it - all done over the web.
          </p>
          <p>
            The <strong>Songcircle</strong> is one of Songfarm's trademark features. To participate in one, all you need is an internet connection, a webcam and a song.
          </p> -->
        </article>
        <article>
          <!-- Schedule of Songcircles -->
          <h2>Upcoming Songcircles:</h2>
          <?php
          $songcircle->open_songcircle_exists();
          $songcircle->display_songcircles();
          ?>

        </article>
        <article>
          <h3>What is a Songcircle?</h3>
          <p>
            A Songcircle is a professional songwriter's circle done over the web.
          </p>
        </article>
      </section>
      <!-- <article id="songcircle"> -->
        <!-- <h1>Songcircle</h1> -->
        <!-- <section style="border:1px solid black;">
          <h2>What is a Songcircle?</h2>
          <p>
            A Songcircle is a virtual songwriter's circle - a group of songwriters coming together, sharing their songs and ideas, inspiring and helping one another, and having a good time doing it - all done over the web.
          </p>
          <p>
            The <strong>Songcircle</strong> is one of Songfarm's trademark features. To participate in one, all you need is an internet connection, a webcam and a song.
          </p>
          <img src="images/buttons/register_m.png" class="register">
          <p>
            <span class="register">Register Today</span> and take part in the next Songcircle!
          </p>
        </section> -->
        <!-- <aside id="schedule">
          <h3>Scheduled Songcircles:</h3>
          <p>
            None scheduled currently.
          </p>
        </aside> -->
      <!-- </article> -->
			<!-- Use RDFa to define songcircle events // schema.org -->

      <?php include("../includes/layout/footer.php") ?>
      <?php //require_once(LIB_PATH.DS."forms/register.php"); ?>
      <!-- <script type="text/javascript" src="js/jquery.validate.min.js"></script>
      <script type="text/javascript" src="js/forms.js"></script> -->

      <!-- remember to use the '<time></time>' attribute when scheduling songcircles-->


      <!-- registration form -->
      <div id="overlay"></div>
      <form id="registration_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    		<!-- General Information -->
    		<div id="details">
          <h4>Songcircle Registration</h4>
          <p>
            Fill out the below information to register
          </p>
    			<!-- <p>
    				Artist Name/Name
    			</p> -->
    			<input type="text" name="username" maxlength="60" placeholder="Please enter your Name">
					<div class="form_error" name="username"></div>
    			<!-- <p>
    				Email
    			</p> -->
    			<input type="email" name="user_email" maxlength="60" placeholder="Please enter your Email">
    			<!-- NOTE: have fallbacks in place for browsers that don't support the email input (ie 8 & 9)-->
					<div class="form_error" name="user_email"></div>
    		</div>
    		<!-- Location -->
    		<div id="loc">
    			<?php if( (isset($city_name) && !empty($city_name)) && (isset($country_name) && !empty($country_name)) ) { ?>
    			<!-- <p id="locAssist">We&apos;ve determined your location to be <br><b><?php echo $city_name.", ".$country_name ?>.</b><br><span id="trigger-location">&lpar;Not right?&rpar;</span></p> -->
    			<?php } else { ?>
    			<p id="without-location">
    				Please select your Country from the list below:
    			</p>
    			<?php } ?>
					<p id="timezone">
    				Please select the most appropriate timezone for your location
    			</p>
    			<select name="timezone">
    				<!-- options are generated based on the country and city -->
    			</select>
					<p>
						Timezone based on <?php if(isset($country_name) && !empty($country_name)){echo $country_name;} ?>
					</p>
					<p>
						<span id="trigger-location">&lpar;Change&rpar;</span>
					</p>
    				<div id="user_location" style="display:none;">
							<p>
								<b>Please select your location</b>
							</p>
    				<!-- NOTE: is hidden unless triggered by span#trigger-location -->
    					<?php include('../includes/countries_array.php'); ?>
    					<select id="country_select">
    						<!-- options are generated by ip search and country array -->
    						<?php
    						// if system can deduce from IP country name and code, then display it
    						if(isset($country_name) && !empty($country_name)){ ?>
    							<option value="<?php echo $country_code ?>"><?php echo $country_name; ?></option>
    						<?php }

    						// generate countries select menu
    						$continents = [];
    						// $countries comes from countries_array.php
    						foreach ($countries as $country) {
    							$continents[] = $country['continent'];
    							$continents = array_unique($continents);
    						}
    						foreach($continents as $key => $continent){
    							// excluding Asia, Africa, Antarctica
    							if( $continent != 'Asia' && $continent != 'Africa' && $continent != 'Antarctica'){
    								echo "<optgroup label=\"{$continent}\"></optgroup>";
    								foreach ($countries as $key => $value) {
    									if($value['continent'] == $continent){
    										echo "<option value=\"$key\">". $value['country'] ."</option>";
    									}
    								}
    							}

    						}
    						?>
    					</select>
    					<!-- <br> -->
    					<!-- <input type="text" id="user_city" value="<?php if(isset($city_name) && !empty($city_name)){echo $city_name;} ?>" maxlength="40" placeholder="Please enter your city" required> -->
							<div class="form_error" name="user_city"></div>
    				</div>


    			<!-- hidden inputs -->
					<input type="hidden" name="fullTimezone" value="">
    			<input type="hidden" name="city_name" value="<?php if(isset($city_name)){echo $city_name;}?>">
    			<input type="hidden" name="country_name" value="<?php if(isset($country_name)){echo $country_name;}?>">
    			<input type="hidden" name="country_code" value="<?php if(isset($country_code)){echo $country_code;}?>">

    		</div>

				<div>
					<input type="submit" name="register" value="Register">
				</div>
				<div id="output"></div>
    	</form>

		</body>

    <script type="text/javascript" src="js/social.js"></script>
    <script>
		$(document).ready(function(){

			var btnRegister = $('input[data-id="triggerRegForm"]');
			var registrationForm = $('#registration_form');
			var overlay = $('#overlay');

			// value of country code, generated by IP detection
			var cityName = $('form#registration_form input[name="city_name"]');
			var cityNameVal = cityName.val();
			var countryCode = $('form#registration_form input[name="country_code"]').val();
			var userCity = $('form#registration_form input[id="user_city"]');
			// full timezone select dropdown
			var selectFullTimezone = $('form#registration_form select[name="timezone"]');
			// full timezone hidden input
			var fullTimezoneVal = $(registrationForm).find('input[name="fullTimezone"]');

			var countrySelectBoxContainer = $('div#user_location');
			var textCountry = $('form#registration_form div#loc p#locAssist');

			// username input field
			var usernameInput 	= $('#registration_form input[name="username"]');

			// hide registration form and overlay on page load
			// registrationForm.hide();
			overlay.hide();
			/*
				position Form off screen
			*/

			// create notification container with class
			var container = $(document.createElement('div')).attr('id','modal').addClass('modalClass');
			$('body').prepend(container);

			// get the songcircle id
			var songcircleId = $('[data-conference-id]').data('conference-id');

			/**
			* When user clicks on the 'Register' button on songcircle.php
			* open the registration form
			*/
			btnRegister.on('click', function(e){
				e.preventDefault();
				overlay.show();
				registrationForm.show();
				usernameInput.focus();
			});

			function getSetFullTimezone(){
				fullTimezone = registrationForm.find('select[name="timezone"] option:selected').html();
				fullTimezoneVal.val(fullTimezone);
			}

			callAjax(countryCode);

			function callAjax(countryCode){
				if(countryCode.length >= 2){
					$.ajax({
						url : '../includes/timezonesFromCountryCode.php',
						data : {
							'country_code' : countryCode,
							'city_name' : cityNameVal
						},
						method : 'POST',
						success: function(data){
							$('form#registration_form select[name="timezone"]').html(data);
						}
					})
				}
			}

			// click event - displays manual location select field
			$('form#registration_form span#trigger-location').on('click', function(){
				countrySelectBoxContainer.css('display','block');

				textCountry.hide();
				// clear all hidden inputs
				// $('form#registration_form input[type="hidden"]:not([name="fullTimezone"])').val('');

			});

			/**
			*	When user changes the country select box
			*/
			$('form#registration_form select#country_select').on('change', function(){

				var countryName = $(this).find('option:selected').text();
				$('form#registration_form input[name="country_name"]').val(countryName);

				var countryCode = $(this).val();
				$('form#registration_form input[name="country_code"]').val(countryCode);

				// set hidden field of city name to NULL
				// cityName.val('');

				callAjax(countryCode);

				// clear out input field
				// userCity.val('');
				// userCity.focus();
			});

			/**
			*	Closes registration form, hide overlay
			* when user clicks outside of the form
			*/
			$(overlay).on('click', function(){
				$(this).hide();
				registrationForm.hide();
				container.hide();
			})

			/**
			*	If the 'Select timezone' select dropdown changes
			*/
			// $(selectFullTimezone).on('change',function(){
			// 	var fullTimezone = $(this).find(':selected').html();
			// 	fullTimezoneVal.val(fullTimezone);
			// 	alert('select value has changed');
			// });

			// Next: test insert all timezone information into Database
			// Next: figure out how to do a multi insert into both tables on once, maybe with 'Transactions'

			// on userCity input value, enter value field

			// on form submit
				var formSubmit = $('#registration_form input[type="submit"]');

			formSubmit.on('click', function(evt){
				// prevent default
				// evt.preventDefault();
				getSetFullTimezone();

				var acptSubmission_name = false;
				var acptSubmission_email = false;
				// var acptSubmission_city	= false;

				// input fields
				// var usernameInput 	= $('#registration_form input[name="username"]');
				var emailInput			= $('#registration_form input[name="user_email"]');
				var userCityInput		= $('#registration_form input#user_city');

				// input values
				var usernameVal 		= usernameInput.val();
				var emailVal 				= emailInput.val();

				// error spans
				var usernameError		= $('div.form_error[name="username"]');
				var emailError			= $('div.form_error[name="user_email"]');
				var userCityError		= $('div.form_error[name="user_city"]');

				// hide error spans
				usernameError.hide();
				emailError.hide();

				// email regex
				var myRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
				var emailRegEx = new RegExp(myRegex);

				usernameInput.focus();

				// input validations..

					// if username input field empty
					if(!usernameInput.val()){
						usernameInput.css('outline','3px solid red').focus();
					}
					// if username is too short
					else if (usernameVal.length <= 1){
						usernameError.html('Username too short!').show();
						// usernameInput.after('<span class="form_error" name="username">Username too short!</span>');
					}	else {
						// clear error outline
						usernameInput.css('outline','3px solid green');
						// remove any error spans
						$('span.form_error[name="username"]').remove();

						acptSubmission_name = true;
					}
					// if data is good, outline green...

					// if email input field empty
					if(!emailInput.val()){
						emailInput.css('outline','3px solid red').focus();
					}
					// if not not valid email
					else if(!emailRegEx.test(emailVal)){
						emailError.html('Please enter a valid email address.').show();
						// emailInput.after('<span class="form_error" name="user_email">Please enter a valid email address.</span>');
					}	else {
						// clear error outline
						emailInput.css('outline','3px solid green');
						// remove any error spans
						$('span.form_error[name="username"]').remove();

						acptSubmission_email = true;
					}

					// if(!userCity.val()){
					// 	userCityError.html('Please enter your city').show();
					// 	userCityInput.css('outline','3px solid red').focus();
					//
					// 	// instead of adding .css to different inputs, use a class... addClass().focus();
					// } else {
					// 	var userCityVal = userCity.val();
					// 	$('form#registration_form input[name="city_name"]').val(userCityVal);
					//
					// 	userCityInput.css('outline','none');
					// 	userCityError.remove();
					//
					// 	acptSubmission_city = true;
					// }

				// for testing
				var outputDiv = $('div#output');

				if(acptSubmission_name && acptSubmission_email){ //  && acptSubmission_city
					// no errors, submission a go..
					console.log('no errors, sending data to validator');
					var formData = registrationForm.serialize();

					$.ajax({
						url : '../includes/registerSongcircle.php',
						data : {
							formData,
							songcircleId // contains unique songcircle id
						},
						method : 'POST',
						success: function(data){

							if($.inArray(true,data) != -1){
								// if there are errors
								outputDiv.show().html(data);
							} else {

								// if no errors
								console.log('validator returned. No Errors...');
								// hide any existing errors
								outputDiv.hide();
								// hide the form
								overlay.hide();
								registrationForm.hide();




								// parse return messages
								try {

									// disable submit button
									btnRegister.off();

									// if songcircleObj is JSON
									var songcircleObj = $.parseJSON(data);

									// construct message
									var notificationMsg = "You have successfully registered for <span>"+songcircleObj.name+'</span> on <span>'+songcircleObj.date_time+'</span>';
									// notificationMsg += ' ('+songcircleObj.user_timezone+' time)';
									notificationMsg += '<br /><br />';
									notificationMsg += 'Please check your email for all the details plus tips	on how to make the most out of this upcoming Songcircle!';
									notificationMsg += '<br /><br />';
									notificationMsg += '<input type="button" value="Go">';

								} catch(e) {

									// catch returned error message
									var notificationMsg = data;

								}

								// append constructed message to notification container
								$(container).append("<p>"+notificationMsg+"</p>");
								overlay.show();
								container.show();

							}
						}
					}); // end of Ajax

				}
				else
				{
					console.log('there was an error somewhere in the form');
					return false;
				}

				return false;

			}); // end of on formSubmit


			// Trigger for showing songcircle participants
			var triggerDiv = $('span.registered');
			var participantsDiv = $('div#participants');

			triggerDiv.on('mouseover', function(){
				participantsDiv.show();
			})

			triggerDiv.on('mouseout', function(){
				participantsDiv.hide();
			})

			// when the notification closes, re-direct to the blog
			// or have a link to blog


		}); // end document.ready

	</script>
</html>
