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
* Created: 25/01/2016
*
* @param (array) email data
* @return (string) an HTML email
*/
function autorespondEmail($email_data){

	// retrieve email template
	$basic_email_template = file_get_contents(EMAIL_PATH.DS.'basicTemplate.html');

	// replace keys with data
	$basic_email_template = str_replace('%title%',$email_data['title'],$basic_email_template);
	$basic_email_template = str_replace('%link%',$email_data['link'],$basic_email_template);
	$basic_email_template = str_replace('%logo%',$email_data['logo']['source'],$basic_email_template);
	$basic_email_template = str_replace('%logoWidth%',$email_data['logo']['width'],$basic_email_template);
	$basic_email_template = str_replace('%logoHeight%',$email_data['logo']['height'],$basic_email_template);
	$basic_email_template = str_replace('%header%',$email_data['header'],$basic_email_template);
	$basic_email_template = str_replace('%intro%',$email_data['intro'],$basic_email_template);
	$basic_email_template = str_replace('%body%',$email_data['body'],$basic_email_template);
	$basic_email_template = str_replace('%signature%',$email_data['signature'],$basic_email_template);
	$basic_email_template = str_replace('%year%',$email_data['year'],$basic_email_template);

	// return email
	echo $basic_email_template;
}

/**
*	Auxillary function that constructs a songcircle confirmation HTML email
*
* Created: 25/01/2016
*
* @param (array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function confirmationEmail($email_data,$songcircle_user_data){

	// retrieve advanced email template
	$advanced_email_template = file_get_contents(EMAIL_PATH.DS.'advancedTemplate.html');

	// replace keys with data
	$advanced_email_template = str_replace('%title%',$email_data['title'],$advanced_email_template);
	$advanced_email_template = str_replace('%logo%',$email_data['logo']['source'],$advanced_email_template);
	$advanced_email_template = str_replace('%logoWidth%',$email_data['logo']['width'],$advanced_email_template);
	$advanced_email_template = str_replace('%logoHeight%',$email_data['logo']['height'],$advanced_email_template);
	$advanced_email_template = str_replace('%header%',$email_data['header'],$advanced_email_template);
	$advanced_email_template = str_replace('%greeting%',$email_data['greeting'],$advanced_email_template);
	$advanced_email_template = str_replace('%intro%',$email_data['intro'],$advanced_email_template);
	$advanced_email_template = str_replace('%body%',$email_data['body'],$advanced_email_template);
	$advanced_email_template = str_replace('%link%',$email_data['ctaLink']['linkLocation'],$advanced_email_template);
	$advanced_email_template = str_replace('%linkText%',$email_data['ctaLink']['linkText'],$advanced_email_template);
	$advanced_email_template = str_replace('%signature%',$email_data['signature'],$advanced_email_template);
	$advanced_email_template = str_replace('%directive%',$email_data['directive'],$advanced_email_template);
	$advanced_email_template = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$advanced_email_template);

	$advanced_email_template = str_replace('%year%',$email_data['year'],$advanced_email_template);

	// replace user/event specific keys
	$advanced_email_template = str_replace('%name%',$songcircle_user_data['username'],$advanced_email_template);
	$advanced_email_template = str_replace('%event%',$songcircle_user_data['eventTitle'],$advanced_email_template);
	$advanced_email_template = str_replace('%date_time%',$songcircle_user_data['date_time'],$advanced_email_template);
	$advanced_email_template = str_replace('%conference_id%',$songcircle_user_data['linkParams']['conference_id'],$advanced_email_template);
	$advanced_email_template = str_replace('%email%',$songcircle_user_data['linkParams']['user_email'],$advanced_email_template);
	$advanced_email_template = str_replace('%confirmation_key%',$songcircle_user_data['linkParams']['confirmation_key'],$advanced_email_template);

	// return email
	return $advanced_email_template;
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
			case 'autoresponder':
				return autorespondEmail($email_data);
				break;
			case 'songcircle_confirmation':
				return confirmationEmail($email_data, $songcircle_user_data);
				break;
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
