<nav>
	<h1>Navigation</h1>
	<span class="logo">Songfarm</span>
	<ul id="settings-trigger">
		<li><?php echo $session->username; ?>
			<ul id="settings-drop-down" class="hide">
				<li>
				<?php
				// if 'profile' is found in the url, option to go to workshop.
				if( strstr($_SERVER['PHP_SELF'], 'profile') || strstr($_SERVER['PHP_SELF'], 'user_settings') ){ ?>
					<a href="workshop.php">Back to Workshop</a>
				<?php } else { ?>
					<a href="profile.php?id=<?php echo $session->user_id; ?>">View Profile</a>
				<?php } ?>
				</li>
				<?php if(strstr($_SERVER['PHP_SELF'], 'user_settings')){ ?>
					<li style="display:none;">
						<a href="user_settings.php">Settings</a>
					</li>
				<?php } else { ?>
					<li>
						<a href="user_settings.php">Settings</a>
					</li>
				<?php } ?>
				<li>
					<a href="../includes/sign_out.php">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<script>
/**
* Controls dropdown navigation menu on
* workshop.php & profile.php
*
*/

// user settings dropdown
$('ul#settings-trigger').on('mouseover', function(){
	$('#settings-drop-down').show();
});
$('ul#settings-drop-down li:first-child, ul#settings-trigger').on('mouseout', function(){
	$('#settings-drop-down').hide();
});
</script>
