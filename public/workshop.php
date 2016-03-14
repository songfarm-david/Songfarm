<?php require_once('../includes/initialize.php'); include_once('../includes/countries_array.php');


// check if user is logged in, if no, redirect to index.php
if(!$session->is_logged_in()) { redirectTo('index.php'); }

if(isset($_SESSION['message'])){echo $_SESSION['message'];}


/* regarding getting and setting country code
upon initial entry into workshop.php */

// if user submit's the country code form, insert data into database
if(isset($_POST['submit_location'])){
	// check if user has a timezone, if so update
	if($user->hasLocation($session->user_id)){
		$user->update_timezone($session->user_id);
	} else {
		$user->insert_timezone($session->user_id, $_POST['timezone'], $_POST['country'], $_POST['full_timezone']); }
	}
// if user timezone is NOT set, request geoplugin
if(!$user->hasLocation($session->user_id)){
	// catch returned array in $country_array from generate_ip_data()
	$country_array = generate_ip_data();
	// create new variables from the array
	list($country_code, $country_name) = $country_array;
}
else
{
	$user->hasLocation($session->user_id);
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $session->username ?>&apos;s Workshop</title>
	<link href="css/global.css" rel="stylesheet" type="text/css">
	<link href="css/nav.css" rel="stylesheet" type="text/css">
	<link href="css/workshop.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
</head>
<body>


	<!-- Top navigation bar -->
	<?php include(LIB_PATH.DS.'layout'.DS.'user_navigation.php'); ?>
	<!-- End of navigation bar -->

	<!-- Main User header -->
	<?php include(LIB_PATH.DS.'layout'.DS.'user_header.php'); ?>
	<!-- end of Main User header -->

	<main>

		<div id="overlay" class="hide"></div>
			<div id="tab-container">
				<ul>
					<li class="active">
						<a href="#" data-name="Songbook">Songbook</a>
					</li><li>
						<a href="#" data-name="Songcircle">Songcircle</a>
					</li>
				</ul>
			</div>
			<!-- if a SongTag is submitted, run the insert_song() method -->
			<?php if(isset($_POST['submit_songTag'])){$songbook->insert_song($session->user_id);} ?>
			<section id="tab-content">

				<article id="songbook" class="songbook">
					<h2>Songbook</h2>
					<div id="songbook-container">
						<div id="upload-song">
							<a href="#">Upload New Song</a>
						</div>
						<div id="pane-left">
							<?php //$songbook->output_video('5611900531d394.90335677'); ?>
							<!-- <div id="video-container">
								<video width="100" height="100" controls>
								</video> -->
							</div>
						</div>
						<div id="pane-right">
							<div id="song-list">
									<?php //$songbook->list_songs($session->user_id); ?>
							</div>
						</div>
						<script>
						$('div#song-list ul li').on('click', function(){
							var thisSongId = $(this).children('input').val();
							// $('div#pane-left').append("<?php //$songbook->output_video("+thisSongId+"); ?>");
						})
						</script>
						<!-- Songtab Form -->
						<form id="songtag" method="post" action="<?php //echo $_SERVER['PHP_SELF']; ?>" class="hide">
							<div class="first">
								<p>
									Are you uploading an Original song or a Cover?
								</p>
								<input class="radio" type="radio" name="type" value="0">Original
								<input class="radio" type="radio" name="type" value="1">Cover
							</div>
							<div class="hide cover_artist">
								<p>
									Who is the Original Artist?
								</p>
								<input type="text" name="cover_artist" maxlength="100">
							</div>
							<div class="hide second">
								<p>
									What's the name of the song?
								</p>
								<input type="text" name="song_name" maxlength="250">

							</div>
							<div class="hide">
								<p>
									Add lyric
								</p>
								<textarea name="lyric" rows="4" cols="40" placeholder="Type or Drop and Drag a lyric"></textarea>
								<p>
									Privacy settings:
								</p>
								<input type="radio" name="permission" value="0">Private (just me)
								<input type="radio" name="permission" value="1">Public (everyone)
								<br><br>
								<input type="submit" name="submit_songTag" value="Submit SongTag">
							</div>
						</form>
					</div>

					<script>
					// show/hide songtag form
					// $('div#upload-song a').on('click', function(){
					//
					// 	$("<div class=\"next disabled\">Next</div><span class=\"go-back\">Go back</span>").insertAfter('#songtag input[type="text"]');
					//
					// 	$('form#songtag').fadeIn('200').removeClass('hide');
					// 	// if/else depending on type
					// 	$('#songtag input.radio').on('click', function(){
					// 		$('#songtag .first').addClass('hide');
					// 		if( $(this).val() == 0){
					// 			$('#songtag .second').removeClass('hide');
					// 		} else {
					// 			$('#songtag .cover_artist').removeClass('hide');
					// 		}
					// 	});
					// 	$('#songtag input[type="text"]').on('keyup', function(){
					// 		var thisLen = $(this).val().length;
					// 		// console.log(thisLen);
					// 		if( thisLen != 0){
					// 			$('div.next').fadeIn('100').removeClass('disabled');
					// 		} else {
					// 			$('div.next').fadeIn('100').addClass('disabled');
					// 		}
					// 	});
					// 	$('#songtag div.next').on('click', function(){
					// 		if($(this).hasClass('disabled')){
					// 			console.log('has class');
					// 		} else {
					// 			console.log('doesn\'t have class');
					// 		}
					// 	})

						// var nameLen = $('#songtag input[name="song_name"]');
						// 	console.log(nameLen);

						// if(){
						// 	alert('public');
						// } else {
						// 	alert('private');
						// }
					// });
					</script>
				</article>

				<article id="songcircle" class="songcircle hide">
				<?php
				// call open_songcircle_exists to auto create open songcircle
				$songcircle->open_songcircle_exists();

				// if user register/unregister for songcircle
				if(isset($_POST['register'])){
					$songcircle->timezone = $user->hasLocation($session->user_id);
					$songcircle->register($_POST['songcircle_id'], $session->user_id, $session->username, $_POST['songcircle_name'], $_POST['date_of_songcircle'], $songcircle->timezone, $user->country);
				} elseif(isset($_POST['unregister'])){
					$songcircle->timezone = $user->hasLocation($session->user_id);
					$songcircle->unregister($_POST['songcircle_id'], $session->user_id, $_POST['songcircle_name'], $_POST['date_of_songcircle']);
				}
				// if user creates a songcircle
				if(isset($_POST['submit'])){
					$songcircle->create_songcircle($session->user_id);
				}
				?>

					<h2>Upcoming Songcircles:&nbsp;&nbsp;<span><a href="#">(What's a Songcircle?)</a></span></h2>

					<!-- Logic, Form and JS for user timezone upon first entry workshop.php -->
					<?php include_once(LIB_PATH.DS.'user_timezone.php'); ?>

				<script>
				// show div#participants on hover
				var participantsDiv = $('div#participants');
				$('span.registered').on('mouseover', function(){
					participantsDiv.toggle();
				});
				</script>
				</article>
			</section>
			<script>
			$("#tab-container ul li").on('click', function(){
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
		<!-- end of Main panels -->


		<!-- Suggestions bar -->
		<!-- <section id="suggest-bar">
			<img id="suggest-change" src="images/icons/community_icon.png">
			<script>
			// toggle the suggestion bar
			$('#suggest-change').on('click', function(){
				var suggestBar = $('#suggest-bar');
				if(suggestBar.height() >= 235){
					suggestBar.animate({height:'30px'}, 300);
				} else {
					suggestBar.animate({height:'235px'}, 300);
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
	function ifsongcirclestarted() {

		var methodtype = "start";
		//alert('pradip1');
		$.ajax({
			method : "POST",
			url	: "../includes/songcircleapi.php",
			data : {'type':methodtype},
			success: function(data){
				if(data != "")
		  		{
					var arr = data.split(",");

					for (var i = 0; i < (arr.length-1); i++)
					{
						$('#divJoin' + arr[i]).show();
					}
		      	}
			}
		});
	}
	var interval = setInterval(ifsongcirclestarted, 110000);

	function ifsongcirclecomlpeted() {

		var methodtype = "complete";
		//alert('pradip1');
		$.ajax({
			method : "POST",
			url	: "../includes/songcircleapi.php",
			data : {'type':methodtype},
			success: function(data){
				if(data != "")
		  		{
					var arr = data.split(",");

					for (var i = 0; i < (arr.length-1); i++)
					{
						$('#divJoin' + arr[i]).hide();
					}
		      	}
			}
		});
	}
	var interval = setInterval(ifsongcirclecomlpeted, 120000);

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
	</script>
	<!-- Global message -->
	<?php include(LIB_PATH.DS.'global_message.php'); ?>
	<!-- end of Global messages -->


</body>
</html>
