<?php
/**
* Redirect to new location
*
* @param (string) a location
* @return NULL
*/
function redirect_to($location) {
	header("Location: " . $location);
	exit;
}

/**
* Auxillary function that constructs an autorespond HTML email
*
*		Class 1 email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @return (string) an HTML email
*/
function autorespondEmail($email_data){

	// retrieve email template
	$autorespond_email = file_get_contents(EMAIL_PATH.DS.'autorespondTemplate.html');

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
*		Class 2 email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function confirmationEmail($email_data,$songcircle_user_data){

	// retrieve advanced email template
	$registration_email = file_get_contents(EMAIL_PATH.DS.'registrationTemplate.html');

	// replace keys with data
	$registration_email = str_replace('%title%',$email_data['title'],$registration_email);
	$registration_email = str_replace('%logo%',$email_data['logo']['source'],$registration_email);
	$registration_email = str_replace('%logoWidth%',$email_data['logo']['width'],$registration_email);
	$registration_email = str_replace('%logoHeight%',$email_data['logo']['height'],$registration_email);
	$registration_email = str_replace('%header%',$email_data['header'],$registration_email);
	$registration_email = str_replace('%greeting%',$email_data['greeting'],$registration_email);
	$registration_email = str_replace('%intro%',$email_data['intro'],$registration_email);
	$registration_email = str_replace('%body%',$email_data['body'],$registration_email);
	$registration_email = str_replace('%link%',$email_data['ctaLink']['linkLocation'],$registration_email);
	$registration_email = str_replace('%linkText%',$email_data['ctaLink']['linkText'],$registration_email);
	$registration_email = str_replace('%signature%',$email_data['signature'],$registration_email);
	$registration_email = str_replace('%directive%',$email_data['directive'],$registration_email);
	$registration_email = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$registration_email);
	$registration_email = str_replace('%year%',$email_data['year'],$registration_email);

	// replace user/event specific keys
	$registration_email = str_replace('%name%',$songcircle_user_data['username'],$registration_email);
	$registration_email = str_replace('%event%',$songcircle_user_data['eventTitle'],$registration_email);
	$registration_email = str_replace('%date_time%',$songcircle_user_data['date_time'],$registration_email);
	$registration_email = str_replace('%conference_id%',$songcircle_user_data['linkParams']['conference_id'],$registration_email);
	$registration_email = str_replace('%email%',$songcircle_user_data['linkParams']['user_email'],$registration_email);
	$registration_email = str_replace('%confirmation_key%',$songcircle_user_data['linkParams']['confirmation_key'],$registration_email);

	// return email
	return $registration_email;
}

/**
*	Auxillary function that constructs a songcircle Waiting List email
*
*		Class 2 email
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function waitingListEmail($email_data,$songcircle_user_data){
	// retrieve wait list email template
	$wait_list_email = file_get_contents(EMAIL_PATH.DS.'wait_listTemplate.html');

	$wait_list_email = str_replace('%title%',$email_data['title'],$wait_list_email);
	$wait_list_email = str_replace('%logo%',$email_data['logo']['source'],$wait_list_email);
	$wait_list_email = str_replace('%logoWidth%',$email_data['logo']['width'],$wait_list_email);
	$wait_list_email = str_replace('%logoHeight%',$email_data['logo']['height'],$wait_list_email);
	$wait_list_email = str_replace('%header%',$email_data['header'],$wait_list_email);
	$wait_list_email = str_replace('%greeting%',$email_data['greeting'],$wait_list_email);
	$wait_list_email = str_replace('%intro%',$email_data['intro'],$wait_list_email);
	$wait_list_email = str_replace('%body%',$email_data['body'],$wait_list_email);
	$wait_list_email = str_replace('%signature%',$email_data['signature'],$wait_list_email);
	$wait_list_email = str_replace('%directive%',$email_data['directive'],$wait_list_email);
	$wait_list_email = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$wait_list_email);
	$wait_list_email = str_replace('%year%',$email_data['year'],$wait_list_email);

	// replace user/event specific keys
	$wait_list_email = str_replace('%name%',$songcircle_user_data['username'],$wait_list_email);
	$wait_list_email = str_replace('%event%',$songcircle_user_data['eventTitle'],$wait_list_email);
	$wait_list_email = str_replace('%date_time%',$songcircle_user_data['date_time'],$wait_list_email);

	return $wait_list_email;
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
			case 'class_1':
				return autorespondEmail($email_data);
				break;
			case 'class_2':
				return confirmationEmail($email_data, $songcircle_user_data);
				break;
			case 'class_3':
				return waitingListEmail($email_data, $songcircle_user_data);
			default:
				return false;
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
function generate_ip_data(){
	// if the Server detects an IP address, set it to $user_ip variable
	if(isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){

		$user_ip = $_SERVER['REMOTE_ADDR'];
		/* Test IPs */
		// localhost equates to ::1 no location information
		// $user_ip = ""; // equates to nothing, outputs NL Netherlands
		// $user_ip = '181.196.204.134'; // IP for Ecuador
		// $user_ip = '192.206.151.131'; // IP for Toronto
		// $user_ip = '2605:e000:fa83:6c00:d143:745:8b73:2daa'; // California IP

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
