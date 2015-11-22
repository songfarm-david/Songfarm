<nav>
	<h1>Navigation</h1>
	<span class="logo">Songfarm</span>
	<ul id="settings-trigger">
		<li><?php echo $session->username; ?>
			<ul id="settings-drop-down" class="hide">
				<li>
				<?php
				// if 'profile' is found in the url, option to go to workshop.
				if( strstr($_SERVER['PHP_SELF'], 'profile') ){ ?>
					<a href="workshop.php">Back to Workshop</a>
				<?php } else { ?>
					<a href="profile.php?id=<?php echo $session->user_id; ?>">View Profile</a>
				<?php } ?>
				</li>
				<li>
					<a href="user_settings.php" class="test">Settings</a>
				</li>
				<li>
					<a href="../includes/sign_out.php">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<script src="js/nav_menu_dropdown.js" type="text/javascript"></script>
