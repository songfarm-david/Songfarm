<?php

function redirect_to($location) {
	header("Location: " . $location);
	exit;
}

function generate_ip_data(){
	// if the Server detects an IP address, set it to $user_ip variable
	if(isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){
		$user_ip = $_SERVER['REMOTE_ADDR'];
	} else {
		$user_ip = "";
	}

	// get contents of the IP address
	$ip_contents = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip));

	// create an array to hold IP data
	$ip_data = [];
	foreach ($ip_contents as $key => $value) {
		$key = substr($key, 10); // remove 'geoplugin' part of key..
		if( // if there are matches for any of these conditions, place into $ip_data[]
		$key == 'countryCode' || 	$key == 'countryName'	)	{
			$ip_data[$key] = $value;
		}
	} // end of foreach loop

	// create country array
	$country_array = [];
	// create variables to contain ip keys
	$country_array[] = strtoupper($ip_data['countryCode']); // make sure country code is always uppercase
	$country_array[] = ucfirst($ip_data['countryName']); // first letter is always upper case

	return $country_array;
}



?>
