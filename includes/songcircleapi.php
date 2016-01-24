<?php
require_once('initialize.php');


	function is_songcircle_started()
	{
		global $db;
		// if something is true, button says register
		$user_id = $_SESSION['user_id'];
		//$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		$sql = "SELECT sc.songcircle_id as songcircle_id FROM songcircle_register sr, songcircle_create sc WHERE sr.songcircle_id = sc.songcircle_id
		AND sr.user_id = $user_id AND sc.songcircle_status = 1";
		//echo $sql . '<br>';
		$songcircle_id = "";
		if($result = $db->query($sql))
		{
			$result_array = $db->fetch_array($result);
			if(is_array($result_array))
			{
				foreach ($result_array as $row )
				{
					//echo " song " . $row;
					$songcircle_id.= $row. ",";
				}
			}
		}
		echo $songcircle_id;
	}

	function is_songcircle_completed()
	{
		global $db;
		// if something is true, button says register
		$user_id = $_SESSION['user_id'];
		//$sql = "SELECT user_id FROM songcircle_register WHERE songcircle_id = '$songcircle_id' AND user_id = $user_id";
		$sql = "SELECT sc.songcircle_id as songcircle_id FROM songcircle_register sr, songcircle_create sc WHERE sr.songcircle_id = sc.songcircle_id
		AND sr.user_id = $user_id AND sc.songcircle_status = 5";
		//echo $sql . '<br>';
		$songcircle_id = "";
		if($result = $db->query($sql))
		{
			$result_array = $db->fetch_array($result);
			if(is_array($result_array))
			{
				foreach ($result_array as $row )
				{
					//echo " song " . $row;
					$songcircle_id.= $row. ",";
				}
			}
		}
		echo $songcircle_id;
	}

		$type = $_REQUEST['type'];
		switch($type)
		{
			case 'start' :
				is_songcircle_started();
				break;
			case 'complete' :
				is_songcircle_completed();
				break;
		}
		
?>
