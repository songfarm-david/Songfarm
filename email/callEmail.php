<?php require_once('../includes/initialize.php');

$songcircle_user_data = [
	'username' => 'Bill',
	'eventTitle' => 'Chopsticks Songcircle',
	'date_time' => 'tomorrow'
];

constructHTMLEmail($email_data['registration'], $songcircle_user_data);

?>
