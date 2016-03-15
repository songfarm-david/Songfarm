<?php
/**
* Redirect to new location
*
* @param (string) a location
* @return NULL
*/
function redirectTo($location) {
	header("Location: " . $location);
	exit;
}

/**
* Auxillary function that constructs an autorespond HTML email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @return (string) an HTML email
*/
function makeAutoresponder($email_data){

	// retrieve email template
	$autorespond_email = file_get_contents(EMAIL_PATH.DS.'email_templates/autoresponder.html');

	// replace keys with data
	$autorespond_email = str_replace('%title%',$email_data['title'],$autorespond_email);
	$autorespond_email = str_replace('%link%',$email_data['link'],$autorespond_email);
	$autorespond_email = str_replace('%logo%',$email_data['logo']['source'],$autorespond_email);
	$autorespond_email = str_replace('%logoWidth%',$email_data['logo']['width'],$autorespond_email);
	$autorespond_email = str_replace('%logoHeight%',$email_data['logo']['height'],$autorespond_email);
	$autorespond_email = str_replace('%header%',$email_data['header'],$autorespond_email);
	$autorespond_email = str_replace('%intro%',$email_data['intro'],$autorespond_email);
	$autorespond_email = str_replace('%body%',$email_data['body'],$autorespond_email);
	$autorespond_email = str_replace('%signature%',$email_data['signature'],$autorespond_email);
	$autorespond_email = str_replace('%year%',$email_data['year'],$autorespond_email);

	// return email
	return $autorespond_email;
}

/**
*	Auxillary function that constructs a songcircle confirmation HTML email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function songcircleConfirmRegistration($email_data,$songcircle_user_data){

	// retrieve advanced email template file
	if( ($email_data['email_type'] == 'confirm_registration') ){
		$email = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_confirm_registration.html');
	}
	elseif( ($email_data['email_type'] == 'registered') ){
		$email = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_registered.html');
	}
	// replace keys with data
	$email = str_replace('%title%',$email_data['title'],$email);
	$email = str_replace('%logo%',$email_data['logo']['source'],$email);
	$email = str_replace('%logoWidth%',$email_data['logo']['width'],$email);
	$email = str_replace('%logoHeight%',$email_data['logo']['height'],$email);
	$email = str_replace('%header%',$email_data['header'],$email);
	$email = str_replace('%greeting%',$email_data['greeting'],$email);
	// if 'intro' key exists
	if(array_key_exists('intro',$email_data)){
		$email = str_replace('%intro%',$email_data['intro'],$email);
	}
	$email = str_replace('%body%',$email_data['body'],$email);
	$email = str_replace('%link%',$email_data['ctaLink']['linkLocation'],$email);
	$email = str_replace('%linkText%',$email_data['ctaLink']['linkText'],$email);
	$email = str_replace('%signature%',$email_data['signature'],$email);
	if(isset($email_data['unregister']) && !empty($email_data['unregister'])){
		$email = str_replace('%unregisterText%',$email_data['unregister']['unregisterText'],$email);
		// $email = str_replace('%unregisterLinkLocation%',$email_data['unregister']['unregisterLinkLocation'],$email);
		// $email = str_replace('%unregisterLinkText%',$email_data['unregister']['unregisterLinkText'],$email);
	}
	$email = str_replace('%directive%',$email_data['directive'],$email);
	$email = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$email);
	$email = str_replace('%year%',$email_data['year'],$email);

	// replace user/event specific keys
	$email = str_replace('%name%',$songcircle_user_data['username'],$email);
	if(array_key_exists('user_email',$songcircle_user_data)){
		$email = str_replace('%email%',$songcircle_user_data['user_email'],$email);
	}
	$email = str_replace('%event%',$songcircle_user_data['event_name'],$email);
	$email = str_replace('%date_time%',$songcircle_user_data['date_time'],$email);
	// if link_params exist
	if(isset($songcircle_user_data['link_params']['confirmation_key'])){
		$email = str_replace('%confirmation_key%',$songcircle_user_data['link_params']['confirmation_key'],$email);
	}
	if(isset($songcircle_user_data['link_params']['songcircle_id'])){
		$email = str_replace('%songcircle_id%',$songcircle_user_data['link_params']['songcircle_id'],$email);
	}
	if(isset($songcircle_user_data['link_params']['user_email'])){
		$email = str_replace('%email%',$songcircle_user_data['link_params']['user_email'],$email);
	}
	if(isset($songcircle_user_data['link_params']['user_id'])){
		$email = str_replace('%user_id%',$songcircle_user_data['link_params']['user_id'],$email);
	}
	// return email
	return $email;
}

/**
*	Auxillary function that constructs a songcircle reminder HTML email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function songcircleFirstReminder($email_data,$songcircle_user_data){
	// get email template
	$reminder_email = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_first_reminder.html');

	// page title
	$reminder_email = str_replace('%title%',$email_data['title'],$reminder_email);
	// logo information
	$reminder_email = str_replace('%logo%',$email_data['logo']['source'],$reminder_email);
	$reminder_email = str_replace('%logoWidth%',$email_data['logo']['width'],$reminder_email);
	$reminder_email = str_replace('%logoHeight%',$email_data['logo']['height'],$reminder_email);
	// main text
	$reminder_email = str_replace('%header%',$email_data['header'],$reminder_email);
	$reminder_email = str_replace('%greeting%',$email_data['greeting'],$reminder_email);
	$reminder_email = str_replace('%intro%',$email_data['intro'],$reminder_email);
	$reminder_email = str_replace('%body%',$email_data['body'],$reminder_email);
	// write out to blog links here:
	$reminder_email = str_replace('%blogLink_1%',$email_data['blogLink_1']['linkText'],$reminder_email);
	$reminder_email = str_replace('%blogLink_2%',$email_data['blogLink_2']['linkText'],$reminder_email);
	// signature
	$reminder_email = str_replace('%signature%',$email_data['signature'],$reminder_email);
	// unregister link
	$reminder_email = str_replace('%unregisterText%',$email_data['unregister']['unregisterText'],$reminder_email);
	// $reminder_email = str_replace('%unregisterLinkLocation%',$email_data['unregisterLink']['unregisterLinkLocation'],$reminder_email);
	// $reminder_email = str_replace('%unregisterLinkText%',$email_data['unregisterLink']['unregisterLinkText'],$reminder_email);
	// directive
	$reminder_email = str_replace('%directive%',$email_data['directive'],$reminder_email);
	$reminder_email = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$reminder_email);
	$reminder_email = str_replace('%year%',$email_data['year'],$reminder_email);

	// user/event specific data
	$reminder_email = str_replace('%songcircle_id%',$songcircle_user_data['songcircle_id'],$reminder_email);
	$reminder_email = str_replace('%event%',$songcircle_user_data['songcircle_name'],$reminder_email);
	$reminder_email = str_replace('%date_time%',$songcircle_user_data['date_of_songcircle'],$reminder_email);

	$reminder_email = str_replace('%user_id%',$songcircle_user_data['user_id'],$reminder_email);
	$reminder_email = str_replace('%name%',$songcircle_user_data['user_name'],$reminder_email);
	$reminder_email = str_replace('%email%',$songcircle_user_data['user_email'],$reminder_email);

	return $reminder_email;
}

/**
* Auxillary function that constructs a join songcircle HTML email
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function songcircleJoinSongcircle($email_data,$songcircle_user_data){
	// get email template
	$join_songcircle = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_join_songcircle.html');
	// page title
	$join_songcircle = str_replace('%title%',$email_data['title'],$join_songcircle);
	// logo information
	$join_songcircle = str_replace('%logo%',$email_data['logo']['source'],$join_songcircle);
	$join_songcircle = str_replace('%logoWidth%',$email_data['logo']['width'],$join_songcircle);
	$join_songcircle = str_replace('%logoHeight%',$email_data['logo']['height'],$join_songcircle);
	// main text
	$join_songcircle = str_replace('%header%',$email_data['header'],$join_songcircle);
	$join_songcircle = str_replace('%greeting%',$email_data['greeting'],$join_songcircle);
	$join_songcircle = str_replace('%intro%',$email_data['intro'],$join_songcircle);
	// cta link
	$join_songcircle = str_replace('%link%',$email_data['ctaLink']['linkLocation'],$join_songcircle);
	$join_songcircle = str_replace('%linkText%',$email_data['ctaLink']['linkText'],$join_songcircle);
	// signature
	$join_songcircle = str_replace('%signature%',$email_data['signature'],$join_songcircle);

	// copyright year
	$join_songcircle = str_replace('%year%',$email_data['year'],$join_songcircle);

	// user/songcircle specific data
	$join_songcircle = str_replace('%event%',$songcircle_user_data['songcircle_name'],$join_songcircle);
	$join_songcircle = str_replace('%songcircle_id%',$songcircle_user_data['songcircle_id'],$join_songcircle);
	$join_songcircle = str_replace('%user_id%',$songcircle_user_data['user_id'],$join_songcircle);
	$join_songcircle = str_replace('%email%',$songcircle_user_data['user_email'],$join_songcircle);
	$join_songcircle = str_replace('%verification_key%',$songcircle_user_data['verification_key'],$join_songcircle);

	// return the email
	return $join_songcircle;
}

/**
*	Auxillary function that constructs a songcircle Waiting List email
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function songcircleWaitlistNotice($email_data,$songcircle_user_data){
	// retrieve waitlist_email template file
	$waitlist_email = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_waitlist_notice.html');
	// replace general variables
	$waitlist_email = str_replace('%title%',$email_data['title'],$waitlist_email);
	$waitlist_email = str_replace('%logo%',$email_data['logo']['source'],$waitlist_email);
	$waitlist_email = str_replace('%logoWidth%',$email_data['logo']['width'],$waitlist_email);
	$waitlist_email = str_replace('%logoHeight%',$email_data['logo']['height'],$waitlist_email);
	$waitlist_email = str_replace('%header%',$email_data['header'],$waitlist_email);
	$waitlist_email = str_replace('%greeting%',$email_data['greeting'],$waitlist_email);
	$waitlist_email = str_replace('%intro%',$email_data['intro'],$waitlist_email);
	$waitlist_email = str_replace('%body%',$email_data['body'],$waitlist_email);
	$waitlist_email = str_replace('%link%',$email_data['ctaLink']['linkLocation'],$waitlist_email);
	$waitlist_email = str_replace('%linkText%',$email_data['ctaLink']['linkText'],$waitlist_email);
	$waitlist_email = str_replace('%postScript%',$email_data['post_script'],$waitlist_email);
	$waitlist_email = str_replace('%signature%',$email_data['signature'],$waitlist_email);
	$waitlist_email = str_replace('%directive%',$email_data['directive'],$waitlist_email);
	$waitlist_email = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$waitlist_email);
	$waitlist_email = str_replace('%year%',$email_data['year'],$waitlist_email);
	// replace user/event specific variables
	$waitlist_email = str_replace('%name%',$songcircle_user_data['username'],$waitlist_email);
	$waitlist_email = str_replace('%event%',$songcircle_user_data['songcircle_name'],$waitlist_email);
	$waitlist_email = str_replace('%date_time%',$songcircle_user_data['date_of_songcircle'],$waitlist_email);
	$waitlist_email = str_replace('%songcircle_id%',$songcircle_user_data['songcircle_id'],$waitlist_email);
	$waitlist_email = str_replace('%user_id%',$songcircle_user_data['user_id'],$waitlist_email);
	$waitlist_email = str_replace('%email%',$songcircle_user_data['user_email'],$waitlist_email);
	$waitlist_email = str_replace('%confirmation_key%',$songcircle_user_data['confirmation_key'],$waitlist_email);
	// return email
	return $waitlist_email;
}

/**
* Constructs HTML email by parsing email data and calling an auxillary construct function
*
* Created: 01/25/2016
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function constructHTMLEmail($email_data, $songcircle_user_data=''){
	// get first dimension of array keys
	foreach($email_data as $email_flag){
		// if email_flag matches..
		switch ($email_flag) {
			/**
			* Need to make these names more intuitive
			*/
			case 'autorespond':
				return makeAutoresponder($email_data);
				break;
			case 'confirm_registration':
				return songcircleConfirmRegistration($email_data, $songcircle_user_data);
				break;
			case 'registered':
				return songcircleConfirmRegistration($email_data, $songcircle_user_data);
				break;
			case 'first_reminder':
				return songcircleFirstReminder($email_data, $songcircle_user_data);
				break;
			case 'join_songcircle':
				return songcircleJoinSongcircle($email_data, $songcircle_user_data);
				break;
			case 'waitlist':
				return songcircleWaitlistNotice($email_data, $songcircle_user_data);
				break;
			default:
				// write to log
				file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").' No cases matched -- '.$_SERVER['PHP_SELF'].' ('.__LINE__.')'.PHP_EOL,FILE_APPEND);
				break;
		} // end of switch
	} // end of foreach
}

/**
*	Function - Attempt to validate
* user IP address.
*
* Use IP address to collect user's
* Country Code and Country Name
*
* @return array user's country code, country name
*
*/
function generateIPData(){
	// if the Server detects an IP address, set it to $user_ip variable
	if(isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){

		$user_ip = $_SERVER['REMOTE_ADDR'];
		/* Test IPs */
		// localhost equates to ::1 no location information
		// $user_ip = ""; // equates to nothing, outputs NL Netherlands
		// $user_ip = '181.196.204.134'; // IP for Ecuador
		// $user_ip = '192.206.151.131'; // IP for Toronto
		$user_ip = '2605:e000:fa83:6c00:d143:745:8b73:2daa'; // California IP

	} else {
		// if results here, could not detect IP address - Should do something
		$user_ip = " ";
	}

	// get contents of the IP address
	if(	$ip_contents = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip))){
		$ip_data = [];
		// loop through contents and extract certain values
		foreach ($ip_contents as $key => $value) {
			$key = substr($key, 10); // remove 'geoplugin' part of key..
			if( // if there are matches for any of these conditions, place into $ip_data[]
			$key == 'countryCode' || 	$key == 'countryName'	|| $key == 'city' || $key == 'continentCode')	{
				$ip_data[$key] = $value;
			}
		} // end of foreach loop

		$location_data = [];
		// create variables to contain ip keys

		if(isset($ip_data['countryCode'])){
			$location_data['country_code'] = strtoupper($ip_data['countryCode']); // make sure country code is always uppercase
		}
		if(isset($ip_data['countryName'])){
			$location_data['country_name'] = ucfirst($ip_data['countryName']); // first letter is always upper case
		}
		if(isset($ip_data['city'])){
			$location_data['city_name'] = ucfirst($ip_data['city']); // first letter is always upper case
		}
		if(isset($ip_data['continentCode'])){
			$location_data['continent_code'] = strtoupper($ip_data['continentCode']); // make sure continent code is always uppercase
		}

		return $location_data;
	}

}

/**
*	Formats and display missing values received by Registration Form
*
* @param (array) array of values
* @return (string) formatted string
*/
function displayMissingValues($array){
	$output = '<p>The following required values were missing:</p>';
	$output.= '<ul>';
	foreach ($array as $value) {
		$output.= '<li><b>'.ucfirst($value).'</b></li>';
	}
	$output.= '</ul>';
	$output.= '<p>Exiting from validation..</p>';
	return $output;
}

/**
* Credit: Scott - http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
*/
function crypto_rand_secure($min, $max){
  $range = $max - $min;
  if ($range < 1) return $min; // not so random...
  $log = ceil(log($range, 2));
  $bytes = (int) ($log / 8) + 1; // length in bytes
  $bits = (int) $log + 1; // length in bits
  $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
  do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; // discard irrelevant bits
  } while ($rnd >= $range);
  return $min + $rnd;
}

/**
* Credit: Scott - http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
*/
function getToken($length){

  $securityToken = "";

  $codeSource = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeSource.= "abcdefghijklmnopqrstuvwxyz";
  $codeSource.= "012345678901234567890123456789";
	// assign token farm length to $max
  $max = strlen($codeSource) - 1;

  for ($i=0; $i < $length; $i++) {
      $securityToken.= $codeSource[crypto_rand_secure(0, $max)];
  }

  return $securityToken;
}

?>
