<?php require_once('../includes/initialize.php');
$user_array = $user->retrieve_user_data($_GET['id']);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Songfarm - <?php echo $user->username ?>&apos;s Profile</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/nav.css" rel="stylesheet" type="text/css">
	<link href="css/workshop.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<style>
	/* temp code -- removes opacity hover on user image */
	.user_image:hover{
		cursor: default;
		opacity: 1;
	}
	</style>
</head>
<body>

	<!-- Top navigation bar -->
	<?php include(LIB_PATH.DS.'layout'.DS.'user_navigation.php'); ?>
	<!-- end of navigation bar -->

	<!-- User header -->
		<?php include('../includes/layout/user_header.php'); ?>
	<!-- end of User header -->

	<main>


	</main>
</body>
</html>
