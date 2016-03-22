<?php require_once(LIB_PATH.DS."login.php"); ?>
<header id="header" >
	<!-- <img src="images/top-bar.png">
	<ul id="social_head">
		<li class="facebook"></li>
		<li class="twitter"></li>
		<li class="linkedIn"></li>
		<li><a href="index.php#contactUs" title="Contact Us" rel="bookmark"></a></li>
	</ul> -->
	<!-- h1 hidden // anchored link to top -->
	<h1 class="hide">Songfarm</h1>
	<nav>
		<!-- nav h2 is hidden -->
		<h2 class="hide">Navigation</h2>
		<a href="index.php"><div class="logo"></div></a>
		<ul id="links">
			<div>
				<li><!-- remember to use a class="active" on links that are currently active -->
					<a href="index.php#about" title="About Songfarm" rel="bookmark">About</a>
				</li>
				<li>
					<a href="index.php#features" title="Features of Songfarm" rel="bookmark">Features</a>
				</li>
				<span id="style"></span>
				<li>
					<a href="songcircle.php" title="Songcircle - Live Songwriter's Circle"><strong>Songcircle</strong></a>
				</li>
				<li>
					<a href="index.php#contactUs" title="Contact Us" rel="bookmark">Contact Us</a>
				</li>
				<li>
					<a href="#" id="login" title="Log In" rel="next"><b>Log In</b></a>
					 <!-- Login form -->
					<?php  include_once('../includes/forms/login.php'); ?>
				</li>
			</div>
			<span class="menu">&#8801;</span>
		</ul>
	</nav>
	<!-- <div class="register medium"></div> -->
	<button class="register medium" value="Register Today">> Register Today</button>
</header>
<script>
	/**
	* Click event for mobile device mini-menu toggle
	*
	* 03/20/2016
	*
	* Toggle mini-menu color to green
	* Toggle visibility of menu
	*/
	$('ul#links span.menu, ul#links li a').on('click',function(){
		$('ul#links span.menu').toggleClass('active-color');
		$('ul#links div').toggleClass('active');
	})
</script>
