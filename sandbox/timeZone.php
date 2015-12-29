<?php require_once('../includes/initialize.php'); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Zone</title>
	<script type="text/javascript" src="../public/js/jquery-1.11.3.min.js"></script>
</head>
<body>
	<?php if(!empty($countryName)){ ?>
	<h1>Timezone based on <?php echo $countryName ?></h1>
	<?php } ?>

	<p>Select your Country from the list to find your timezone</p>
	<select id="countries">
		<?php if(isset($country_code) && !empty($countryName)){ ?>
			<option value="<?php echo $country_code ?>"><?php echo $countryName; ?></option>
		<?php }

		$continents = [];
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

	<input id="country-code" value="<?php echo $country_code; ?>" type="hidden">

	<select id="timezones">
	<?php	//echo timezones_from_countryCode($country_code); ?>
	</select>
<!-- <select>
	<option id="timezone" value="">

	</option>
	</option>
</select> -->
</body>
</html>
<script>
// country code
$(document).ready(function(){
	var initialCountryCode = $('#country-code').val();
	$.ajax({
		method : "POST",
		url	: "../includes/timezonesFromCountryCode.php",
		data : {'country_code':initialCountryCode},
		success: function(data){
			$('#timezones').html(data);
		}
	});
	$('#countries').on('change',function(){
		var countryCode = $(this).val(); // this gets the country code
		$.ajax({
			method : "POST",
			url	: "../includes/timezonesFromCountryCode.php",
			data : {'country_code':countryCode},
			success: function(data){
				$('#timezones').html(data);
			}
		});
	});
});
</script>
