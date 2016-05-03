<?php require_once('initialize.php');

$login = new validateLogin();

if(isset($_POST['login_submit'])) {
	$username = $login->username;
	$login->validateUser($_POST['username'],$_POST['password']);
} else {
	$username = "";
}

?>
