<!-- Registration Form -->
<div id="overlay"></div>
<form id="registration_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="hide">
	<!-- General Information -->
	<div id="details">
		<p>Songcircle Registration</p>
		<label for="username">
		<input type="text" name="username" maxlength="60" placeholder="Please enter your Name" tabindex="1">
		<div class="form_error" name="username"></div>
		</label>
		<label for="user_email">
		<input type="email" name="user_email" maxlength="80" placeholder="Please enter your Email" tabindex="2">
		<!-- NOTE: have fallbacks in place for browsers that don't support the email input (ie 8 & 9)-->
		<div class="form_error" name="user_email"></div>
		</label>
	</div>
	<!-- end of General information -->
	<!-- Location -->
	<div id="loc">
		<!-- conditional for whether generateIPData() has provided values -->
		<?php
			if((isset($country_code) && !empty($country_code))
			&& (isset($country_name) && !empty($country_name))){
		?>
		<p id="timezone">
			Please select the most appropriate timezone for your location
		</p>
		<!--
			Jquery inserts timezone select list here if user has IP data
		-->
		<p id="locationMsg">
			Timezone based on <b><?php echo $country_name; ?></b>
			<br>
			<span id="trigger-location" tabindex="4">&lpar;Not right?&rpar;</span>
		</p>
		<!-- end of: if((isset($country_code) && (isset($country_name))) -->
	<?php
		} else {
	?>
	<!-- if NO values generated from generateIPData() -->
		<p>
			Please select your country from the list
		</p>
	<?php
		}
	?>
		<div id="user_location" class="hide">
		<!-- include countries array -->
		<?php include('../includes/helper_functions/countries_array.php'); ?>
			<!-- select dropdown for countries -->
			<select id="country_select" tabindex="5">
			<!-- conditional for whether generateIPData() has provided values -->
			<?php
				if((isset($country_name) && !empty($country_name))
				&& (isset($country_code) && !empty($country_code))){
			?>
				<option value="<?php echo $country_code ?>"><?php echo $country_name; ?></option>
			<?php
				} // end of: (isset($country_name)) && (isset($country_code))
			// init $continents array
			$continents = [];
			foreach ($countries as $country) {
				// populate $continents array with continents countries array
				$continents[] = $country['continent'];
				// remove duplicate values with array_unique
				$continents = array_unique($continents);
			}
			foreach($continents as $key => $continent){
				// if NOT continent == Asia, Africa, Antarctica..
				/**
				* NOTE: can exclude continents here..
				*/
				if( $continent != 'Asia' && $continent != 'Africa' && $continent != 'Antarctica'){
					echo "<optgroup label=\"{$continent}\"></optgroup>";
					foreach ($countries as $key => $value) {
						if($value['continent'] == $continent){
							echo "<option value=\"$key\">". $value['country'] ."</option>";
						}
					}
				}
			}
			?>
			</select>
		</div><!-- end of: <div id="user_location" class="hide"> -->
	</div><!-- end of: <div id="loc"> -->
	<!-- end of: Location -->
	<!-- hidden inputs for location -->
	<input type="hidden" name="full_timezone" value="">
	<input type="hidden" name="city_name" value="<?php if(isset($city_name)){echo $city_name;}?>">
	<input type="hidden" name="country_name" value="<?php if(isset($country_name)){echo $country_name;}?>">
	<input type="hidden" name="country_code" value="<?php if(isset($country_code)){echo $country_code;}?>">
	<!-- Code of Conduct -->
	<div>
		<div id="codeOfConduct">
			<input type="checkbox" name="codeOfConduct" tabindex="6" class="checkbox-custom"><label for="codeOfConduct" class="checkbox-custom-label">I agree to the </label><a href="#" tabindex="7">Code&nbsp;of&nbsp;Conduct</a>
		</div>
		<!-- Form submit -->
		<input type="submit" name="register" value="Register" tabindex="8">
	</div>
	<!-- error output -->
	<div id="output"></div>
</form>
<!-- end of Registration Form -->
