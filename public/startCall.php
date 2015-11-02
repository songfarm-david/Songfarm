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
        <link href="css/index.css" rel="stylesheet" type="text/css">
        <link href="css/songfarmvideo.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <script src="https://code.oovoo.com/webrtc/oovoosdk-2.0.0.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
	</head>        
<?php
	
	$songcircle_id = $_GET['songcircleid'];
	if($songcircle_id != '')
	{
		
	}
?>

    
<body>
	
  <script>
	var conference = null;
	var conferenceId = "<?php echo $songcircle_id ?>";
	var appToken = "<?php echo APP_TOKEN ?>";
	var sessionToken = getQSParam("t");
	var participantId = getQSParam("pid");
	if (!sessionToken) {
	    //login to get session token
	    participantId = "123" + <?php  echo $session->user_id ?>;
	    
		//	"url to send response with the session token";
	    var redirectUrl = location.href;
	    var newredirectUrl = redirectUrl.replace("startCall.php","videocall.php");
	    redirectUrl = newredirectUrl + "&pid=" + participantId;
	    
	    ooVoo.API.connect({
	        token: appToken,
	        isSandbox: true,
	        userId: participantId,
	        callbackUrl: redirectUrl
	    });
	}
	else {
		ooVoo.API.init({
	        userToken: sessionToken
	    }, onAPI_init);
	}

	function getQSParam(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : results[1].replace(/\+/g, " ");
    }
</script>  


</body>
   
</html>