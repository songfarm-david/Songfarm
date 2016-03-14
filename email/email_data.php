<?php
/**
* Email Data Arrays --
*
* NOTE: "email_type" is used as a key in constructHTMLEmail() function (functions.php)
* NOTE: Include WebRTC note in select emails (which??)
* NOTE: waitlist_notice is commented out as currently unused
*
*/
/**
*	Can I use a $_SERVER constant instead of typing in http://www.songfarm.ca etc.. ???
*/

$email_data = [
	"contact" => [
		"email_type" => "autorespond",
		"title" => "Message received!",
		"link" => "http://test.songfarm.ca/email/contactConfirmationEmail.html",
		"logo" => [
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_s.png",
			"width" => "158",
			"height" => "48"
		],
		"header" => "Message received!",
		"intro" => "Thanks for taking the time to get in touch!",
		"body" => "We will view your message and may get back to you within the next 24 hours.",
		"signature" => "Thank you,<br />
		The Songfarm Team",
		"year" => date("Y")
	],
	"support" => [
		"email_type" => "autorespond",
		"title" => "Support Request",
		"link" => "http://test.songfarm.ca/email/supportConfirmationEmail.html",
		"logo" => [
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_s.png",
			"width" => "158",
			"height" => "48"
		],
		"header" => "We're sorry you're experiencing problems! :(",
		"intro" => "At Songfarm, we strive to make your experience pleasant and enjoyable, so we're sorry you're having a hard time.",
		"body" => "You're receiving this email to notify you that we have received your support request and will reply to you within the next 24 hours.",
		"signature" => "Thank you for your patience and understanding,<br />
		The Songfarm Team",
		"year" => date("Y")
	],
	"confirm_registration" => [
		"email_type" => "confirm_registration",
		"title" => "Songcircle Registration Confirmation",
		"logo" => [ // may user alternate logo
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "You're almost there..", // Almost there.. // Just one more thing..
		"greeting" => "Hi %name%,",
		"intro" => "You are almost done registering for <b>%event%</b> on <b>%date_time%</b>.",
		"body" => "Please confirm your registration by clicking the link below:",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/includes/songcircle_user_action.php?action=confirm_registration&waitlist=false&songcircle_id=%songcircle_id%&user_email=%email%&confirmation_key=%confirmation_key%&username=%name%",
			"linkText" => "Confirm Registration"
		],
		"signature" => "Thank you!<br />
		The Songfarm Team",
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	],
	"registered" => [
		"email_type" => "registered",
		"title" => "Registration Confirmed!",
		"logo" => [
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "You're Confirmed!",
		"greeting" => "That's it, %name%!",
		"intro" => "You are now confirmed for <b>%event%</b> on <b>%date_time%</b>",
		"body" => "Here's an article we think you'll like. It's all about getting the most out of your microphone, camera and environment so that you will look and sound your best: <b>Also, make sure to add this event to your calendar...</b>",
		"ctaLink" => [
			"linkLocation" => "http://blog.songfarm.ca/songcircle_tips.html",
			"linkText" => "Get the most out of your webcam equipment"
		],
		"signature" => "Speak to you soon! <br />
		The Songfarm team",
		"unregister" => [
			"unregisterText" => "If you will be unable to attend this event, please unregister by <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=unregister&songcircle_id=%songcircle_id%&user_id=%user_id%\">clicking here</a>."
		],
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	],
	"first_reminder" => [
		"email_type" => "first_reminder",
		"title" => "%event% is happening soon!",
		"logo" => [ // may user alternate logo
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "We are only a few days away from %event%",
		"greeting" => "Hey %name%,",
		"intro" => "This is a reminder that you are confirmed to participate in <b>%event%</b> on <b>%date_time%</b>.",
		"body" => "So that everybody is on the same page, we took the liberty of writing a short post on what you can expect and what's expected of you when <b>%event%</b> takes place.",
		"blogLink_1" => [
			"linkLocation" => "http://blog.songfarm.ca/songcircle_format.html",
			"linkText" => "blogLink_1"
		],
		"blogLink_2" => [
			"linkLocation" => "http://blog.songfarm.ca/songcircle_format.html",
			"linkText" => "blogLink_2"
		],
		"signature" => "Looking forward to hearing from you!<br />
		The Songfarm Team",
		"unregister" => [
			"unregisterText" => "If you will be unable to attend this event, please unregister by <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=unregister&songcircle_id=%songcircle_id%&user_id=%user_id%\">clicking here</a>."
		],
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	],
	"join_songcircle" => [
		"email_type" => "join_songcircle",
		"title" => "Join %event% now!",
		"logo" => [ // may user alternate logo
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "%event%<br /> is starting!",
		"greeting" => "Here we go!",
		"intro" => "Click the link below now to be connected live to %event%.",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/public/start_call.php?action=join_songcircle&songcircle_id=%songcircle_id%&user_id=%user_id%",
			"linkText" => "Join Now!"
		],
		"signature" => "Break a leg!<br />
		The Songfarm Team",
		// "directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		// If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		// "unsubscribeLink" => [
		// 	"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		// ],
		"year" => date("Y")
	],
	// "waitlist_confirmation" => [
	// 	"email_type" => "waitlist",
	// 	"title" => "You're on the Waiting List",
	// 	"logo" => [ // may user alternate logo
	// 		"source" => "http://test.songfarm.ca/logo_email_l.png",
	// 		"width" => "293",
	// 		"height" => "194"
	// 	],
	// 	"header" => "You're on the Waiting List!",
	// 	"greeting" => "Hi %name%,",
	// 	"intro" => "You've been added to the waiting list for <b>%event%</b> on %date_time%.",
	// 	"body" => "We will notify you if a spot opens up for this event.",
	// 	"signature" => "Thanks!<br />
	// 	The Songfarm Team",
	// 	"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
	// 	If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
	// 	"unsubscribeLink" => [
	// 		"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
	// 	],
	// 	"year" => date("Y")
	// ],
	"waitlist" => [
		"email_type" => "waitlist",
		"title" => "A spot has opened up for %event%!",
		"logo" => [ // may user alternate logo
			"source" => "http://test.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "A spot has opened up!",
		"greeting" => "Hey %name%!",
		"intro" => "A spot has opened up for <b>%event%</b> on <b>%date_time%</b>.",
		"body" => "Please click the link below now to confirm your attendence:",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/includes/songcircle_user_action.php?action=confirm_registration&waitlist=true&songcircle_id=%songcircle_id%&user_email=%email%&confirmation_key=%confirmation_key%&username=%name%", // notice parameters here. Will need to adjust
			"linkText" => "Confirm Now!"
		],
		"post_script" => "If you no longer wish to participate, please <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=waitlist_unregister&songcircle_id=%songcircle_id%&user_id=%user_id%\">click here</a> to be removed from the waitlist.",
		"signature" => "Thanks!<br />
		The Songfarm Team",
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	]
];
// end of data


?>
