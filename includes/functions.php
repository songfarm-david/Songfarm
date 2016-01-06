<?php

/**
* Function - redirect to
* a new location
*
* @param string location
*/
function redirect_to($location) {
	header("Location: " . $location);
	exit;
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


		/* Testing IPs */

		// localhost equates to ::1 no location information
		// $user_ip = ""; // equates to nothing, outputs NL Netherlands
		// $user_ip = '181.196.204.134'; // IP for Ecuador
		$user_ip = '192.206.151.131'; // IP for Toronto
		// $user_ip = '2605:e000:fa83:6c00:d143:745:8b73:2daa'; // California IP

	} else {
		// if results here, could not detect IP address - Should do something
		$user_ip = " ";
	}

	// get contents of the IP address
	$ip_contents = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip));

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

?>
