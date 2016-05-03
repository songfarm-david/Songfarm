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
	"contact_us" => [
		"email_type" => "contact_us",
		"title" => "Message received!",
		"link" => "http://test.songfarm.ca/email/sandbox/contactConfirmationEmail.html",
		"logo" => [
			"source" => "http://songfarm.ca/public/images/emails/logo_email_s.png",
			"width" => "158",
			"height" => "48"
		],
		"header" => "Message received!",
		"intro" => "Thanks for getting in touch, %name%!",
		"body" => "We are reviewing your comment and will get back to you shortly.",
		"signature" => "Thank you,<br />
		The Songfarm Team",
		"year" => date("Y")
	],
	"support" => [
		"email_type" => "autorespond",
		"title" => "Support Request",
		"link" => "http://test.songfarm.ca/email/supportConfirmationEmail.html",
		"logo" => [
			"source" => "http://songfarm.ca/public/images/emails/logo_email_s.png",
			"width" => "158",
			"height" => "48"
		],
		"header" => "We're sorry you're experiencing problems! :(",
		"intro" => "At Songfarm, we strive to make your experience pleasant and enjoyable, so we're sorry you're having a hard time!",
		"body" => "You're receiving this email to notify you that we have received your support request and will reply to you within the next 24 hours.",
		"signature" => "Thank you for your patience and understanding,<br />
		The Songfarm Team",
		"year" => date("Y")
	],
	"confirm_registration" => [
		"email_type" => "confirm_registration",
		"title" => "Songcircle Registration Confirmation",
		"logo" => [ // may user alternate logo
			"source" => "http://songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "Almost there!", // Almost there.. // Just one more thing..
		"greeting" => "Hi %username%,",
		"intro" => "You are almost done registering for <b>%songcircle_name%</b> on <b>%date_of_songcircle%</b>.",
		"body" => "Please confirm your registration by clicking the link below:",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/includes/songcircle_user_action.php?action=confirm_registration&songcircle_id=%songcircle_id%&user_email=%user_email%&username=%username%&confirmation_key=%confirmation_key%&waitlist=false",
			"linkText" => "Confirm Registration"
		],
		"signature" => "Thank you!<br />
		The Songfarm Team",
		"disclaimer" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_key=%user_key%"
		],
		"year" => date("Y")
	],
	"registered" => [
		"email_type" => "registered",
		"title" => "Registration Confirmed!",
		"logo" => [
			"source" => "http://songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "You're Confirmed!",
		"greeting" => "That's it, %username%!",
		"intro" => "You are confirmed for <b>%songcircle_name%</b> on <b>%date_of_songcircle%</b>.",
		"body" => "<b>In order to participate</b>, please be sure to use one of the platforms listed below:",
		"linkIntro" => "Want to get the most out of <b>%songcircle_name%</b>? Check out these articles:",
		"blogLink" => [
			"linkLocation_1" => "http://blog.songfarm.ca/blog_article_1.html",
			"linkText_1" => "blog_article_1",
			"linkLocation_2" => "http://blog.songfarm.ca/blog_article_2.html",
			"linkText_2" => "blog_article_2",
		],
		"signature" => "Take care! <br />
		The Songfarm team",
		"unregister" => [
			"unregisterText" => "If you will be unable to attend this event, please unregister by <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=unregister&songcircle_id=%songcircle_id%&user_id=%user_id%&waitlist=false\">clicking here</a>."
		],
		"disclaimer" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_key=%user_key%"
		],
		"year" => date("Y")
	],
	"first_reminder" => [
		"email_type" => "first_reminder",
		"title" => "%songcircle_name% is happening soon!",
		"logo" => [ // may user alternate logo
			"source" => "http://songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "We are only a couple days away from %songcircle_name%",
		"greeting" => "Hey %username%,",
		"intro" => "This is a reminder that you are confirmed to participate in <b>%songcircle_name%</b> on <b>%date_of_songcircle%</b>.",
		"body" => "Please remember that to participate you will need to be using...",
		"linkIntro" => "Here's an article we think you'll like:",
		"blogLink" => [
			"linkLocation_1" => "http://blog.songfarm.ca/blog_article_1.html",
			"linkText_1" => "blog_article_1",
			// "linkLocation_2" => "http://blog.songfarm.ca/blog_article_2.html",
			// "linkText_2" => "blog_article_2",
		],
		"signature" => "Looking forward to hearing from you!<br />
		The Songfarm Team",
		"unregister" => [
			"unregisterText" => "If you will be unable to attend this event, please unregister by <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=unregister&songcircle_id=%songcircle_id%&user_id=%user_id%&waitlist=false\">clicking here</a>."
		],
		"disclaimer" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_key=%user_key%"
		],
		"year" => date("Y")
	],
	"join_songcircle" => [
		"email_type" => "join_songcircle",
		"title" => "Join %songcircle_name% now!",
		"logo" => [ // may user alternate logo
			"source" => "http://songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "%songcircle_name%<br /> is starting!",
		"greeting" => "<b>%songcircle_name%</b> is starting now!",
		"intro" => "Click the link below to be connected now:",
		"ctaLink" => [
			"linkLocation" => "http://songfarm.ca/public/songcircle_user_action.php?action=join_songcircle&songcircle_id=%songcircle_id%&user_id=%user_id%&verification_key=%verification_key%",
			"linkText" => "Join Now!"
		],
		"signature" => "Break a leg!<br />
		The Songfarm Team",
		"disclaimer" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_key=%user_key%"
		],
		"year" => date("Y")
	],
	"waitlist" => [
		"email_type" => "waitlist",
		"title" => "A spot has opened up for %songcircle_name%!",
		"logo" => [
			"source" => "http://songfarm.ca/public/images/emails/logo_email_l.png",
			"width" => "293",
			"height" => "194"
		],
		"header" => "A spot has opened up!",
		"greeting" => "Hey %username%!",
		"intro" => "A spot has opened up for <b>%songcircle_name%</b> on <b>%date_of_songcircle%</b>.",
		"body" => "Please click the link below to secure your spot:",
		"ctaLink" => [
			"linkLocation" => "http://test.songfarm.ca/includes/songcircle_user_action.php?action=confirm_registration&songcircle_id=%songcircle_id%&user_email=%user_email%&username=%username%&confirmation_key=%confirmation_key%&waitlist=true",
			"linkText" => "Secure my spot!"

		],
		"postScript" => "If you no longer wish to participate, please <a href=\"http://test.songfarm.ca/includes/songcircle_user_action.php?action=unregister&songcircle_id=%songcircle_id%&user_id=%user_id%&waitlist=true\">click here</a> to be removed from the waitlist.",
		"signature" => "Thanks!<br />
		The Songfarm Team",
		"disclaimer" => "You are receiving this email because you registered for a Songcircle on <a href=\"http://songfarm.ca\" style=\"text-decoration: none; color: #153643;\"><font color=\"#153643\">songfarm.ca</font></a><br />
		If you received this email in error or do not wish to receive any further communications from Songfarm, please ",
		"unsubscribeLink" => [
			"unsubscribeLinkLocation" => "http://test.songfarm.ca/includes/unsubscribe.php?user_key=%user_key%"
		],
		"year" => date("Y")
	]
];
// end of data


?>
