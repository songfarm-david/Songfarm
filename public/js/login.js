/**
* Log In functionality for main header (includes/layout/header.php)
*
* NOTE: validation requires validate.min.js to function
*/
	// Drop-down functionality
	$('#login').on('click', function(){
		$('#login-form').toggle(500, function(){
			$('#login-form input[type=text]').focus();
		});
	})

	// Log In Form validation
	$('#login-form').validate({
		errorElement : 'span',
		rules : {
			username : 'required',
			password : 'required'
		},
		messages : {
			username : 'Please enter your Name or Email',
			password : 'Please enter your Password'
		},
		submitHandler: function(form){
			var form = $('#login-form');
			var form_data = form.serialize();
			$.ajax({
				url 	: '../includes/login.php',
				type 	: 'POST',
				data 	: form_data,
				success:function(data, textStatus, jqXHR){
					// successful login returns false
					if(data == false){
						window.location.href = 'workshop.php'
					} else {
						$('span#login-error').html(data);
					}
				} // success
			}) // ajax
		} // submit handler
	}); // validate
