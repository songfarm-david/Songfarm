$(document).ready(function(){

	// Where re-direction will go after successful registration:

	/* local site */
	// var redirectURL = 'http://localhost/songfarm-oct2015/public/index.php';

	/* live test site */
	var redirectURL = 'http://test.songfarm.ca';

	/* live site */
	// var redirectURL = 'http://songfarm.ca';

	// span to trigger registration form
	var triggerForm = $('span[data-id="triggerRegForm"]');
	// Register button from Songcircle Table
	var btnRegister = $('#schedule_container input[type="submit"]');
	// Waiting list button from Songcircle Table
	var btnWaitList = $('input[data-id="triggerWaitList"]');

	// Overlay for Registration Form
	var overlay = $('#overlay');
	// Registration Form
	var registrationForm = $('#registration_form');

	// Registration Form Submit Button
	var formSubmit = $('#registration_form input[type="submit"]');
	// 'Faux' submit button span
	var formSubmitSpan = $('#registration_form span#submit_registration');

	/* IP Generated Data (functions.php) */
	// city name hidden input value on load
	var cityName = $('form#registration_form input[name="city_name"]').val();
	// country code hidden input value on load
	var countryCode = $('form#registration_form input[name="country_code"]').val();
	// country name hidden input value on load
	var countryName = $('form#registration_form input[name="country_name"]').val();
	// dyamically inputted:
	// full timezone hidden input value once selected
	var fullTimezoneVal = $(registrationForm).find('input[name="full_timezone"]');

	/* Element variables */
	// username input field
	var usernameInput = $('#registration_form input[name="username"]');
	// email input
	var emailInput			= $('#registration_form input[name="user_email"]');
	// timezone select field dropdown
	var selectFullTimezone = $('form#registration_form select[name="timezone"]');
	// container for country select field
	var countrySelectBoxContainer = $('div#user_location');
	// country select trigger
	var countryTrigger = $('form#registration_form span#trigger-location');
	// country select field dropdown
	var countrySelectDropdown = $('form#registration_form select#country_select');

	/* IP sensitive variables */
	var timezoneText = '<p>Please select your timezone</p>';
	var timezoneSlctField = '<select name="timezone" tabindex="3"></select>';
	var formHasIP = $('p#timezone');

	// error output div
	var outputDiv = $('#registration_form div#output');
	// Confirmation notification container (after submission of registration form)
	var confirmContainer = $('div#modal');

	// create array
	var songcircleData = [];
	var otherArray = [];

	/* Validation flags */
	var nameIsValid 			= false;
	var emailIsValid 			= false;
	var codeOfConductRead = false;
	var timezoneValid 		= false;

	/* Code of Conduct */
	var codeOfConductDiv = $('#registration_form div#codeOfConduct');
	var codeOfConductCheckbox = $('#registration_form div#codeOfConduct input[name="codeOfConduct"]');
	var codeOfConductError = $('#registration_form #codeOfConduct div#cOcAlertBox');

	// error spans
	var usernameError		= $('div.form_error[name="username"]');
	var emailError			= $('div.form_error[name="user_email"]');
	// timezone error
	$('#registration_form div#loc').append('<div class="form_error" name="timezone"></div>');
	var timezoneError = $('div.form_error[name="timezone"]');

	// Code of Conduct error counter
	// Used to alert the user if they have forgotten to confirm Code of Conduct
	var cOcErrorCounter = 0;

	// email regex
	var myRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
	var emailRegEx = new RegExp(myRegex);


	/**
	* Trigger Songcircle Registration form + pass values from songcircle table
	*/
	$(triggerForm).click(function(e){
		$(btnRegister).click();
	});

	/**
	* When user clicks on the 'Register' button on songcircle.php
	* open the registration form
	*/
	$(btnRegister).add(btnWaitList).on('click', function(e){
		e.preventDefault();
		overlay.show();
		registrationForm.show();
		// call function to retrieve songcircle data by ROW number
		getAndSetSongcircleDataByRow( $(this).parents('tr').data('row-count') );
		usernameInput.focus();
	});

	/**
	* Gathers input data for a given row and
	* Sets it into the registration form
	*
	*	Created: 01/28/2016
	*
	* @param (int) a row number
	*/
	function getAndSetSongcircleDataByRow(rowNumber){

		// child inputs of songcircle data row
		var childInputs = $('#schedule_container table tr[data-row-count="'+rowNumber+'"]').find('td:last-child').children('input[type="hidden"]');

		// loop through the child elements and extract key/value pairs
		$(childInputs).each(function(index, element){
			var name 	= $(element).attr('name');
			var str = '<input type="hidden" name="'+name+'" value="'+$(element).val()+'">';
			// insert elements into form
			$(formSubmit).before(str);
		});

	}

	/**
	*	Targets specific hidden inputs in the registration form
	* and Removes them
	*/
	function removeSongcircleData(){
		var submitBtnParent = $(formSubmit).parent().find('input[type="hidden"]').remove();
	}

	/**
	*	Find the selected option of timezone select field
	* assign value to full_timezone hidden field
	*/
	function getSetFullTimezone(){
		full_timezone = $(registrationForm).find('select[name="timezone"] option:selected').html();
		fullTimezoneVal.val(full_timezone);
	}

	callAjax(countryCode);

	/**
	*	Takes IP generated data (country code) ,
	* and returns country-specific timezone data
	*
	* @param (string) a two letter country code
	* @param (string) a city name
	* @return string formatted timezones (1 or more)
	*/
	function callAjax(countryCode){
		if(countryCode.length >= 2){
			$.ajax({
				url : '../includes/helper_functions/timezones_from_country_code.php',
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
	} // end of: callAjax(countryCode)

	/**
	* Click event for manual country select by user
	*/
	$(countryTrigger).on('click', function(){
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
		var countryNameVal = $(this).find('option:selected').text();
		// insert value into country name hidden input field
		$('form#registration_form input[name="country_name"]').val(countryNameVal);
		// get value of selected option
		var countryCodeVal = $(this).val();
		// insert value into country code hidden input field
		$('form#registration_form input[name="country_code"]').val(countryCodeVal);
		// clear value of hidden field
		$('form#registration_form input[name="city_name"]').val('');

		callAjax(countryCodeVal);

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
		removeSongcircleData();
	});

	/**
	* Trigger Form w/ hidden values
	*/
$('span')


	/**
	* Trigger Registration Form Submission
	*/
	$(formSubmitSpan).click(function(){
		$(formSubmit).click();
	})

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
			timezoneError.hide();

		/* Input validations */
			/* Username */
			if( !usernameInput.val() ){	// if username input field empty
				usernameInput.addClass('input_error').focus();
				// flag to signal there was an error with the username
				var username_flag = true;
			}
			// if username is too short
			else if ( usernameInput.val().length <= 1 ){
				// focus on username input
				usernameInput.addClass('input_error').focus();
				// show error message
				usernameError.css('display','table').html('<p>Username too short!</p>').show();
				// flag to signal there was an error with the username length
				var username_length_flag = true;
			}	else { // no errors with username
				// hide any error spans
				$(usernameError).hide();
				// Outline goes green -- function used here
				usernameInput.css('outline','none');
				// validate measure
				nameIsValid = true;
			}

			/* Email */
			if( !emailInput.val() ){ // if email input field empty
				emailInput.addClass('input_error');
				// if NOT username error, focus on email field
				if(!username_flag && !username_length_flag){
					emailInput.focus();
				}
			}
			// if not not valid email
			else if( !emailRegEx.test( emailInput.val() ) ){
				emailInput.addClass('input_error').focus().select();
				emailError.css('display','table').html('<p>Please enter a valid email address.</p>').show();
			}	else {
				// hide any error spans
				$(emailError).hide();
				// change outline to green
				emailInput.css('outline','none');
				// validate measure
				emailIsValid = true;
			}

			/* Timezone validation */
			if( !fullTimezoneVal.val() ){
				timezoneError.css('display','table').html('<p>Please select your timezone.</p>').show();
			} else {
				// console.log('no timezone error.. Validating true');
				timezoneValid = true;
			}

			// if code of conduct button is not checked
			if( !codeOfConductCheckbox.prop('checked') ){
				cOcErrorCounter++;
				// console.log('error counter: '+cOcErrorCounter);

				console.log('not checked');

				// construct error message
				// var alertMsg = '<div id="cOcAlertBox" class="form_error">';
				var	alertMsg = '<p>Please acknowledge that you have read and understand the <a href="#">Songfarm Code of Conduct</a></p>'
						// alertMsg += '</div>';
				// append message to div
				// codeOfConductDiv.append(alertMsg).show();
				codeOfConductError.html(alertMsg).show();

				$('.checkbox-custom-label, .checkbox-custom-label a').css('color','#3FA8F4');
				$('.checkbox-custom + .checkbox-custom-label').addClass('error');

				if(cOcErrorCounter >= 3){
					// codeOfConductDiv.effect('shake');
					console.log('shake the box');
					/*
						program a shaking effect to occur on the error alert box
					*/
				}
			} else {
				// validate measure
				codeOfConductRead = true;
			}

		// test that all validation conditions are met
		if( nameIsValid && emailIsValid && timezoneValid && codeOfConductRead ){
			// no errors

			// serialize form data
			var formData = registrationForm.serialize();
			console.log(formData);
			// send data to validation (includes/songcircle_register_user.php)
			$.ajax({
				// url : '../includes/songcircle_register_user.php',
				/**
				* Testing whether possible to include get variable in URL
				* while using method : 'POST'
				*/
				url : '../includes/songcircle_user_action.php?action=register',
				data : {
					'formData' : formData
				},
				method : 'POST',
				success: function(data){
					console.log(data);
					// hide form and overlay
					registrationForm.hide();
					overlay.hide();

					try {	// try to parse return data in JSON format

					var songcircleObj = $.parseJSON(data); // parse return messages
					var notificationMsg;
					// console.log('script returned object');
					// if returned data contains waitlist flag
					if(songcircleObj.waitlist == true){
						// waitlist message
						notificationMsg = "<p><span>Thanks, "+songcircleObj.username+"!</span></p>";
						notificationMsg+= "<p>You've been added to the Waiting List for <b>"+songcircleObj.songcircle_name+"</b> on <b>"+songcircleObj.date_of_songcircle+"</b></p>";
						notificationMsg+= "<p>We'll notify as soon as a spot opens up!</p>"
					} else {
						// registration message
						notificationMsg = "<p><span>Thank You, "+songcircleObj.username+"!</span></p>";
						notificationMsg+= "<p>Please visit your email to confirm your attendance to <b>"+songcircleObj.songcircle_name+"</b> plus learn some simple tips for getting the most out of this upcoming Songcircle event.</p>";
						// need to re-work this message...
					}

						// wait 5 seconds then redirect
						setTimeout(function(){
							window.location.replace(redirectURL);
						}, 5000);

					} catch(e) { // catch returned error message as 'data'
					console.log('script returned error');

						var notificationMsg = data;

						// trim returned data for keywords ('name','email')
						var newStr = notificationMsg.substring( notificationMsg.indexOf('=')+2, notificationMsg.indexOf("_") );

						switch (newStr) {
							case 'name':
								var nameFocus = true;
								break;
							case 'email':
								var emailFocus = true;
								break;
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
								// hide notification box
								confirmContainer.hide();
							}, 2500);
						}
						else
						{
							setTimeout(function(){
								console.log('mailing error occurred');
								overlay.show();
								registrationForm.show();
								confirmContainer.hide();
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
			}); // end of: $.ajax
		} // end of: if( nameIsValid && emailIsValid && timezoneValid && codeOfConductRead )
		else
		{
			console.log('registration failed due to validation error..');
			// exit script
			return false;
		}
		return false;
	}); // end of on formSubmit



	/**
	* Toggles checked property on codeOfConduct
	*/
	// $.fn.toggleCoC = function(){
		$('#codeOfConduct label').on('click',function(){
			$(codeOfConductCheckbox).prop('checked',!codeOfConductCheckbox.prop("checked"));
			$('.checkbox-custom-label, .checkbox-custom-label a').css('color','');
			$('.checkbox-custom + .checkbox-custom-label').removeClass('error');
			$('div#cOcAlertBox').hide();
		})
	// }

	/**
	* Code of Conduct Modal
	*/
	/* CSS for Code Of Conduct Modal */
	var modalTrigger = $('#codeOfConduct a');
	var codeOfConContainer = $('<div id="cOcModal"></div>');

	/**
	* Function for retrieving 'Songfarm Code of Conduct' from filesystem
	*/
	$.fn.getCodeOfConduct = function(){
		$.get('../code_of_conduct.html', function(data){
			// construct modal
			codeOfConContainer.html(data);
			// append modal container at end of body
			$('body').append(codeOfConContainer);
			// show modal
			codeOfConContainer.show();
		});
	}

	/**
	* When Code of Conduct link is clicked
	*/
	modalTrigger.on('click', function(){
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
	* Event: User agrees with Code of Conduct
	*/
	$('body').on('click', 'button.cOcAgree', function(){
		// check checkbox if agree
		codeOfConductCheckbox.prop('checked', true);
		// hide Code of Conduct container
		codeOfConContainer.fadeOut().hide();
	});

	/**
	* Event: User does not agree with Code of Conduct
	*/
	$('body').on('click', 'a[name="cOcNotAgree"]', function(){
		codeOfConductCheckbox.prop('checked', false);
		codeOfConContainer.fadeOut().hide();
		// $.fn.toggleCoC();
	});

	/**
	* If there are no registered user, display a unique message
	*	Otherwise display registered users
	*/
	// Trigger for showing songcircle participants
	var triggerDiv = $('span.triggerParticipantsTable');
	var participantsTable = $('.participantsTable');

	triggerDiv.on('mouseover mouseout', function(){
		$(this).next().toggle();
	});

}); // end document.ready
