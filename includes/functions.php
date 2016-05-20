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
* Constructs HTML email by parsing email data and calling an auxillary construct function
*
* Created: 01/25/2016
*
* @param (multi-dimensional array) email data
* @param (array) songcircle/user specific data
* @return (string) an HTML email
*/
function initiateEmail($email_data, $user_data=''){

	// get first dimension of array keys
	foreach($email_data as $email_type){
		// if email_flag matches..
		switch ($email_type) {

			case 'confirm_registration':
				$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_confirm_registration.html');
				break;
			case 'registered':
				$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_registered.html');
				break;
			case 'first_reminder':
				$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_first_reminder.html');
				break;
			case 'join_songcircle':
				$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_join_songcircle.html');
				break;
			// case 'confirm_waitlist':
			// 	$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_confirm_waitlist.html');
			// 	break;
			case 'waitlist':
				$email_template = file_get_contents(EMAIL_PATH.DS.'email_templates/songcircle_waitlist_notice.html');
				break;
			case 'contact_us':
				return makeAutoresponder($email_data, $user_data);
				break;
			default:
				// write to log,
				file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").' Error -- Function initiateEmail called. No cases matched -- '.$_SERVER['PHP_SELF'].' ('.__LINE__.')'.PHP_EOL,FILE_APPEND);
				break;

		} // end of switch

		return constructHTMLEmail($email_data, $user_data, $email_template);

	} // end of foreach

}

/**
* Takes all the elements of an email and puts it together
*
* @param (object) a set of arrays to be used inside a template
* @param (array) user/songcircle data
* @param (object) a file -- email template
*/
function constructHTMLEmail($email_data, $user_data, $email_template){

	// compile all user specific data into a single array
	$compiled_user_data = compileArrays($user_data);

	// title of email
	$email_template = str_replace('%title%',$email_data['title'],$email_template);

	// logo specs
	$email_template = str_replace('%logo%',$email_data['logo']['source'],$email_template);
	$email_template = str_replace('%logoWidth%',$email_data['logo']['width'],$email_template);
	$email_template = str_replace('%logoHeight%',$email_data['logo']['height'],$email_template);

	// main header
	$email_template = str_replace('%header%',$email_data['header'],$email_template);

	/**
	* main script
	*/
	$email_template = str_replace('%greeting%',$email_data['greeting'],$email_template);
	// if intro
	if( array_key_exists('intro',$email_data) )
	{
		$email_template = str_replace('%intro%',$email_data['intro'],$email_template);
	}
	// if body
	if( array_key_exists('body',$email_data) )
	{
		$email_template = str_replace('%body%',$email_data['body'],$email_template);
	}
	$email_template = str_replace('%signature%',$email_data['signature'],$email_template);

	// disclaimer
	$email_template = str_replace('%disclaimer%',$email_data['disclaimer'],$email_template);
	// copyright year
	$email_template = str_replace('%year%',$email_data['year'],$email_template);

	// if call-to-action
	if( array_key_exists('ctaLink',$email_data) )
	{
		$email_template = str_replace('%linkLocation%', $email_data['ctaLink']['linkLocation'], $email_template);
		$email_template = str_replace('%linkText%', $email_data['ctaLink']['linkText'], $email_template);
	}

	// if link to unregister
	if( array_key_exists('unregister',$email_data) )
	{
		$email_template = str_replace('%unregisterText%',$email_data['unregister']['unregisterText'],$email_template);
	}

	// if blog links
	if( array_key_exists('linkIntro',$email_data) ) //isset($email_data['linkIntro']
	{
		$email_template = str_replace('%linkIntro%',$email_data['linkIntro'],$email_template);
		// link 1
			$email_template = str_replace('%link_1%',$email_data['blogLink']['linkLocation_1'],$email_template);
			$email_template = str_replace('%linkText_1%',$email_data['blogLink']['linkText_1'],$email_template);
		// if link 2
		if( isset($email_data['blogLink']['linkLocation_2']) )
		{
			$email_template = str_replace('%link_2%',$email_data['blogLink']['linkLocation_2'],$email_template);
			$email_template = str_replace('%linkText_2%',$email_data['blogLink']['linkText_2'],$email_template);
		}
	}

	// if post script
	if( array_key_exists('postScript',$email_data) )
	{
		$email_template = str_replace('%postScript%',$email_data['postScript'],$email_template);
	}

	// if unsubscribe link
	if( array_key_exists('unsubscribeLink',$email_data) )
	{
		$email_template = str_replace('%linkUnsubscribe%',$email_data['unsubscribeLink']['unsubscribeLinkLocation'],$email_template);

		if( array_key_exists('unsubscribe_key', $compiled_user_data) )
		{
			$email_template = str_replace('%unsubscribe_key%',$compiled_user_data['unsubscribe_key'],$email_template);
		}
		elseif( array_key_exists('user_email', $compiled_user_data) )
		{

			// retrieve user key from database
			if( !$unsubscribe_key = retrieveUserKey($compiled_user_data['user_email']) )
			{

				file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").' Could not retrieve user key for user email '.$compiled_user_data['user_email'].' -- '.$_SERVER['PHP_SELF'].' ('.__LINE__.')'.PHP_EOL,FILE_APPEND);

				exit('error retrieving necessary information to execute email script');
			}

			$email_template = str_replace('%unsubscribe_key%',$unsubscribe_key,$email_template);

		} else {
			// log error that no user email was provided
			exit('Error sending email');
		}

	}

	// convert user_data keys into search terms for str_replace
	// then replace them
	foreach ($compiled_user_data as $key => $value) {
		if( array_key_exists($key,$compiled_user_data) )
		{
			if( $key == 'user_name' )
			{
				$key = str_replace('_','',$key);
			}
			$search_term = '%'.$key.'%';
			$email_template = str_replace($search_term,$value,$email_template);
		}
	}

	return $email_template;

}

/**
* Auxillary function that constructs an autorespond HTML email
*
* Updated: 01/29/2016
*
* @param (array) email data
* @return (string) an HTML email
*/
function makeAutoresponder($email_data, $user_data){
	// retrieve email template
	$autorespond_email = file_get_contents(EMAIL_PATH.DS.'email_templates/autoresponder.html');

	$compiled_data_array = compileArrays($user_data);

	/**
	* NOTE: could implement a loop here..
	*/
	// input $email_data to replace keys
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
	// input $user_data to replace keys
	$autorespond_email = str_replace('%name%',$compiled_data_array['name'],$autorespond_email);
	// return email
	return $autorespond_email;
}


/**
* Sends an email to administration in cases of certain errors
*/
function notifyAdminByEmail($msg){

	$dt = date("Y-m-d H:i:s (T)");
	$msg = $dt . ' -- ' . $msg;

	// send email
	error_log($msg, 1, ADMIN_EMAIL,"From: error_report@songfarm.ca");

	return true;

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

	return false; /* for testing purposes sans internet */

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

/**
* Generate a unique selector
*
* NOTE: could be relocated to user class
* NOTE: for use in user unsubscription
*
* @return (string) 12 characters
*/
function generateUserKey(){
	// to support random_bytes function in PHP version < 7
	require_once(SITE_ROOT.DS.'/random_compat-2.0.2/lib/random.php');

	return strtr(
		base64_encode( random_bytes(9) ),
		'+/',
		'-_'
	);

}

/**
* Retrieve unsubscribe_key from database
*
* NOTE: could be relocated to user class
*
* @param (string) user email
* @return (string) user key
*/
function retrieveUserKey($user_email){
	global $db;

	$sql = "SELECT unsubscribe_key FROM user_register WHERE user_email = '$user_email'";
	if( $result = $db->query($sql) ){
		if( $data = $db->fetchArray($result) ){
			return $data['unsubscribe_key'];
		}
	}
}

/**
* Compiles a multi-dimensional array into a single-dimensional array
*
* @param (multi-dimensional array)
*/
function compileArrays($multi_dimensional_array){
	$compiled_data = [];

	foreach ($multi_dimensional_array as $key => $value) {
		if( is_array($value) ){
			foreach ($value as $key => $value) {
				$compiled_data[$key] = $value;
			}
		}
		$compiled_data[$key] = $value;
	}
	return $compiled_data;
}

?>
