<!-- Login form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="login-form" class="hide">
	<input type="text" name="username" placeholder="Artist Name or Email" value="<?php echo $login->username ?>" required><!--required-->
	<input type="password" name="password" placeholder="Enter your Password" required><!--required-->
	<input type="submit" value="Log In" name="submit" id="submitLogIn">
	<span id="login-error">
		<?php if($login->errors) {
						foreach ($login->errors as $error) { echo $error;	}
					}	?>
	</span>
</form>
