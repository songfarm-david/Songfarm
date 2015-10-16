<?php 
	require_once("../includes/initialize.php"); 
	if(!$session->is_logged_in()) { redirect_to('index.php'); }
?>



<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Songfarm nurtures music talent and cultivates songwriters' careers from the ground up!">
        <title>Songfarm - Growing Music Talent From The Ground Up</title>
        <!-- <link rel="shortcut icon" type="image/x-icon" href="images/songfarm_favicon.png" /> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta property="og:url" content="http://www.songfarm.ca">
        <meta property="og:title" content="Cultivating Music Talent From The Ground Up">
        <meta property="og:description" content="Songfarm is a feedback, exposure and live-collaboration platform for aspiring singer/songwriters. Upload your raw videos, receive feedback from the Songfarm Community of Artists, Industry Professionals and Fans and begin growing your career. Register Today!">
        <meta property="og:image" content="http://www.songfarm.ca/images/songfarm_logo_l.png">
        <meta property="og:image:width" content="1772">
        <meta property="og:image:height" content="1170">
        <!-- <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> -->
        
        <link href="css/index.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <script src="https://code.oovoo.com/webrtc/oovoosdk-2.0.0.min.js"></script>
	</head>        
<?php
	
	$songcircle_id = $_GET['songcircleid'];
	if($songcircle_id != '')
	{
// 		global $db;
// 		// query database for a record of global user_id 0. if no rows come back, then run the create method.
// 		$sql = "SELECT songcircle_id, participants, date_of_songcircle FROM songcircle_create WHERE user_id = 0";
// 		if($result = $db->query($sql)){
// 			// fetch array from query
// 			$result_array = $db->fetch_array($result);
// 			// assign values to class properties
// 			$this->songcircle_id = $result_array['songcircle_id'];
// 			$this->date_of_songcircle = $result_array['date_of_songcircle'];
// 			// count num rows..
// 			$row = $db->has_rows($result);
// 			if($row == 1){ // if Open Songcircle exists..
// 				$this->open_songcircle_is_max_parts($this->songcircle_id, $this->max_participants, $this->date_of_songcircle);
// 			} elseif(!$row) { // if NO open songcircle exists..
// 				$this->create_open_songcircle($this->date_of_songcircle);
// 			}
// 		}
		
	}
?>
<body>
	<input type="hidden" name="user_token" id="user_token" value="" />
	<input type="hidden" name="user_id" id="user_id" value="" />
	<input type="hidden" name="confid" id="confid" value="" />
	<div class="video_Call" border =1>
		<table width="60%" height ="60%">
			<tr>
				<td colspan=3 > 
					<video id="localVideo" style="width:60%; height:auto;" autoplay muted></video>
    			</td>
    		</tr>
    		<tr>
				<td colspan=3 > 
					<div id= "participantsvideo" > 
						
					</div>
    			</td>
    		</tr>
    		<tr>
    			<td>	
					<input type="button" name="video_off" id="video_off" value="Video Off" onClick="setVideoOff()">
				</td>
				<td>
					<input type="button" name="audio_mute" id="audio_mute" value="Audio Off" onClick="setAudioMute()" >
				</td>
				<td>
					<input type="button" name="disconnect" id="disconnect" value="Disconnect" onClick="disconnect()" >
				</td>
			</tr>
		</table>	
    
    </div>
</body>




    <script type="text/javascript">
        var conference = null;
        var conferenceId = "<?php echo $songcircle_id ?>";
        var appToken = "<?php echo APP_TOKEN ?>";
        //alert(conferenceId);
		var sessionToken = getQSParam("t");
        var participantId = getQSParam("pid");

        if (!sessionToken) {
            //login to get session token
            participantId = "123452" + <?php  echo $session->user_id ?>;
            //participantId = 1123456;
            //for example (get random id)
            //participantId = Math.floor(Math.random() * 9999999999) + 1000000000;

            var redirectUrl = "url to send response with the session token"
            redirectUrl = location.href + "&pid=" + participantId;
            
            ooVoo.API.connect({
                token: appToken,
                isSandbox: true,
                userId: participantId,
                callbackUrl: redirectUrl
            });
            alert('ooVoo.API.connect');
        }
        else {
        	alert('ooVoo.API.init');
        	
            ooVoo.API.init({
                userToken: sessionToken
            }, onAPI_init);
        }

        function onAPI_init(res) {
        	conference = ooVoo.API.Conference.init({ video: true, audio: true }, onConference_init);
    
        }
        function onConference_init(res) {
        	alert('ooVoo.API.onConference_init');
        	//log("callback: ooVoo.API.Conference.init: " + JSON.stringify(res));
        	alert(res.error);
            if (!res.error) {
            	//log("init callback event functions")
                //log("init callback event functions")
                //register to conference events
                conference.onParticipantJoined = onParticipantJoined;
                conference.onParticipantLeft = onParticipantLeft;
                //conference.onRecvData = onRecieveData;
                //conference.onVideoRotate = onVideoRotate;
                conference.onLocalStreamPublished = onStreamPublished;
                conference.onConferenceStateChanged = onConferenceStateChanged;
                conference.onRemoteVideoStateChanged = onRemoteVideoStateChanged

                conference.setConfig({
                    videoResolution: ooVoo.API.VideoResolution["HIGH"],
                    videoFrameRate: new Array(5, 15)
                }, function (res) {
                    if (!res.error) {
                        //var username = <?php  echo $session->username; ?>
                        //conference.join(conferenceId, participantId, sessionToken, username, function (result) { });
                    	conference.join(conferenceId, participantId, sessionToken, "pradip", function (result) { });
                    }
                });
            }
        }

        function onStreamPublished(stream) {
        	alert('onStreamPublished');
            document.getElementById("localVideo").src = URL.createObjectURL(stream.stream);
        }

        function onParticipantLeft(evt) {
        	//alert('onParticipantLeft');
            if (evt.uid) {
                //document.getElementById("vid_" + evt.uid).remove();
                $("#vid_" + evt.uid).remove();
            }
        }
        function onParticipantJoined(evt) {
            //alert('onParticipantJoined');
            if (evt.stream && evt.uid != null) {
                //alert(evt.uid);
               var videoElement = document.createElement("video");
                videoElement.id = "vid_" + evt.uid;
                videoElement.src = URL.createObjectURL(evt.stream);
                videoElement.setAttribute("autoplay", true);
                document.body.appendChild(videoElement);
            
                
                //var videoElement = document.createElement("video");
                //videoElement.id = "vid_" + evt.uid;
                //videoElement.src = URL.createObjectURL(evt.stream);
                //videoElement.setAttribute("autoplay", true);
                //document.body.participantsvideo.appendChild(videoElement);
               //$('#participantsvideo').append(videoElement);

               
                //$('#participantsvideo').append("<video id='vid_'+ evt.uid style='width:auto;height:auto;' autoplay > </video>");
                //$('#vid_'+ evt.uid).src = URL.createObjectURL(evt.stream);        
                //$("#participantsvideo").append("<div class='col-sm-3'><div class='searchtags'><a class='boxclose' id='boxclose' onclick='removeTag(this)'></a>"+
                 //       "<h4><strong>#"+name+"</strong></h4>"+
                  //      "<h5><strong>"+counttoDisplay+" posts </strong></h5></div></div>");
    			
            }	
        }
        function onConferenceStateChanged(evt) {
        	//alert('onConferenceStateChanged');
        }
        function onRemoteVideoStateChanged(evt) {
        	//alert('onRemoteVideoStateChanged');
        	if (evt.stream && evt.uid != null) {
                //alert(evt.uid);
                //document.body.participantsvideo.appendChild(videoElement);
               $('#vid_' + evt.uid).src = URL.createObjectURL(evt.stream);
                
                //$('#participantsvideo').append("<video id='vid_'+ evt.uid style='width:auto;height:auto;' autoplay > </video>");
                //$('#vid_'+ evt.uid).src = URL.createObjectURL(evt.stream);        
                //$("#participantsvideo").append("<div class='col-sm-3'><div class='searchtags'><a class='boxclose' id='boxclose' onclick='removeTag(this)'></a>"+
                 //       "<h4><strong>#"+name+"</strong></h4>"+
                  //      "<h5><strong>"+counttoDisplay+" posts </strong></h5></div></div>");
    			
            }	
 
        }

        function setAudioMute() {
        	//alert('SetAudio');
        	if(conference != null)
        	{
        		var bAudioMute = conference.getLocalAudioMute();
        		//alert(bAudioMute);
        		conference.setLocalAudioMute(!bAudioMute);
        		if(bAudioMute)
        			$("#audio_mute").attr('value', 'Audio On')
        		else
        			$("#audio_mute").attr('value', 'Audio Off')
        	}
        }

        function setVideoOff() {
        	//alert('SetAudio');
        	if(conference != null)
        	{
        		var bVideoOff = conference.getLocalVideoState();
            	//alert(bVideoOff);
            	if(bVideoOff)
            	{
            		conference.playLocalVideo()();
            		$("#video_off").attr('value', 'Video Off')
            	}
            	else
            	{
            		conference.stopLocalVideo();;
            		$("#video_off").attr('value', 'Video On')
                }
        	}
        }
        function disconnect() {
        	//alert('SetAudio');
        	if(conference != null)
        	{
        		conference.disconnect();
        	}
        }
        
        function getQSParam(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : results[1].replace(/\+/g, " ");
        }
    </script>
</html>