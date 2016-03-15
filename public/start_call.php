<?php	require_once("../includes/initialize.php");
/**
* This page receives request from songcircle_user_action.php (case: 'join_songcircle')
*
* It first validates url query parameters before connecting user to video_call.php
*
* NOTE: commented out to bypass $_SESSION data -- 03/01/16
* if(!$session->is_logged_in()) { redirectTo('index.php'); }
*
*/
/* begin validation */
  // if query parameters are not present or empty
    if( (!isset($_GET['songcircle_id']) || empty($_GET['songcircle_id'])) &&
        (!isset($_GET['user_id']) || empty($_GET['user_id'])) &&
        (!isset($_GET['verification_key']) || empty($_GET['verification_key'])) ){
      // invalid parameters
        // redirect user
        redirectTo('index.php');
    }
    // if verification keys don't match
    elseif(!$songcircle->verifyKeys($_GET['songcircle_id'],$_GET['user_id'],$_GET['verification_key'])) {
      // keys don't match
        // redirect user
        redirectTo('index.php');
    }
    else
    {
      // query parameters valid
        // set variables
        $songcircle_id = $_GET['songcircle_id'];
        //NOTE: ternary operator first checks $_SESSION for user_id, else from $_GET['user_id']
        $user_id = (isset($session) && isset($session->user_id) ? $session->user_id : $_GET['user_id']);
    }
?>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="description" content="Songfarm nurtures music talent and cultivates careers from the ground up">
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
	<body>
	  <script>
		var conference = null;
		var conferenceId = "<?php echo $songcircle_id ?>";
		var appToken = "<?php echo APP_TOKEN ?>";
		var sessionToken = getQSParam("t");
		var participantId = getQSParam("pid");
		var sessionUserId = "<?php echo $user_id ?>";;

		if (!sessionToken) {
	    //login to get session token
	    participantId = "123" + sessionUserId;

			//"url to send response with the session token";
	    var redirectUrl = location.href;
	    var newredirectUrl = redirectUrl.replace("start_call.php","video_call.php");
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
