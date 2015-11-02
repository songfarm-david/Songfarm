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
        <script type="text/javascript" src="js/songfarm.VideoHelper-0.0.5.js"></script>	
    	<script type="text/javascript" src="js/jquery-ui.js"></script>
	</head>        
<?php
	
	$songcircle_id = $_GET['songcircleid'];
	if($songcircle_id != '')
	{

	}
?>

    <script>
    var isFireFox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

	var conference = null;
	var conferenceId = "<?php echo $songcircle_id ?>";
	var appToken = "<?php echo APP_TOKEN ?>";
	
	var sessionToken = getQSParam("t");
	var user_token = sessionToken;

	var participantId = getQSParam("pid");
	log("calling: ooVoo.API.init");
	
	var resolution = "HIGH";		//"NORMAL";
	var isResolutionSupported = true;
	var enableLogs = true;

	try {
	    document.addEventListener("fullscreenchange", onFullScreenStateChanged, false);
	    document.addEventListener("webkitfullscreenchange", onFullScreenStateChanged, false);
	    document.addEventListener("mozfullscreenchange", onFullScreenStateChanged, false);
	} catch(e){}

	
	
	var myId = "";
	var callParticipants = new Array();
	
	if (!sessionToken) {
	    //login to get session token
	    participantId = "123" + <?php  echo $session->user_id ?>;
	
	    var redirectUrl = location.href + "&pid=" + participantId;
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

	function onAPI_init(res) {
	    if (res && res.error) {
			//alert(res.error);
	        showPopup('dvNotSupported');
	    } else {
	        log("callback: getUserMedia");
	        log("calling: ooVoo.API.Conference.init");
	        conf = ooVoo.API.Conference.init({ video: true, audio: true }, onConference_init);
	    }
	}

	function onConference_init(res) {
	    log("callback: ooVoo.API.Conference.init: " + JSON.stringify(res));
	    if (!res.error) {
	        log("init callback event functions");
	        conf.onParticipantJoined = onParticipantJoined;
	        conf.onParticipantLeft = onParticipantLeft;
	        conf.onRecvData = onRecieveData;
	        conf.onVideoRotate = onVideoRotate;
	        conf.onConferenceStateChanged = onConferenceStateChanged
	        conf.onRemoteVideoStateChanged = onRemoteVideoStateChanged;
	        conf.onLocalStreamPublished = onStreamPublished;

	        join();
	    }
	    else {
	        error(JSON.stringify(res));
	    }
	}

	function onConference_setConfig(res) {
	    showPopup("dvAccess");
	    log("callback: ooVoo.API.Conference.setConfig:" + JSON.stringify(res));
	    if (!res.error) {
	        log("calling: conf.join");
	        //user login
	        conf.join(document.getElementById("confid").value, $("#user_id").val(), user_token, document.getElementById("dname").value, function (res) {
	            log("callback: conf.join: " + JSON.stringify(res));
	            if (!res.error) {
	                log("Trying to join conversation: " + document.getElementById("confid").value);
	            }
	            else
	                error(JSON.stringify(res));
	        });
	    }
	    else {
	        error(JSON.stringify(res));
	    }
	}

	function onParticipantLeft(evt) {
	    log("user (" + evt.uid + ") left the conversation.");
	    if (evt.uid) {
	        var index = callParticipants.indexOf(evt.uid);
	        callParticipants.splice(index, 1);
	        VideoHelper.setCallType(callParticipants.length);
	        drawCallWindows();
	        VideoHelper.removeParticipantThatlLeft(evt.uid);
	    }
	}
	function onParticipantJoined(evt) {
	    if (evt.stream && evt.uid != null) {
	        log("someone (" + evt.uid + ") else joined the conversation.");
	        callParticipants.push(evt.uid);
	        VideoHelper.setCallType(callParticipants.length);
	        publishDisplayName();
	        var mainContainer = document.getElementById("mainVideoContainer");
	        var mainVideo = mainContainer.getElementsByTagName("video")[0];
	        var mainVideoUid = mainVideo.id.replace("vid_", "");
	        var showMainVideo = isVisible(mainVideo);
	        var mainFilter = VideoHelper.getFilter(mainVideoUid);
	        drawCallWindows();
	        VideoHelper.moveVideoFromMainToNewWindow(showMainVideo, mainFilter, function () {
	            var stream = URL.createObjectURL(evt.stream);
	            var userDname = decode_utf8(evt.user_data);
	            VideoHelper.addNewParticipantToMainWindow(evt.uid, userDname, stream);
	            publishFilter();
	        });
	    }
	}
	function onRecieveData(evt) {
	    var data = JSON.parse(evt);
	    var msg = decode_utf8(data.Buffer);
	    try {
	        var inCallData = JSON.parse(msg);
	        if (inCallData.action == "change_name") {
	            var uid = data.SourceID;
	            var name = inCallData.value;
	            onDisplayNameChanged(uid, name);
	        }
	        if (inCallData.action == "change_filter") {
	            var uid = data.SourceID;
	            var filter = inCallData.value;
	            onFilterChanged(uid, filter);
	        }
	    } catch (e) {
	        log(msg);
	    }
	}
	function onVideoRotate(evt) {
	    if (evt.mrr) {
	        VideoHelper.mirrorVideo("vid_" + evt.uid);
	    }
	    VideoHelper.rotateVideo("vid_" + evt.uid, evt.deg);
	}
	function onConferenceStateChanged(evt) {
	    switch (evt.type) {
	        case ooVoo.API.ConferenceStateEventType.ACCESS_ACCEPTED:
	            log("ooVoo.API.ConferenceStateEventType.ACCESS_ACCEPTED");
	            hidePopup("dvAccess");
	            break;
	        case ooVoo.API.ConferenceStateEventType.JOINED:
	            log("ooVoo.API.ConferenceStateEventType.JOINED");
	            $("#disconnect").removeClass("disconnect_disabled");
	            $("#disconnect").addClass("disconnect");
	            $("#localMic").removeClass("disabled");
	            $("#localSpk").removeClass("disabled");
	            $("#localCam").removeClass("disabled");
	            $("#localFilter").removeClass("disabled");
	            myId = evt.uid;
	            var mainVideo = document.getElementById("mainVideo");
	            mainVideo.id = "vid_" + myId;
	            callParticipants.push(myId);

	            var mainDName = document.getElementById("mainDisplayName");
	            mainDName.textContent = document.getElementById("dname").value;
	            mainDName.id = "name_" + myId;
	            //document.querySelector("#mainVideoContainer div").style.display = "block";
	            document.querySelector(".main_control_bar").id = "control_" + myId;
	            break;
	        case ooVoo.API.ConferenceStateEventType.ACCESS_DENIED:
	            log("ooVoo.API.ConferenceStateEventType.ACCESS_DENIED");
	            showPopup("dvDenied");
	            //conf.disconnect();
	            break;
	        case ooVoo.API.ConferenceStateEventType.DEVICE_NOT_FOUND:
	            log("ooVoo.API.ConferenceStateEventType.DEVICE_NOT_FOUND");
	            showPopup("dvNotFound");
	            break;
	        case ooVoo.API.ConferenceStateEventType.CAM_RES_NOT_SUPPORTED:
	            log("ooVoo.API.ConferenceStateEventType.CAM_RES_NOT_SUPPORTED");
	            isResolutionSupported = false;
	            conf.disconnect();
	            break;
	        case ooVoo.API.ConferenceStateEventType.CONNECTED:
	            log("ooVoo.API.ConferenceStateEventType.CONNECTED");
	            //showPopup("dvName");
	            break;
	        case ooVoo.API.ConferenceStateEventType.DISCONNECTED:
	            log("ooVoo.API.ConferenceStateEventType.DISCONNECTED");
	            var $vid = $("#vid_" + myId);
	            if ($vid) {
	                $vid.attr("src", "");
	                hideVideo($vid,true);
	                $vid.attr("id", "mainVideo");
	                $dname = $("#name_" + myId);
	                $dname.text("");
	                $dname.attr("id", "mainDisplayName");
	            }
	            callParticipants = new Array();
	            onDisconnect();
	            break;
	    }
	}
	function onRemoteVideoStateChanged(evt) {
	    switch (evt.state) {
	        case ooVoo.API.VideoStateType.PLAY:
	            log("ooVoo.API.VideoStateType.PLAY");
	            showVideo($("#vid_" + evt.uid));
	            break;
	        case ooVoo.API.VideoStateType.STOPPED:
	            log("ooVoo.API.VideoStateType.STOPPED");
	            hideVideo($("#vid_" + evt.uid), false);
	            break;
	        default:

	    }
	}
	function onStreamPublished(stream)
	{
	    addLocalVideo(stream);
	}
	function addLocalVideo(stream) {
	    var $mainVideo = $($(".localVideo")[0]);
	    var mainVideoContainerId = $mainVideo.parent().attr("id");
	    $mainVideo.attr("src",URL.createObjectURL(stream.stream));
	    $mainVideo.on("canplaythrough", function () {
	        showVideo($mainVideo);
	    });
	}
	function onDisconnect() {
	    $("#disconnect").removeClass("disconnect");
	    $("#disconnect").addClass("disconnect_disabled");
	    $("#mainVideoContainer").removeClass("in_call");
	    $("#mainVideoContainer").html("<video autoplay id=\"mainVideo\" muted class=\"localVideo\" style=\"display:none;\" ></video><div id=\"mainDisplayName\" class=\"displayname\"></div>");
	    $("#remoteVideos").html("");
	    $("#localMic").removeClass("mic_muted");
	    $("#localSpk").removeClass("spk_muted");
	    $("#localCam").removeClass("cam_stopped");
	    $("#localMic").addClass("disabled");
	    $("#localSpk").addClass("disabled");
	    $("#localCam").addClass("disabled");
	    $("#localFilter").addClass("disabled");
	    exitFullScreen();
	    if (!isResolutionSupported) {
	        setTimeout(function () { 
	            switchResolution();
	        });
	    } else {
	        returnToBaseUrl();
	    }
	    
	}
	function error(msg) {
	    log(msg);
	}
	function join() {
	    document.getElementById("mainVideoContainer").classList.add("in_call");
	    log("calling: ooVoo.API.Conference.setConfig: " + resolution);
	    conf.setConfig({ videoResolution: ooVoo.API.VideoResolution[resolution], videoFrameRate: new Array(5, 15) }, onConference_setConfig);
	}
	function log(msg) {
	    if (enableLogs) {
	        console.log(msg);
	    }
	}
	function sendCustomeMessage() {
	    var message = document.querySelector("#customeMsg").value;
	    var to = document.querySelector("#ddlContacts")[document.querySelector("#ddlContacts").selectedIndex].value;
	    ooVoo.API.Conference.sendData(myId, to, message, function (msg) {
	        if (msg && msg.error) {
	            log(JSON.stringify(msg.error));
	        }
	        else {
	            log("InCall Message Sent");
	        }
	    });
	}
	function onMsgParametersChanged() {
	    if (document.querySelector("#dv-send-msg").style.display == "none") {
	        return;
	    }
	    setTimeout(function () {
	        if (document.querySelector("#ddlContacts").selectedIndex >= 0) {
	            if (document.querySelector("#customeMsg").value.length == 0) {
	                document.querySelector("#btn-send-msg").setAttribute("disabled", true);
	            }
	            else {
	                document.querySelector("#btn-send-msg").removeAttribute("disabled");
	            }
	        }
	    });
	}
	function muteLocal(event) {
	    var isLocalAudioMute = ooVoo.API.Conference.getLocalAudioMute();
	    ooVoo.API.Conference.setLocalAudioMute(!isLocalAudioMute);
	    isLocalAudioMute = ooVoo.API.Conference.getLocalAudioMute();
	    if (isLocalAudioMute) {
	        $("#localMic").addClass("mic_muted");
	        $("#localMic").attr("title","unmute mic");
	    }
	    else {
	        $("#localMic").removeClass("mic_muted");
	        $("#localMic").attr("title", "mute mic");
	    }
	    ignoreOtherEvents(event);
	}
	function muteRemote(event) {
	    var isRemoteAudioMute = ooVoo.API.Conference.getRemoteAudioMute();
	    ooVoo.API.Conference.setRemoteAudioMute(!isRemoteAudioMute);
	    isRemoteAudioMute = ooVoo.API.Conference.getRemoteAudioMute();
	    if (isRemoteAudioMute) {
	        $("#localSpk").addClass("spk_muted");
	        $("#localSpk").attr("title", "unmute speakers");
	    }
	    else {
	        $("#localSpk").removeClass("spk_muted");
	        $("#localSpk").attr("title", "mute speakers");
	    }
	    ignoreOtherEvents(event);
	}
	function stopLocal(event) {
	    var isLocalVideoStopped = ooVoo.API.Conference.getLocalVideoState();
	    if (!isLocalVideoStopped) {
	        ooVoo.API.Conference.stopLocalVideo();
	    }
	    else {
	        ooVoo.API.Conference.playLocalVideo();
	    }
	    isLocalVideoStopped = ooVoo.API.Conference.getLocalVideoState();
	    var $localvideo = $(".localVideo");
	    if (isLocalVideoStopped) {
	        hideVideo($localvideo, true);
	        $("#localCam").attr("title", "show video");

	    }
	    else {
	        showVideo($localvideo);
	        $("#localCam").attr("title", "stop video");
	    }
	    ignoreOtherEvents(event);
	}
	function stopRemote(event, uid) {
	    ooVoo.API.Conference.unRegisterRemoteVideo(uid);
	    hideVideo($("#vid_" + uid),true);
	    ignoreOtherEvents(event);
	}
	function startRemote(event, uid) {
	    ooVoo.API.Conference.registerRemoteVideo(uid);
	    showVideo($("#vid_" + uid));
	    ignoreOtherEvents(event);
	}
	function drawCallWindows()
	{
	    var mainVideoContainer = document.getElementById("mainVideoContainer");
	    var remoteVideosContainer = document.getElementById("remoteVideosContainer");

	    VideoHelper.drawCallWindows(callParticipants.length, mainVideoContainer, remoteVideosContainer);
	}
	function ignoreOtherEvents(event)
	{
	    if (!event) var event = window.event;
	    event.cancelBubble = true;
	    if (event.stopPropagation) {
	        event.stopPropagation()
	    }
	}
	function showVideo($videoElement) {
	    var uid = $videoElement.attr("id").replace("vid_", "");
	    var $camSpan = $("#control_" + uid + " .cam");
	    if ($camSpan) {
	        $camSpan.removeClass("cam_stopped");
	        $camSpan.removeAttr("onclick");

	        if ($videoElement.hasClass("localVideo")) {
	            $camSpan.attr("onclick", "stopLocal(event)");
	        }
	        else {
	            $camSpan.attr("onclick", "stopRemote(event, \"" + uid + "\")");
	        }
	    }
	        
	    $videoElement.show(function () {
	        VideoHelper.optimizeVideo($videoElement.parent().attr("id"));
	    });
	}
	function hideVideo($videoElement, enableClick) {
	    if (!$videoElement.attr("id")) {
	        return;
	    }
	    var uid = $videoElement.attr("id").replace("vid_", "");
	    var $camSpan = $("#control_" + uid + " .cam");
	    $camSpan.addClass("cam_stopped");
	    $camSpan.removeAttr("onclick");
	    if (enableClick) {
	        if ($videoElement.hasClass("localVideo")) {
	            $camSpan.attr("onclick", "stopLocal(event)");
	        }
	        else {
	            $camSpan.attr("onclick", "startRemote(event, \"" + uid + "\")");
	        }
	    }
	    else {
	        $camSpan.attr("onclick", "ignoreOtherEvents(event)");
	    }
	    $videoElement.hide();
	}
	function isVisible(element,callback) {
	    return element.style.display != "none";
	}
	function decode_utf8(s) {
	    try {
	        return decodeURIComponent(escape(s));
	    } catch (e) {
	        return s;
	    }
	}
	function showPopup(dvId)
	{
	    $('.popup_form').hide();
	    $("#dvBackground").show();
	    $("#" + dvId).show();
	    if (isFireFox && !$(".popup_allow_access").hasClass("firefox")) {
	        $(".popup_allow_access").addClass("firefox");
	        $(".popup_access_denied").addClass("firefox");
	    }
	}

	function hidePopup(dvId) {
	    $("#dvBackground").hide();
	    $("#" + dvId).hide();
	}

	function changeDisplayName()
	{
	    var dname = $("#dname").val();
	    if (!dname || dname == "") {
	        closeDisplayNameWin();
	        return;
	    }
	    var message = "{ \"action\": \"change_name\" ,\"value\": \"" + dname + "\" }";
	    ooVoo.API.Conference.sendData(myId, "", message, function (msg) {
	        //hidePopup('dvName');
	        $("#name_" + myId).text(dname);
	        if (msg && msg.error) {
	            log(JSON.stringify(msg.error));
	        }
	    });
	}

	function publishDisplayName() {
	    if (!$("#name_" + myId) || $("#name_" + myId).text() == "") {
	        return;
	    }
	    var message = "{ \"action\": \"change_name\" ,\"value\": \"" + $("#name_" + myId).text() + "\" }";
	    ooVoo.API.Conference.sendData(myId, "", message, function (msg) {
	        if (msg && msg.error) {
	            log(JSON.stringify(msg.error));
	        }
	    });
	}

	function closeDisplayNameWin() {
	    $("#dname").val("");
	    hidePopup('dvName');
	}

	function onDisplayNameChanged(uid, name)
	{
	    $("#name_" + uid).text(name);
	}

	function changeFilter(filterId) {
	    var message = "{ \"action\": \"change_filter\" ,\"value\": \"" + filterId + "\" }";
	    ooVoo.API.Conference.sendData(myId, "", message, function (msg) {
	        $("#ddlFilters").hide();
	        VideoHelper.setFilter(myId, filterId);
	        if (msg && msg.error) {
	            log(JSON.stringify(msg.error));
	        }
	    });
	}
	function popupKeyPress(e,callbackEnter, callbackEscape)
	{
	    var keycode;
	    if(window.event) keycode = window.event.keyCode;
	    else if (e) keycode = e.which;
	    else return true;
	    
	    if (keycode == 13) {
	        callbackEnter();
	    }
	    else if (keycode == 27)
	    {
	        callbackEscape();
	    }
	}
	function publishFilter() {
	    var message = "{ \"action\": \"change_filter\" ,\"value\": \"" + VideoHelper.getFilter(myId)+ "\" }";
	    ooVoo.API.Conference.sendData(myId, "", message, function (msg) {
	        if (msg && msg.error) {
	            log(JSON.stringify(msg.error));
	        }
	    });
	}
	function onFilterChanged(uid, filter) {
	    VideoHelper.setFilter(uid, filter);
	}
	function toggleFullScreen() {
	    if (isFullScreen()) {
	        exitFullScreen();
	    }
	    else {
	        enterFullScreen();
	    }
	}
	function enterFullScreen()
	{
		var el = document.documentElement, rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen;
	    rfs.call(el);
	}
	function exitFullScreen() {
	    if (document.exitFullscreen)
	        document.exitFullscreen();
	    else if (document.msExitFullscreen)
	        document.msExitFullscreen();
	    else if (document.mozCancelFullScreen)
	        document.mozCancelFullScreen();
	    else if (document.webkitExitFullscreen)
	        document.webkitExitFullscreen();
	}
	function onFullScreenStateChanged() {
	    if (!isFullScreen()) {
	        $(".callContainer").height("540px").width("960px");
	        $(".videoWinContainer").height("540px").width("960px");
	        $(".main_control_bar").width("100%");
	        VideoHelper.optimizeVideosAfterWindowsChanged();
	    }
	    else {
	        $(".callContainer").height("90%").width("100%");
	        $(".videoWinContainer").height("90%").width("100%");
	        $(".main_control_bar").width("100%");
	        VideoHelper.optimizeVideosAfterWindowsChanged();
	    }
	}
	function isFullScreen() {
	    if (document.mozFullScreenElement) {
	        return true;
	    }
	    return (document.webkitFullscreenElement || document.fullscreenElement);
	}
	function returnToBaseUrl()
	{
	    var confId = document.getElementById("confid").value;
	    var a = location.pathname.split("/");
	    if (a.length > 2 && a[2] == confId) {
	        parent.postMessage("", "*");
	        location.href = location.origin + "/" + a[1];
	    }
	}
	function switchResolution()
	{
	    if (resolution == "HD") {
	        resolution = "HIGH";
	    } else {
	        resolution = "NORMAL";
	    }
	    isResolutionSupported = true;
	    join();
	}
	function getQSParam(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : results[1].replace(/\+/g, " ");
    }

</script>
<body>
	<div>
        <input type="hidden" id="confid" value="<?php echo $songcircle_id; ?>" />
        <input type="hidden" id="user_token" value=""  />
        <input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
    </div>
    <div class="callContainer" ondblclick="toggleFullScreen();">
        <div id="popupsContainer">
            <div id="dvBackground" class="popup_background"></div>
            <div id="dvAccess" class="popup_form" style="display: none; height: 250px;top:125px;">
                <h2>Allow camera and mic access </h2>
                <div class="popup_allow_access">
                </div>
                <div style="text-align:center;font-size:12px;margin-left:20px;margin-right:20px;">Click the Allow button in the upper corner of your browser to enable real-time communication.</div>
            </div>
            <div id="dvDenied" class="popup_form" style="display: none; height: 240px;top:125px;">
                <h2>Allow camera and mic access </h2>
                <div class="popup_access_denied">
                </div>
                <div style="text-align:center;font-size:12px;margin-left:20px;margin-right:20px;">
                Find this icon in the URL bar and allow ooVoo access your media devices.</div>
            </div>
            <div id="dvNotFound" class="popup_form" style="display: none; height: 165px; top: 163px;">
                <h2>Media device not found </h2>
                <div style="text-align:center;font-size:16px;margin-left:20px;margin-right:20px;">
                  Please make sure you have camera and microphone connected.
                </div>
            </div>
            <div id="dvName" class="popup_form" style="height: 135px; top: 178px;">
                <div class="popup_close" onclick="closeDisplayNameWin();"></div>
                <br />
                <input id="dname" type="text" value="" maxlength="30" placeholder="Your Name" onkeyup="popupKeyPress(event,changeDisplayName, closeDisplayNameWin);" />
                <br />
                <br />
                <input type="button" value="CONTINUE" onclick="changeDisplayName();" style=" width: 110px !important; height: 40px !important; line-height: 40px; margin: 0px 0 35px 50px !important;" />
                <br />
             </div> 
<!--            <div id="dvNotSupported" class="popup_form" style="height: 178px; top: 156px;"> -->
<!--                 <h2>Sorry for the inconvenience</h2> -->
<!--                 <div class="popup_browser_not_supported"></div> -->
<!--                  	<div style="float: left; margin-left: 4px;"> -->
<!--                     Your browser is incompatible ! -->
<!--                     <br />Please use latest Chrome/Firefox/Opera<br /> browsers. -->
<!--                 </div> -->
<!--             </div> -->
        </div>
<!--         <iframe id="iframeWebRTC" height="540" width="960" allowtransparency="true" frameborder="0" scrolling="no"></iframe> -->
        
        <div class="videoWinContainer" oncontextmenu="return false;">
            <div id="mainVideoContainer" class="two_wayMainVideoContainer">
                <video autoplay id="mainVideo" muted class="localVideo" style="display:none;"></video>
                <div id="mainDisplayName" class="displayname"></div>
            </div>

            <div id="remoteVideosContainer" class="two_wayRemoteVideosContainer">
                <div id="remoteVideos"></div>
            </div>
        </div>
        <div class="main_control_bar">
            <div>
                <div id="localMic" title="mute mic" class="icon mic left10 disabled" onclick="muteLocal(event)"></div>
                <div id="localSpk" title="mute speakers" class="icon spk left10 disabled" onclick="muteRemote(event)"></div>
                <div id="localCam" title="stop video" class="icon cam left10 disabled" onclick="stopLocal(event)"></div>
                <div id="localFilter" title="select filter" class="icon filter left10 disabled" onclick="$('#ddlFilters').show();"></div>
            </div>
            <div class="left100"><div id="disconnect" class="icon disconnect_disabled" onclick="conf.disconnect()"></div></div>
        </div>
        <div id="ddlFilters" class="filters_container" onmouseleave="$('#ddlFilters').hide();">
            <div onclick="changeFilter('filter-grayscale');">Grayscale</div>
            <div onclick="changeFilter('filter-saturate');">Saturate</div>
            <div onclick="changeFilter('filter-invert');">Invert</div>
            <div onclick="changeFilter('filter-sepia');">Sepia</div>
            <div onclick="changeFilter('none');">None</div>
        </div>
    </div>
    


</body>
   
</html>