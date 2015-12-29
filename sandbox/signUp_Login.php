<?php include 'login.php' ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Authentication</title>
	<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	<style>
		.hidden{ display: none;}
	</style>
</head>
<body>
	<p>
		Log In or Sign Up
	</p>
	<ul>
		<li>
			<a href="#" id="login">Login</a>
		</li>
		<li>
			<a href="#" id="signUp">Sign Up</a>
		</li>
	</ul>

	<!-- Login form -->
	<form method="post" id="login-form" class="hidden">
		<input type="text" name="username" placeholder="Artist Name or Email" value="<?php echo $name; ?>">
		<input type="password" name="password" placeholder="Enter your Password">
		<input type="submit" value="submit">
	</form>

	<!-- Sign Up form -->
	<form method="post" action="signUpValidation.php" id="signUp-form" class="hidden">
		<input type="text" name="artist_name" placeholder="Artist Name"><br>
		<input type="email" name="artist_email" placeholder="What is your Email?"><br>
		<input type="password" name="artist_password" placeholder="Choose a Password"><br>
		<input type="password" name="conf_password" placeholder="Re-type your Password"><br>
		<input type="submit" value="Submit">
	</form>

	<script>

		$('#login').on('click', function(){
			$('#login-form.hidden').removeClass('hidden');
		})

		$('#signUp').on('click', function(){
			$('#signUp-form.hidden').removeClass('hidden');
		})




	</script>
</body>
</html>
