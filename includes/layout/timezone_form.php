<!-- Timezone form -->
<form id="country_code" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<select id="countries">

	<?php
	// if system can deduce from IP country name and code, then display it
	if(isset($country_code) && !empty($country_name)){ ?>
		<option value="<?php echo $country_code ?>"><?php echo $country_name; ?></option>
	<?php }

	// generate countries select menu
	$continents = [];
	// $countries comes from countries_array.php
	foreach ($countries as $country) {
		$continents[] = $country['continent'];
		$continents = array_unique($continents);
	}
	foreach($continents as $key => $continent){
		echo "<optgroup label=\"{$continent}\"></optgroup>";
		foreach ($countries as $key => $value) {
			if($value['continent'] == $continent){
				echo "<option value=\"$key\">". $value['country'] ."</option>";
			}
		}
	}
	?>
</select>
<select id="timezones" name="timezone"></select>
<input type="hidden" name="country" value="">
<input type="hidden" name="full_timezone" value="">
<br>
<input type="submit" name="submit_location" value="Submit">
</form>
<!-- end of Timezone form -->
<script>
/**
* script for getting country code information
* upon user's first entry into workshop.php
*/
$(document).ready(function(){

	// if IP generates country code upon load
	// get value of input name="country_code"
	var initialCountryCode = $('form#country_code + input[name="country_code"]').val();

	// set fields in the form
	getAndSetCountryName();

	// call ajax with initial country code
	callAjax(initialCountryCode);


	// if there is a change from initial country
	$('select#countries').on('change',function(){

		// get the value of the selected option box in the select dropdown
		var countryCode = $(this).val();

		// get and set the form elements
		getAndSetCountryName();

		// call Ajax with updated country code
		callAjax(countryCode);
	});

	$('select#timezones').on('change', function(){
		getFullTimezone();
	})


	/**
	* Function - Gets text inside
	* countries select dropdown and
	* populates a hidden form field
	*/
	function getAndSetCountryName(){
		// get the country name from selected option
		var countryName = $('select#countries option:selected').text();
		// target hidden input field and input value as country name
		$('form#country_code input[name="country"]').val(countryName);
	}

	/**
	* Function - makes Ajax call
	* to timezonesFromCountryCode.php
	*
	* @param string a country code
	* @return string html
	* @method function calls selectOption_has_value()
	*/
	function callAjax(countryCode){
		// make ajax call to timezonesFromCountryCode.php with country code
		$.ajax({
			method : "POST",
			url	: "../includes/timezonesFromCountryCode.php",
			data : {'country_code':countryCode},
			success: function(data){
				// input return data as options in select dropdown
				$('select#timezones').html(data);
				// check if select option has value
				selectOption_has_value();
			}
		}); // end of Ajax call
	}

	/**
	* Function - Gets value of
	* timezones select dropdown
	*
	* If no value, disable submission of form
	*
	*/
	function selectOption_has_value(){
		var timezoneSelectChildren = $('select#timezones option').val();
		// if timezoneSelect length is equal to 0 then there are no options below
		if(!timezoneSelectChildren){
			$('form#country_code input[type="submit"]').prop('disabled', true);
		} else {
			getFullTimezone();
			// enable submit button to call ajax
			$('form#country_code input[type="submit"]').prop('disabled', false);
		}
	}

	/**
	* Function - get formatted
	* timezone from timezone select dropdown
	* and sets hidden form input value to such
	*/
	function getFullTimezone(){
		// get full formatted timezone
		var full_timezone = $('select#timezones option:selected').html();
		// set full formatted timezone
		$('form#country_code input[name="full_timezone"]').val(full_timezone);
	}

});
</script>
