<?php require_once('../includes/initialize.php'); include_once('../includes/countries_array.php');
if(!$session->is_logged_in()) { redirect_to('index.php'); }
if(isset($_POST['submit_location'])){
	$user->update_timezone($session->user_id, $_POST['timezone'], $_POST['country'], $_POST['full_timezone']);
}
$user->has_location($session->user_id);
$user->retrieve_user_data($session->user_id);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $user->username ?>&apos;s settings</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/nav.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
</head>
<body>
	<?php include(LIB_PATH.DS.'layout'.DS.'user_navigation.php'); ?>
	<section id="user_settings">
		<h1><?php echo $user->username ?>&apos;s settings</h1>
		<hr>
		<article>
			<h2>Current Timezone:</h2>
			<p><?php echo $user->city . ", " . $user->country . " -- " . $user->full_timezone; ?>&nbsp;<span class="edit">Edit</span></p>
			<?php include_once('../includes/layout/timezone_form.php'); ?>
		</article>
		<a href="workshop.php">Back</a>
	</section>
</body>
</html>
