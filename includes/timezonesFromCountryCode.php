<?php require('initialize.php');

/* Ajax request/response from workshop.php */

if(isset($_POST['country_code'])){
	$country_code = $_POST['country_code'];
	// echo back response to Ajax
	echo timezones_from_countryCode($country_code);
}

/*
* Function - Get list of timezones based on
* a country code.
*
* Gets timezone offsets based on UTC
*
* Orders and formats timezones
*
* @param string a country code (eg. EC, CA)
* @return string html options with formatted timezones
*/
function timezones_from_countryCode($country_code){
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

		// clean up raw timezone
		$clean_timezone = User::clean_city($raw_timezone);
		// echo back options to a select dropdown on workshop.php
		echo "<option value=\"$raw_timezone\">(".$pretty_offset.") " . $clean_timezone . ' ('.$timezone_abbr.')</option>';
	}
}

?>
