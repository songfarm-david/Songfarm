<?php

require_once("../includes/initialize.php");
require_once("../includes/cronjob_helper.php");
set_time_limit(3000);
global $db;

//unregister 127.0.0.1/public/userAction.php?songcircle_id=56a9237402e8c&userid=16&action=Unregister&confirmation=QK0wOl78W0yI94lBVNV6j72EN9nAZZ8HFl209juO
//Join 127.0.0.1/public/userAction.php?songcircle_id=56a9237402e8c&userid=16&action=Join&confirmation=QK0wOl78W0yI94lBVNV6j72EN9nAZZ8HFl209juO


switch($_GET['action']) {

	case 'Unregister':
		{
			
			if(isset($_GET['songcircle_id']) && isset($_GET['userid'])  && isset($_GET['$confirmationKey']))
			{
				$songcircle_id = $_GET['songcircle_id'];
				$userid = $_GET['userid'];
				$confirmationKey = $_GET['confirmation'];
				
				//sanitize values
				$songcircle_id = $db->escape_value($songcircle_id);
				
				
				// confirm selection
				$sql = "SELECT count(*) FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
				if(!$result = $db->query($sql)){
					exit;
				} else {
					if($row = $db->has_rows($result) == 1){
						// remove record from database
						$sql = "DELETE FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
						if($result = $db->query($sql)){
							// confirm affected rows
							if ( $db->hasAffectedRows() ){
								// success
								$success_msg = 'Record successfully deleted. <br><br>Redirecting now...';
								
								//header("Location: " . $joinConfLink); /* Redirect browser */
							}
						} // end of: if($result = $db->query($sql))
					} // end of: if($row = $db->has_rows($result) == 1)
				}
		
			} // end of: if(isset($_GET['songcircle_id']) && isset($_GET['userid'])  && isset($_GET['$confirmationKey']))
			
			
			break;
		}
		
	case 'Join':
		{
			
			if(isset($_GET['songcircle_id']) && isset($_GET['userid'])  && isset($_GET['$confirmationKey']))
			{
				$songcircle_id = $_GET['songcircle_id'];
				$userid = $_GET['userid'];
				$confirmationKey = $_GET['confirmation'];
			
				//sanitize values
				$songcircle_id = $db->escape_value($songcircle_id);
			
			
				// confirm selection
				$sql = "SELECT count(*) FROM songcircle_register WHERE songcircle_id = '{$songcircle_id}' AND user_id = {$user_id}";
				if(!$result = $db->query($sql)){
					exit;
				} else {
					if($row = $db->has_rows($result) == 1){
						//join the video conference room
						//get user email and send email
						$joinConfLink = $_SERVER['SERVER_ADDR'].'/public/startCall.php?songcircleid='.$songcircle_id.'&userid='.$user_id;
						header("Location: " . $joinConfLink); /* Redirect browser */
						exit();
						
						} // end of: if($result = $db->query($sql))
					} // end of: if($row = $db->has_rows($result) == 1)
				}
			
			}
		}
	?>