<?php
	require_once("../includes/initialize.php");
	set_time_limit(3000);

	/* who calls this page?? Where is this GET request originating from? */
	switch($_GET['method']) {

	 	case 'songcircleState':
	 		file_put_contents('./logfile/log_'.date("j.n.Y").'.txt',PHP_EOL. date("G:i:s"). " CronJob songcircleState started", FILE_APPEND);
	 		global $db;

			// query database for a record of global user_id 0. if no rows come back, then run the create method.
			$sql = "SELECT * FROM songcircle_create WHERE songcircle_status <> 5";
			//$currentTime = date('Y-m-d H:i:s', time());

			$currentTime = time();

			//echo " currentTime " . $currentTime ;

			if($result = $db->query($sql))
			{
				foreach($result as $row)
				{
					$songcircle_id = $row['songcircle_id'];
					$date_of_songcircle = $row['date_of_songcircle'];
					$songcircledatetime = strtotime($date_of_songcircle);
					$diff= ($currentTime - $songcircledatetime);
					$diffinMin = $diff/60;
					//echo " diff in hours ". $diffinMin;

					if($diffinMin <= 0 && $diffinMin > -120)
					{
						//update the table with status to started
						$updatesql = "Update songcircle_create set songcircle_status = 1 where songcircle_id = '$songcircle_id'";
						echo $updatesql;
						$db->query($updatesql);
											}
					else if($diffinMin > 0 && $diffinMin < (3*60*60))
					{
						//update the table with status to completed
						$updatesql = "Update songcircle_create set songcircle_status = 5 where songcircle_id = '$songcircle_id'";
						echo $updatesql;
						$db->query($updatesql);
					}
				}
			}
	}
?>
