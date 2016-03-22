/**
* This page contains scripting for
* Registration Processing && Contact Form
*
* Used in public/index.php
*
* NOTE: requires validate.min.js
*/

// on click: 'REGISTER TODAY'
$(".register").on('click',function(){
	// show overlay and registration form part 1
	$('div#overlay, form#register-form').fadeIn('fast').removeClass('hide');
	// if user clicks outside of form, HIDE OVERLAY, FORM PART 1, and RESET the form
	$('#overlay, form#register-form img').on('click', function(){
		$('form#register-form').css('display','none');
		$('#register-form > div').removeClass('hide');
		$('#second').addClass('hide');
		$('#overlay').css('display','none');
		$('#message').addClass('hide');
		resetForm($('#register-form'));
	});
});

// Click event for ACTIVE users (currently artists only)
// Retrieve User Type
$('.user:not(.inactive)').on('click',function(){
	// get value of clicked element
	var user_type = $(this).attr('value');
	// set value of hidden input to clicked value
	$('#user_type').attr('value', user_type);
	// hide first form div
	$('form#register-form > div').addClass('hide');
	// show second form div
	$('#second').removeClass('hide');
	$('input#username').focus();
});

// on SUBMIT (if all fields are valid)
$("#register-form").validate({
	errorElement : 'span',
	rules : {
		user_name : {
			required 	: true,
			maxlength	: 255 // letters and numbers
		},
		user_email : {
			required : true,
			email : true
		},
		user_password : {
			required 	: true,
			minlength : 7
		},
		conf_password : {
			equalTo : '#userpassword'
		}
	},
	messages : {
		user_name : {
			required : 'The name field is required',
			maxlength : 'Name field cannot exceed 255 characters'
		},
		user_email : {
			required : 'The email field is required',
			email : 'Please enter a valid email' // this is not valid enough
		},
		user_password : {
			required : 'You must enter a password',
			minlength :'Your password needs to be at least 7 characters'
		},
		conf_password : {
			equalTo : 'Passwords don\'t match'
		}
	},
	// call a custom handler
	submitHandler: function(){
		// grab from and serialize the data
		var form = $("#register-form");
		var form_data = form.serialize();
		// send data to php validation file
		$.ajax({
			// url:'../public/index.php',
			url:'../includes/registration_val.php',
			type:'POST',
			data: form_data,
			dataType:'json',
			success: function(data, textStatus, jqXHR){
				// console.log(data, textStatus, jqXHR);
				if($.inArray(true,data) != -1){
					// window.location.href = 'workshop.php?id='+data[0];
					/*
					* Here we will return a confirmation and perhaps do other actions upon
					* successful user registration
					*/
					window.location.href = 'index.php';
				} else {
					$("#register-form div#message").append("<p>"+data+"</p>");
					$("#register-form div#message").removeClass('hide');
				}
			} // success: function(data)
		}); // $.ajax
	} // submitHandler
});// end of validate

function resetForm($form) {
	// $form.find('input:hidden, input:text').val('');
	$('input[type=hidden], input[type=text], input[type=email], input[type=password]').val('');
	$('span#useremail-error, span#username-error,span#userpassword-error,span#confpassword-error').html('');

}

// Validator method to check for letters only and not numbers
// jQuery.validator.addMethod("lettersAndNumbers", function(value, element) {
// 	return this.optional(element) || /^[a-z0-9\s]+$/i.test(value); ///^[a-z0-9\-\s]
// }, "Only letters and whitespace allowed");

// on click: CONTACT FORM
$("form#contact-form div.button").on('click', function(){
	$(this).trigger("submit");
	$("form#contact-form").validate({
		errorElement : 'span',
		// call a custom handler
		submitHandler: function(form){
			// grab from and serialize the data
			var form = $("#contact-form");
			var formData = form.serialize();
			// send data to php validation file
			$.ajax({
				url:'../includes/contactFormValidation.php',
				type:'POST',
				data: formData,
				success: function(data){
				$("#contact-form").css('display','none');
				$("div#thank-you_message p").html(data);
				$('div#thank-you_message').removeClass('hide');
				}
			});
		}
	});
});
