<?php require('initialize.php');

if(isset($_POST['country_code'])){
	$country_code = $_POST['country_code'];
	$country_name = $_POST['country_name'];
	echo timezones_from_countryCode($country_code, $country_name);
}

function timezones_from_countryCode($country_code, $country_name){
	$dt = new DateTime();

	// create a list of timezones based on that country code..
	$timezones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);

	$timezone_offset = [];
	// instantiate timezone_offset array
	foreach ($timezones as $timezone) {
		$tz = new DateTimeZone($timezone);
		$timezone_offset[$timezone] = $tz->getOffset(new DateTime);
	}

	// sort by offset
	asort($timezone_offset);
	// format display of timezone and offset
	foreach($timezone_offset as $raw_timezone => $offset)	{

		$dt->setTimezone(new DateTimeZone($raw_timezone));
		$timezone_abbr = $dt->format('T');

		$offset_prefix = $offset < 0 ? '-' : '+';
		$offset_formatted = gmdate( 'H:i', abs($offset) );
		$pretty_offset = "UTC${offset_prefix}${offset_formatted}";

		// if( ($pos = strpos($raw_timezone, '/') ) !== false ) { // remove 'America/'
		// 	$clean_timezone = substr($raw_timezone, $pos+1);
		// 	if( ($pos = strpos($clean_timezone, '/')) !== false ) { // remove second level '.../'
		// 		$clean_timezone = substr($clean_timezone, $pos+1);
		// 	}
		// }
		// $clean_timezone = str_replace('_',' ',$clean_timezone); // remove the '_' in city names
		$clean_timezone = User::clean_city($raw_timezone);
		echo "<option value=\"$raw_timezone\">(".$pretty_offset.") " . $clean_timezone . ' ('.$timezone_abbr.')</option>';

	}
}

?>
