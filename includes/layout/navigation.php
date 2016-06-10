<?php require_once(LIB_PATH.DS."login.php"); ?>
<nav>
	<h2 class="hide">Navigation</h2>
	<a href="index.php" title="Home" tabindex="-1"><div class="logo" tabindex="0"></div></a>
	<ul id="links">
			<li><!-- remember to use a class="active" on links that are currently active -->
				<a href="index.php#about" title="About Songfarm" rel="bookmark">About</a>
			</li>
			<li>
				<a href="index.php#features" title="Features of Songfarm" rel="bookmark">Features</a>
			</li>
			<li>
				<a href="songcircle.php" title="Songcircle - Live Songwriter's Circle" rel="next"><b>Songcircle</b></a>
			</li>
			<li>
				<a href="index.php#contactUs" title="Contact Us" rel="bookmark">Contact Us</a>
			</li>
			<!-- <li>
				<a href="#" id="login" title="Log In" rel="next"><b>Log In</b></a>
				 // Login form -->
				<?php  include_once('../includes/forms/login.php'); ?>
			<!-- </li> -->
		<!-- </div> -->
	</ul>
	<button class="menu-icon active">&#8801;</button>
</nav>
<script>
	/**
	* Click event for mobile device mini-menu toggle
	*
	* 03/20/2016
	*
	* Toggle mini-menu color to green
	* Toggle visibility of menu
	*/
	$('.menu-icon, #links li a').on('click',function(){
		$('.menu-icon').toggleClass('active-color');
		$('ul#links').toggleClass('active');
	})
</script>
