<header id="header" >
	<img src="images/top-bar.png">
	<ul id="social_head">
		<li class="facebook"></li>
		<li class="twitter"></li>
		<li class="linkedIn"></li>
		<li><a href="index.php#contactUs" title="Contact Us" rel="bookmark"></a></li>
	</ul>
	<h1 class="hide">Songfarm</h1><!-- h1 hidden // anchored link to top -->
	<nav>
		<h2 class="hide">Navigation</h2><!-- nav h2 is hidden -->
		<a href="index.php"><div class="logo"></div></a>
		<ul id="links">
			<li><!-- remember to use a class="active" on links that are currently active -->
				<a href="index.php#about" title="About Songfarm" rel="bookmark">About</a>
			</li>
			<li>
				<a href="index.php#features" title="Songfarm features" rel="bookmark">Features</a>
			</li>
			<li>
				<a href="songcircle.php" title="Songcircle - A virtual songwriter's circle"><strong>Songcircle</strong></a>
			</li>
			<li>
				<a href="index.php#contactUs" title="Contact Us" rel="bookmark">Contact Us</a>
			</li>
			<li>
				<a href="#" id="login" title="Log In" rel="next"><b>Log In</b></a>
				<!-- Login form -->
				<?php include_once('../includes/forms/login.php'); ?>
			</li>
		</ul>
	</nav>
	<div class="register medium"></div>
</header>
