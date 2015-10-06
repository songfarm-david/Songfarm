<?php require_once('initialize.php');

$songcircle = new Songcircle;

$songcircle->delete_songcircle($_GET['songcircle_id']);
?>
