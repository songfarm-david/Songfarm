<?php require_once('../includes/initialize.php');
$user->retrieve_user_data($_GET['id']);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $user->username ?>&apos;s Profile</title>
	<link href="css/workshop.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
</head>
<body>
	<nav>
		<h1>Navigation</h1>
		<span class="logo">Songfarm</span>
		<ul>
			<li>
				<?php if($_SERVER['PHP_SELF'] != $_SERVER['HTTP_REFERER']) { ?>
					<a href="workshop.php">Return to Workshop</a>
				<?php } else { ?>
					<a href="workshop.php"><?php echo $session->username; ?></a>
				<?php } ?>
			</li>
			<li>
				<a href="../includes/sign_out.php">Log Out</a>
			</li>
		</ul>
	</nav>
	<header>
		<div class="user_image" style="background-image:url('../uploaded_images/<?php //echo $image_name; ?>')">
			<!-- Is there a better way to access these pictures?? Absolute Path? -->
		</div>
		<h1><?php echo $user->username; ?></h1>
		<span class="attr">Singer/Songwriter, Lyricist</span>&nbsp;<span style="font-style:italic;">located in</span>&nbsp;<span style="font-weight:bold;">Canoa, Ecuador</span>
		<!-- Input form for user_image -->
		<!-- if session user is visiting his/her own profile, option to return to workshop -->
		<?php if($session->user_id == $_GET['id']){
			echo "<span class=\"profile\"><a href=\"".$_SERVER['HTTP_REFERER']."\">Return to Workshop</a></span>";
		} ?>
	</header>
	<main>


	</main>
</body>
</html>
