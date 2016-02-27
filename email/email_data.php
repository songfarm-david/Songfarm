<?php
/**
* 	Email Types:
* 'class_1' -- used for auto responders
* 'class_2' -- songcircle registration
* 'class_3' -- waiting list
*/
// email arrays
$email_data = [
	"contact" => [
		"email_type" => "autorespond",
		"title" => "Message received!",
		"link" => "http://www.songfarm.ca/email/contactConfirmationEmail.html",
		"logo" => [
			"source" => "http://www.songfarm.ca/public/images/emails/logo_email_s.png",
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
		"link" => "http://www.songfarm.ca/email/supportConfirmationEmail.html",
		"logo" => [
			"source" => "http://www.songfarm.ca/public/images/emails/logo_email_s.png",
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
			"source" => "http://www.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "You're almost there..", // Almost there.. // Just one more thing..
		"greeting" => "Hi %name%,",
		"intro" => "You are almost done registering for <b>%event%</b> on <b>%date_time%</b>.",
		"body" => "Please confirm your registration by clicking the link below:",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/includes/songcircleConfirmUser.php?conference_id=%conference_id%&user_email=%email%&confirmation_key=%confirmation_key%&username=%name%",
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
	"registration" => [
		"email_type" => "registration",
		"title" => "Registration Confirmed!",
		"logo" => [
			"source" => "http://www.songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "That's it!",
		"greeting" => "You're confirmed, %name%!",
		"intro" => "You're confirmed for <b>%event%</b> on <b>%date_time%</b>",
		"body" => "Here's an article we think you'll like. It's all about getting the most out of your microphone, camera and environment so that you will look and sound your best:",
		"ctaLink" => [
			"linkLocation" => "http://blog.songfarm.ca/songcircle_tips.html",
			"linkText" => "Get the most out of your webcam equipment"
		],
		"signature" => "Speak to you soon! <br />
		The Songfarm team",
		"unregister" => [
			"unregisterText" => "<i>If you will be unable to attend this event, please unregister by clicking the following link:</i><br />",
			"unregisterLinkLocation" => "",
			"unregisterLinkText" => "Unregister Link Here"
		],
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	],
	// "reminder" => [
	// 	"email_type" => "class_2",
	// 	"title" => "%event% is happening soon!",
	// 	"logo" => [ // may user alternate logo
	// 		"source" => "http://www.songfarm.ca/public/images/emails/logo_email_l.png",
	// 		"width" => "293",
	// 		"height" => "194"
	// 	],
	// 	"header" => "We are only a few days away from %event%",
	// 	"greeting" => "Hey %name%,",
	// 	"intro" => "This is just a gentle remind that you are confirmed to participate for <b>%event%</b> on <b>%date_time%</b>.",
	// 	"body" => "So that everybody is on the same page, we suggest you take a look at the following article on how this whole thing is supposed to go down.",
	// 	"ctaLink" => [
	// 		"linkLocation" => "http://blog.songfarm.ca/songcircle_format.html",
	// 		"linkText" => "Songcircle_Format"
	// 	],
	// 	"signature" => "Looking forward to hearing from you!<br />
	// 	The Songfarm Team",
	// 	"unregister" => "If you will be unable to attend this event for any reason, please unregister by clicking the link below.",
	// 	"unregisterLink" => [
	// 		"unregisterLinkLocation" => "",
	// 		"unregisterLinkText" => ""
	// 	],
	// 	"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
	// 	If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
	// 	"unsubscribeLink" => [
	// 		"unsubscribeLinkLocation" => "http://www.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
	// 	],
	// 	"year" => date("Y")
	// ],
	// "join" => [
	// 	"email_type" => "class_2",
	// 	"title" => "Join %event% now!",
	// 	"logo" => [ // may user alternate logo
	// 		"source" => "http://www.songfarm.ca/public/images/emails/logo_email_l.png",
	// 		"width" => "293",
	// 		"height" => "194"
	// 	],
	// 	"header" => "%event% is starting!",
	// 	// "greeting" => "Hey %name%,",
	// 	"intro" => "Here we go! Click the link below to be connected to %event% now.",
	// 	// "body" => "Be sure to check out this article on how get your camera, microphone and environment at it's best so you can be confident that everything will go as smooth as possible when you play your song:",
	// 	"ctaLink" => [
	// 		"linkLocation" => "join link",
	// 		"linkText" => "Join Now!"
	// 	],
	// 	"signature" => "Have fun!<br />
	// 	The Songfarm Team",
	// 	// "unregister" = > "If you will be unable to attend this event for any reason, please unregister by clicking the link below.",
	// 	// "unregisterLink" => [
	// 	// 	"unregisterLinkLocation" => "",
	// 	// 	"unregisterLinkText" => ""
	// 	// ],
	// 	"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
	// 	If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
	// 	"unsubscribeLink" => [
	// 		"unsubscribeLinkLocation" => "http://www.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
	// 	],
	// 	"year" => date("Y")
	// ],
	"waiting_list" => [
		"email_type" => "waitlist",
		"title" => "You're on the Waiting List",
		"logo" => [ // may user alternate logo
			"source" => "http://www.songfarm.ca/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "You're on the Waiting List!",
		"greeting" => "Hi %name%,",
		"intro" => "You've been added to the waiting list for <b>%event%</b> on %date_time%.",
		"body" => "We will notify you if a spot opens up for this event.",
		"signature" => "Thanks!<br />
		The Songfarm Team",
		"directive" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://www.songfarm.ca/includes/unsubscribe.php?user_email=%email%"
		],
		"year" => date("Y")
	]
];
// end of data


?>
