<?php require_once('../includes/initialize.php'); include_once('../includes/countries_array.php');
if(!$session->is_logged_in()) { redirect_to('index.php'); }
$image->user_id = $session->user_id;
$songcircle->open_songcircle_exists(); // see that open songcircle exists

if(isset($_POST['register'])){
	$songcircle->register($_POST['songcircle_id'], $session->user_id, $session->username, $_POST['songcircle_name'], $_POST['date_of_songcircle']);
} elseif(isset($_POST['unregister'])){
	$songcircle->unregister($_POST['songcircle_id'], $session->user_id, $_POST['songcircle_name'], $_POST['date_of_songcircle']);
}
// if user creates a songcircle
if(isset($_POST['submit'])){
	$songcircle->create_songcircle($session->user_id);
}

// if the user timezone is already set, then skip the request to geoplugin
if(!$user->has_timezone($session->user_id)){ 	generate_ip_data(); }

if(isset($_POST['submitCountryCode'])){
	$user->insert_timezone($session->user_id, $_POST['timezone']);
}

if(!isset($_SESSION['message'])){
	echo $songcircle->message;
} else {
	echo $_SESSION['message'];
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $session->username ?>&apos;s Workshop</title>
	<link href="css/workshop.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
</head>
<body>
	<!-- Top navigation bar -->
	<nav>
		<h1>Navigation</h1>
		<span class="logo">Songfarm</span>
		<ul id="settings-trigger">
			<li>
				<a href="#"><?php echo $session->username; ?></a>
				<ul id="settings-drop-down" class="hide">
					<li>
						<a href="profile.php?id=<?php echo $session->user_id; ?>">View Profile</a>
					</li>
					<li>
						<a href="artist_settings.php">Settings</a>
					</li>
					<li>
						<a href="../includes/sign_out.php">Log Out</a>
					</li>
				</ul>
			</li>

		</ul>
	</nav>
	<!-- End of navigation bar -->
	<!-- Main user header -->
	<header>
		<!-- Hold user image -->
		<?php echo isset($_POST['submit_image']) ? $image->upload_image($_FILES['file_upload']) : $image->retrieve_user_photo(); ?>
		<div class="user_image" style="background-image:url('../uploaded_images/<?php echo $image->image_name; ?>')"></div>
		<form id="upload_user_image" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$session->user_id ?>" method="post" enctype="multipart/form-data" class="hide">
			<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
			<input type="file" name="file_upload">
			<input type="submit" name="submit_image" value="Upload">
			<?php if($image->photo_errors) { ?>
				<span>Photo error:</span>
				<ul>
					<?php foreach ($photo_errors as $error) {
						echo "<li>{$error}</li>";
					} ?>
				</ul>
			<?php } ?>
		</form>
		<!-- H1 holds user name -->
		<h1><?php echo $session->username; ?></h1>
			<!-- hold user information
			- Roles
			- Location
			- Style(s)
			-->
			<span class="attr">Singer/Songwriter, Lyricist</span>&nbsp;<span style="font-style:italic;">located in</span>&nbsp;<span style="font-weight:bold;">Canoa, Ecuador</span>
			<!-- hidden form for image -->

			<!-- end of form -->
	</header>
	<!-- end of Main user header -->
	<!-- Message display
	- a place for user messages to appear
	-->

	<main>

		<!-- <aside class="aside-left">
			<button>
				<a href="#">Invite other Songwriters</a>
			</button>
			<?php if($_SESSION['permission'] != 1) {
				echo "<button class=\"inactive\"><a href=\"#\">Host a Songcircle</a>";
			} else {
				echo "<button id=\"host_songcircle\"><a href=\"#\">Host a Songcircle</a>";
			}?>
			</button>
		</aside> -->
		<?php echo $songcircle->message; ?>
		<!-- Main panels -->
		<!-- <div id="tab-container"> -->

			<div id="tab-container">
				<ul>
					<li class="active">
						<a href="#" data-name="Songbook">Songbook</a>
					</li><li>
						<a href="#" data-name="Songcircle">Songcircle</a>
					</li>
				</ul>
			</div>

			<section id="tab-content">
				<article id="Songbook" class="songbook">
					<h2>Songbook</h2>
				</article>
				<article id="Songcircle" class="songcircle hide">
					<h2>Songcircle</h2><span><a href="#">What's a Songcircle?</a></span>
					<?php // if user timezone is not currently set, prompt them to set it.
					if($user->has_timezone($session->user_id)) {
						$songcircle->timezone = $user->timezone;
						$songcircle->display_songcircles();
					} else {
						if(!empty($countryName)){ ?>
						<h1>Timezone based on <?php echo $countryName ?></h1>
						<?php } ?>

						<p>Select your Country from the list to find your timezone</p>
						<form id="countryCode" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<select id="countries" name="countries">
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

						<input type="hidden" id="country-code" value="<?php echo $country_code; ?>">

						<select id="timezones" name="timezone">
						<?php	//echo timezones_from_countryCode($country_code); ?>
						</select>
						<input type="submit" name="submitCountryCode" value="Submit">
						</form>
				<?php	} ?>
				</article>
			</section>
			<script>
			$("ul li").on('click', function(){
				// if 'this' has not class 'active' then switch class active to this

				if(!$(this).hasClass('active')){ // if clicked element does not have class 'active'
					// find the element that does have class active
					var active = $('ul').children('li.active');

					// remove class active from element with class 'active'
					active.removeClass('active');

					// add class active to clicked element
					$(this).addClass('active');

					// get the data-name of the currently visible tab
					var activeName = active.children('a').attr('data-name');
					var activeName = activeName.toLowerCase();

					// prepare the variable for insertion
					// var activeName = '"'+activeName.toLowerCase()+'"';

					// get handle to current tab
					var currentTab = $('section').children('article.'+activeName);
					// add class hide to current tab
					currentTab.addClass('hide');

					// find corresponding tab to clicked element
					var thisTab = $(this).children('a').attr('data-name');
					var thisTab = thisTab.toLowerCase();

					// get handle to target tab
					var targetTab = $('section').children('article.'+thisTab);
					// remove class 'hide' from target thisTab
					targetTab.removeClass('hide');

				}
			})
			</script>

			<!-- <article id="tab-songbook" class="tab-content">
				<h3>Songbook</h3>
			</article> -->

			<!-- <article id="tab-songcircle" class="tab-content">
				<div>
					<h3>Upcoming Songcircles:</h3><span><a href="#">What's a Songcircle?</a></span>
          	<?php // if user timezone is not currently set, prompt them to set it.
						if($user->has_timezone($session->user_id)) {
							$songcircle->timezone = $user->timezone;
							$songcircle->display_songcircles();
						} else {
							if(!empty($countryName)){ ?>
							<h1>Timezone based on <?php echo $countryName ?></h1>
							<?php } ?>

							<p>Select your Country from the list to find your timezone</p>
							<form id="countryCode" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<select id="countries" name="countries">
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

							<select id="timezones" name="timezone">
							<?php	//echo timezones_from_countryCode($country_code); ?>
							</select>
							<input type="submit" name="submitCountryCode" value="Submit">
							</form>
					<?php	} ?>
				</div>

			</article> -->

			<!-- <article id="tab-liveConcert" class="tab-content">
				<h3>Live Concert</h3>
			</article> -->

		<!-- </div><!-- end of tab container -->
		<!-- end of Main panels -->
		<!-- Suggestions bar -->
		<!-- <section id="suggest-bar">
			<img id="suggest-change" src="images/icons/community_icon.png">
			<script>
			// toggle the suggestion bar
			$('#suggest-change').on('click', function(){
				var suggestBar = $('#suggest-bar');
				if(suggestBar.height() >= 250){
					suggestBar.animate({height:'30px'}, 300);
				} else {
					suggestBar.animate({height:'250px'}, 300);
				}
			});
			</script>
		</section> -->
		<!-- end of Suggestions bar -->
	</main>



  <div id="overlay" class="hide"></div>
  <form id="host_a_songcircle" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="hide">
    <h1>Host a Songcircle</h1>
    <p>
      Name your Songcircle:
    </p>
    <input type="text" name="songcircle_name" maxlength="50" required><br>
    <p>
      Select a time and date: (all times are GMT)<!-- some sort of calculation to take into account where the user is located globally and reflecting and accurate transposition -->
    </p>
    <input type="datetime-local" name="date_of_songcircle" required>
    <p>
      Is your Songcircle Public (open to everyone) or Private (invite other Songwriters from your Songwriter's Circle)?
    </p>
    Public<input type="radio" name="songcircle_permission" value="Public" required></label>
    Private<input type="radio" name="songcircle_permission" value="Private" required> <!--Private currently inactive-->
    <p>
      What is the maximum number of participants you'll allow?
    </p>
    <select name="max_participants" required>
      <?php for ($i=2; $i <= 12 ; $i++) {
        echo "<option value=\"{$i}\">{$i}</option>";
      } ?>
    </select>
    <br>
    <br>
    <input type="submit" name="submit" value="Create">
    <?php //echo $message; ?>
  </form>
	<script>
	// user settings dropdown
	$('ul#settings-trigger').on('mouseover', function(){
		console.log('show');
		$('#settings-drop-down').show();
	});
	$('ul#settings-drop-down, ul#settings-trigger').on('mouseout', function(){
		$('#settings-drop-down').hide();
	});

  // host songcircle overlay
  $('#host_songcircle').on('click', function(){
    $('form#host_a_songcircle, div#overlay').fadeIn().removeClass('hide');
  });
  // hide form and overlay
  $('#overlay').on('click', function(){
    $('#overlay, form#host_a_songcircle').fadeOut().addClass('hide');
  })

	// script for tabbed panels
	$('#tab-container ul').each(function(){

		var $active, $content, $links = $(this).find('a');

		$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[1]);
		$active.addClass('active');
		$content = $($active[0].hash);
		$links.not($active).each(function () {
      $(this.hash).hide();
    });
		// Bind the click event handler
    $(this).on('click', 'a', function(e){
      // Make the old tab inactive.
      $active.removeClass('active');
      $content.hide();

      // Update the variables with the new link and content
      $active = $(this);
      $content = $(this.hash);

      // Make the tab active.
      $active.addClass('active');
      $content.show();

      // Prevent the anchor's default click action
      e.preventDefault();
    });
	})

	// user image
	$('.user_image').on('click', function(){
		$('#upload_user_image').fadeIn('fast').removeClass('hide');
	})
	</script>
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
</body>
</html>
