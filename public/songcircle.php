<?php require_once("../includes/initialize.php");

/**
* References 'functions.php' generate_ip_data() function
*/
if( $location_by_ip = generate_ip_data() ){ // if ip array comes back

	$expected = array('country_code','country_name','city_name','continent_code');

	while (list($key, $value) = each($location_by_ip)) {
		if( in_array($key, $expected) ){
			// assign variable variables
			${$key} = $value;
		}
	}
}

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

        <article id="main"><!-- holds background image -->
          <!-- Introduction to Songcircle -->
          <h1 id="headline">
            <span class="bold">Real songs. Real songwriters. Real-time.</span>
          </h1>
          <h2 id="byline">
            Get inspired. Join songwriters just like you in a live, virtual songwriter's circle. Register for a Songcircle today!
          </h2>
        </article>

        <article id="schedule">

          <h3>Upcoming Songcircles:</h3>

					<!-- Schedule of Songcircles -->
          <?php
          $songcircle->open_songcircle_exists();
          $songcircle->display_songcircles();
          ?>

        </article>

        <article id="aboutSongcircle">
          <h3>What is a Songcircle?</h3>
          <p>
            A Songcircle is a virtual songwriter's circle done live over the web in real time.
          </p>
					<p>
						It's a unique opportunity to share the songs you're working on with other passionate songwriters from around the world.
					</p>
					<p>
						Songcircle's are free to participate in. There is absolutely no cost involved. All you need is a song, a webcam and an internet connection.
					</p>
        </article>

      </section>

			<!-- Use RDFa to define songcircle events // schema.org -->
			<!-- remember to use the '<time></time>' attribute when scheduling songcircles-->

      <?php include("../includes/layout/footer.php") ?>


			<!-- Registration Form -->

      <div id="overlay"></div>
      <form id="registration_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    		<!-- General Information -->
    		<div id="details">
          <h4>Songcircle Registration</h4>
          <!-- <p>
            Fill out the below information to register
          </p> -->
    			<!-- <p>
    				Artist Name/Name
    			</p> -->
    			<input type="text" name="username" maxlength="60" placeholder="Please enter your Name" tabindex="1">
					<div class="form_error" name="username"></div>
    			<!-- <p>
    				Email
    			</p> -->
    			<input type="email" name="user_email" maxlength="80" placeholder="Please enter your Email" tabindex="2">
    			<!-- NOTE: have fallbacks in place for browsers that don't support the email input (ie 8 & 9)-->
					<div class="form_error" name="user_email"></div>
    		</div>

    		<!-- Location -->
    		<div id="loc">
				<?php if( (isset($country_code) && !empty($country_code)) && (isset($country_name) && !empty($country_name)) ){ ?>
					<p id="timezone">
						Please select the most appropriate timezone for your location
					</p>

					<!-- Jquery inserts timezone select list here if user has IP data -->

					<p id="locationMsg">
						Timezone based on <b><?php echo $country_name; ?></b>
						<br>
						<span id="trigger-location" tabindex="4">&lpar;Not right?&rpar;</span>
					</p>

				<?php } else { ?>

					<p>
						Please select your country from the list
					</p>

				<?php }	?>
					<div id="user_location" class="hide">
  					<?php include('../includes/countries_array.php'); ?>

  					<select id="country_select" tabindex="5">
						<?php	if( (isset($country_name) && !empty($country_name)) && (isset($country_code) && !empty($country_code)) ){ ?>
  							<option value="<?php echo $country_code ?>"><?php echo $country_name; ?></option>
						<?php }

						/* Generate countries array using 'includes/countries_array.php' */

						$continents = [];

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

  				</div>

    		</div>

				<!-- hidden inputs -->
				<input type="hidden" name="fullTimezone" value="">
				<input type="hidden" name="city_name" value="<?php if(isset($city_name)){echo $city_name;}?>">
				<input type="hidden" name="country_name" value="<?php if(isset($country_name)){echo $country_name;}?>">
				<input type="hidden" name="country_code" value="<?php if(isset($country_code)){echo $country_code;}?>">

				<div>
					<div id="codeOfConduct">
						<input class="double" type="checkbox" name="codeOfConduct" tabindex="6">&nbsp;I have read and agree to adhere to the <a href="#" style="text-decoration:underline" tabindex="7">Code&nbsp;of&nbsp;Conduct</a>
					</div>
					<input type="submit" name="register" value="Register" tabindex="8">
				</div>

				<!-- error output -->
				<div id="output"></div>
    	</form>

		</body>

    <script type="text/javascript" src="js/social.js"></script>
    <script>
		$(document).ready(function(){

			// Where re-direction will go after successful registration:

			/* local site */
			// var redirectURL = 'http://localhost/songfarm-oct2015/public/index.php';

			/* live test site */
			var redirectURL = 'http://test.songfarm.ca/public/'

			//

			// Register button
			var btnRegister = $('input[data-id="triggerRegForm"]');
			// Registration Form
			var registrationForm = $('#registration_form');
			// Registration Form Submit
			var formSubmit = $('#registration_form input[type="submit"]');

			// Overlay for Registration Form
			var overlay = $('#overlay');
			// Hide overlay on page load
			overlay.hide();

			/* IP Generated Data (functions.php) */
			// city name hidden input value on load
			var cityName = $('form#registration_form input[name="city_name"]').val();
			// country code hidden input value on load
			var countryCode = $('form#registration_form input[name="country_code"]').val();
			// country name hidden input value on load
			var countryName = $('form#registration_form input[name="country_name"]').val();
			// dyamically inputted:
			// full timezone hidden input value once selected
			var fullTimezoneVal = $(registrationForm).find('input[name="fullTimezone"]');

			/* Element variables */
			// timezone select field dropdown
			var selectFullTimezone = $('form#registration_form select[name="timezone"]');
			// container for country select field
			var countrySelectBoxContainer = $('div#user_location');
			// country select field dropdown
			var countrySelectDropdown = $('form#registration_form select#country_select');
			// username input field
			var usernameInput = $('#registration_form input[name="username"]');
			// email input
			var emailInput			= $('#registration_form input[name="user_email"]');
			// error output div
			var outputDiv = $('#registration_form div#output');

			/* Confirmation notification container (after submission of registration form)*/
			var confirmContainer = $(document.createElement('div')).attr('id','modal').addClass('modalClass');
			$('body').prepend(confirmContainer);

			/* retrieve songcircle id from display_songcircles() ('includes/songcircle.php'); */
			var songcircleId = $('[data-conference-id]').data('conference-id');

			/* IP sensitive variables */
			var timezoneText = '<p>Please select your timezone</p>';
			var timezoneSlctField = '<select name="timezone" tabindex="3"></select>';
			var formHasIP = $('p#timezone');

			/* Validation measures */
			var nameIsValid = false;
			var emailIsValid = false;
			var codeOfConductRead = false;

			/* Code of Conduct */
			var codeOfConductDiv = $('#registration_form div#codeOfConduct');
			var codeOfConductCheckbox = $('#registration_form div#codeOfConduct input[name="codeOfConduct"]');

			// error spans
			var usernameError		= $('div.form_error[name="username"]');
			var emailError			= $('div.form_error[name="user_email"]');

			// Code of Conduct error counter to alert user
			var cOcErrorCounter = 0;

			// email regex
			var myRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
			var emailRegEx = new RegExp(myRegex);


			/**
			* When user clicks on the 'Register' button on songcircle.php
			* open the registration form
			*/
			btnRegister.on('click', function(e){
				e.preventDefault();
				overlay.show();
				registrationForm.show();

				/* call remove errors function here */

				usernameInput.focus();
			});

			/**
			*	Find the selected option of timezone select field
			* assign it to fullTimezone hidden field
			*/
			function getSetFullTimezone(){
				fullTimezone = $(registrationForm).find('select[name="timezone"] option:selected').html();
				fullTimezoneVal.val(fullTimezone);
			}

			callAjax(countryCode);

			/**
			*	Takes IP generated (or user selected) country code,
			* checks that it is valid
			*	Sends country code and city name (if available) to country code function
			*
			* @params string countryCode - a two letter country code
			* @return string formatted timezones (1 or more)
			*/
			function callAjax(countryCode){
				if(countryCode.length >= 2){
					$.ajax({
						url : '../includes/timezonesFromCountryCode.php',
						data : {
							'country_code' : countryCode,
							'city_name' : cityName
						},
						method : 'POST',
						success: function(data){
							$('form#registration_form select[name="timezone"]').html(data);
						}
					});
				}
			}

			/**
			* Click event for manual country select by user
			*/
			$('form#registration_form span#trigger-location').on('click', function(){
				$(this).hide();
				$('p#locationMsg').text('Please select your country');
				countrySelectBoxContainer.css('display','block');
				countrySelectDropdown.focus();
			});

			/**
			*	When user changes the country select box
			*/
			$(countrySelectDropdown).on('change', function(){

				// get value of selected option
				var countryName = $(this).find('option:selected').text();
				// insert value into country name hidden input field
				$('form#registration_form input[name="country_name"]').val(countryName);
				// get value of selected option
				var countryCode = $(this).val();
				// insert value into country CODE hidden input field
				$('form#registration_form input[name="country_code"]').val(countryCode);

				// clear value of hidden field
				$('form#registration_form input[name="city_name"]').val('');

				callAjax(countryCode);

			});

			/**
			* Variable Location Form Fields
			*/
			// if presence of IP address elements
			if( formHasIP.length ){
				$('p#timezone').after(timezoneSlctField);
			} else {
				// if no presence of IP address elements
				// show country selection container
				countrySelectBoxContainer.show();
				// insert elements after selection container
				countrySelectBoxContainer.after(timezoneText+timezoneSlctField);
			}

			/**
			*	Closes registration form, hide overlay
			* when user clicks outside of the form
			*/
			$(overlay).on('click', function(){
				$(this).hide();
				registrationForm.hide();
				// hide confirmation notice, if present
				confirmContainer.hide();
				// hide Code of Conduct, if present
				codeOfConContainer.hide();
			});

			/**
			* On Songcircle Registration form submission
			*/

			formSubmit.on('click', function(evt){

				/**
				* Write function for correct outlining of true or false validations
				*/

				// remove the hidden input containing city name so that it doesn't submit with the form
					$('input[name="city_name"]').remove();

				// call function to retrieve full timezone
					getSetFullTimezone();

				// hide any possible error spans from previous submissions
					usernameError.hide();
					emailError.hide();

				// input validations..
					if( !usernameInput.val() ){	// if username input field empty
						usernameInput.css('outline','3px solid red').focus();
						// flag to signal there was an error with the username
						var username_flag = true;
					}
					// if username is too short
					else if ( usernameInput.val().length <= 1 ){
						// focus on username input
						$(usernameInput).focus();
						// show error message
						usernameError.html('Username too short!').show();
						// flag to signal there was an error with the username length
						var username_length_flag = true;
					}	else { // no errors with username
						// hide any error spans
						$(usernameError).hide();
						// Outline goes green -- function used here
						usernameInput.css('outline','3px solid green');
						// validate measure
						nameIsValid = true;
					}

					if( !emailInput.val() ){ // if email input field empty
						emailInput.css('outline','3px solid red');
						// if NOT username error, focus on email field
						if(!username_flag && !username_length_flag){
							emailInput.focus();
						}
					}
					// if not not valid email
					else if( !emailRegEx.test( emailInput.val() ) ){
						emailInput.css('outline','3px solid red').focus().select();
						emailError.html('Please enter a valid email address.').show();
					}	else {
						// hide any error spans
						$(emailError).hide();
						// change outline to green
						emailInput.css('outline','3px solid green');
						// validate measure
						emailIsValid = true;
					}

					// if code of conduct button is not checked

					if( !codeOfConductCheckbox.prop('checked') ){
						cOcErrorCounter++;
						console.log('error counter: '+cOcErrorCounter);

						// construct error message
						var alertMsg = '<div id="cOcAlertBox">';
								alertMsg += '<p>Please acknowledge that you have read and understand the <a href="#">Songfarm Code of Conduct</a></p>'
								alertMsg += '</div>';
						// append message to div
						codeOfConductDiv.append(alertMsg);
						if(cOcErrorCounter >= 3){
							alert('shake the box');
							/*
								program a shaking effect to occur on the error alert box
							*/
						}
					} else {
						// validate measure
						codeOfConductRead = true;
					}

				// test that all validation conditions are met
				if( nameIsValid && emailIsValid && codeOfConductRead ){
					// no errors
					// serialize form data
					var formData = registrationForm.serialize();

					// send data to validation (includes/registerSongcircle.php)
					$.ajax({
						url : '../includes/registerSongcircle.php',
						data : { formData, songcircleId	},
						method : 'POST',
						success: function(data){

							// hide form and overlay
							registrationForm.hide();
							overlay.hide();

							try {
							// try to parse return data in JSON format

								// if songcircleObj is JSON
								var songcircleObj = $.parseJSON(data);// parse return messages

								// construct return message
								var notificationMsg = "<span>Thank You!</span><br /><br />";
										notificationMsg += "You have successfully registered for <span>"+songcircleObj.name+'</span> on <span>'+songcircleObj.date_time+'</span><br /><br />';
										notificationMsg += 'Please check your email for more details, plus tips on how to get the most out of this Songcircle.';
										notificationMsg += '<br /><br />';

								// timer for page redirect
								setTimeout(function(){
									window.location.replace(redirectURL);
								}, 7000);

							} catch(e) {
								// catch returned error message as 'data'
								var notificationMsg = data;

								// collect error "id" from returned data
								var newStr = notificationMsg.substring( notificationMsg.indexOf('=')+2, notificationMsg.indexOf("_") );
								// console.log(newStr);

								switch (newStr) {
									case 'name':
										// usernameInput.css('outline','3px solid red');
										var nameFocus = true;
										break;
									case 'email':
										var emailFocus = true;
										// usernameInput.css('outline','3px solid green');
										// emailInput.css('outline','3px solid red').focus();
										break;
									// case 'timezone':
									// 	console.log('timezone was found');
									// 	break;
									// case 'country':
									// 	console.log('country was found');
									// 	break;
									// case 'code':
									// 	console.log('code was found');
									// 	break;
									// default:
									// 	console.log('End of Switch Statement');

								}

								if(nameFocus || emailFocus){
									// set timeout to return to form
									setTimeout(function(){
										overlay.show();
										registrationForm.show();
										if(nameFocus){
											usernameInput.focus();
										} else if (emailFocus) {
											emailInput.focus().select();
										}
									}, 2500);
								} // end of: if(nameFocus || emailFocus)
								else {
									setTimeout(function(){
										console.log('location error occurred');
										overlay.show();
										registrationForm.show();
									}, 7500);
								}

							} finally {
								// remove any child elements, if existing
								$(confirmContainer).empty();
								// append notification message to container
								$(confirmContainer).append(notificationMsg);
								// show overlay & confirmation container
								overlay.show();
								confirmContainer.show();
							}

						} // end of: success: function(data)
					}) // end of: $.ajax
				} // end of: if( nameIsValid && emailIsValid && codeOfConductRead )
			 	else
				{
					console.log('registration failed due to validation error..');
					// exit script
					return false;
				}
				return false;
			}); // end of on formSubmit

			/**
			*	Hide Code of Conduct error alert box if checked
			*/
			codeOfConductCheckbox.change(function(){
				if( $(this).is(':checked') ){
					if( $('div#cOcAlertBox').length ){
						$('div#cOcAlertBox').hide();
					}
				}
			});

			// Trigger for showing songcircle participants
			var triggerDiv = $('span.registered');
			var participantsTable = $('#participants');

			/* Events for show/hide songcircle participants */
			triggerDiv.on('mouseover', function(){
				participantsTable.show();
			});

			triggerDiv.on('mouseout', function(){
				participantsTable.hide();
			});


			/* Code of Conduct Modal*/

			/* CSS for Code Of Conduct Modal */
			var modalTarget = $('#codeOfConduct a');
			var codeOfConContainer = $('<div id="cOcModal"></div>');
			var modalClose = $('<button type="button" class="closeCoC">Ok, got it!</button>');

			/**
			* Function for retrieving 'Songfarm Code of Conduct' from filesystem
			*/
			$.fn.getCodeOfConduct = function(){
				$.get('../codeOfConduct.html', function(data){

					// construct modal
					codeOfConContainer.html(data);
					codeOfConContainer.append(modalClose);

					// append modal container at end of body
					$('body').append(codeOfConContainer);

					// get width of modal container
					var modalWidth = codeOfConContainer.width();

					// adjust left margin to center modal
					codeOfConContainer.css('margin-left','-'+modalWidth/2+'px');

					codeOfConContainer.show();

				});
			}

			// on trigger click
			modalTarget.on('click', function(){
				// get page
				$(this).getCodeOfConduct();

			});

			/**
			* Open Modal event for Code of Conduct error alert
			*/
			$('body').on('click', '#cOcAlertBox', function(){
				$.fn.getCodeOfConduct();
			});

			/**
			* Click event to close Code of Conduct Modal
			*/
			$('body').on('click', '.closeCoC', function(){
				// alert('click');
				codeOfConContainer.fadeOut().hide();
			});

			/*
			*	Code for browser window resize event listener
			*/
			// $(window).resize(function(){
			// 	// alert('resize');
			// 	/* this function would be used in continuously resizing a margin when
			// 	someone resizes the browser window */
			// })

		}); // end document.ready

	</script>
</html>
