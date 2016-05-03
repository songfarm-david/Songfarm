<?php
	// if no message, EXIT script
		if( !isset($success_msg) && !isset($error_msg) ){

			$location = 'http://songfarm.ca';
			header("Location: " . $location);
			exit;

			// temp
			// $success_msg = $error_msg = '';

		}
?>
<!doctype>
<html>
	<head>
		<meta charset="utf-8">
		<title>Songfarm</title>
		<meta name="description" content="Share your newest song in a live virtual songwriter's circle">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="stylesheet" href="../../public/css/global.css" type="text/css">
		<script type="text/javascript" src="../../public/js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="../../public/js/spin.js/spin.min.js"></script>
		<style>
			html, body{
				max-width: initial;
				max-height: 100%;
				overflow: hidden;
			}
			article#logoContainer{
				height: 300px;
				width: 100%;
				margin: 3.5em 0 1em;
			}
			article#logoContainer > div{
				width: 456px;	height: 100%;
				margin: 0 auto;	overflow: hidden;
			}
			article#userMessage{
				font-family: 'museo_sans300';	text-align: center;
				font-size: 2em;	color: #3F9FEB;
			}
			article#userMessage p{
				font-family: inherit;	line-height: 1.5em;
				font-weight: 600;	margin: 0 auto;
				display: block;
				max-width: 65%;
				padding: 0 1em;
			}
			article#userMessage a{	color: #7CC919; transition: color .250s; }
			article#userMessage a:hover{ color: #666666; transition: color .250s; }

			div#spinLoader{
				position: relative;
				top: -50px; bottom: 0;
				display: block;
				width: 100%; height: 300px;
				display: none;
			}
			div#spinLoader p{
				position: absolute; bottom: 1.5em;
				left:50%;	transform: translateX(-50%);
				font-size: 1.75em; font-style: italic;
			}
			span#ellipsis{ position: absolute; font-style: inherit; }

			@media screen and (max-width: 1120px){
				article#userMessage p{
					max-width: initial;
				}
			}
		</style>
	</head>
	<body>
		<article id="logoContainer">
			<div>
				<img src="../../public/images/songfarm_logo_l.png" alt="Songfarm Logo" height="auto" width="100%">
			</div>
		</article>
		<article id="userMessage">
			<?php
				/**
				* NOTE: Write code so that if there is an error message, the message stays on the screen for longer
				*/
				if ( $error_msg && is_array($error_msg) ) {
					foreach ($error_msg as $error) {
						echo '<p data-flag="error">'.$error.'</p>';
					}
				} elseif ( $success_msg || is_array($success_msg) ) {
					if( is_array($success_msg) )
					foreach ($success_msg as $success_msg) {
						echo '<p>'.$success_msg.'</p>';
					} else {
						echo '<p>'.$success_msg.'</p>';
					}
				}
				else
				{
					// redirect somewhere
					// redirectTo('../public/index.php');
					echo '<p data-flag="error">No Message - success or error - was detected. Doing a redirect now.</p>';
				}
			?>
			<!-- jQuery plug-in for spin loader -->
		</article>
		<div id="spinLoader">
			<p>redirecting<span id="ellipsis"></span></p>
		</div>
	</body>
	<script>
		// on page load event, set timer.
		window.onload = function(){

			var target = document.getElementById('spinLoader');

			var div = $('div#spinLoader');
			var span = $('span#ellipsis');
			var timerDuration;

			/* set timer duration dependant on type of message */
			var dataFlag = $('#userMessage p').data('flag');
			if( dataFlag == 'error'){
				timerDuration = 6500;
			}	else if ( dataFlag == 'default' ) {
				timerDuration = 85000;
			} else {
				timerDuration = 5000;
			}
			// console.log(timerDuration);

			/*** redirect location ***/
				// live config:
					redirectURL = 'http://test.songfarm.ca';
					// redirectURL = 'http://songfarm.ca';

				// test config:
					// redirectURL = '../public';

			// timeout function for spin loader
			setTimeout(function(){
				// init the spinner object
				new Spinner(opts).spin(target);
				// show the div after the timer
				div.fadeIn().show();
				// init counter
				var counter =	0;

				/* interval for ellipsis creation */
				setInterval(function(){

					if(counter <= 2){
						// append a period on to the element
						$(span).append('.');
						counter++;
					}
					else
					{
						var replace = $(span).text().replace('...','');
						$(span).text(replace);
						counter = 0;
					}

				}, 1000);

				/* timer for redirect */
				setTimeout(function(){
					window.location.replace(redirectURL);
				},3500);

			},timerDuration);

		}; // end of window.onload

		// parameters regarding spin loader
		var opts = {
			  lines: 10 // The number of lines to draw
			, length: 20 // The length of each line
			, width: 15 // The line thickness
			, radius: 30 // The radius of the inner circle
			, scale: 0.8 // Scales overall size of the spinner
			, corners: 0.5 // Corner roundness (0..1)
			, color: [
				'#81AD9A',
				'#9FD65C',
				'#CAE23E',
				'#EDDF3C',
				'#F1A426',
				'#F17526',
				'#D4545E',
				'#B145A3', // purple
				'#7F74C4', // purple/blue
				'#6284D8', // blue
			]// #rgb or #rrggbb or array of colors
			, opacity: 0.5 // Opacity of the lines
			, rotate: 0 // The rotation offset
			, direction: 1 // 1: clockwise, -1: counterclockwise
			, speed: 1 // Rounds per second
			, trail: 75 // Afterglow percentage
			, fps: 30 // Frames per second when using setTimeout() as a fallback for CSS
			, zIndex: 2e9 // The z-index (defaults to 2000000000)
			, className: 'spinner' // The CSS class to assign to the spinner
			, top: '50%' // Top position relative to parent
			, left: '50%' // Left position relative to parent
			, shadow: false // Whether to render a shadow
			, hwaccel: false // Whether to use hardware acceleration
			, position: 'absolute' // Element positioning
		}

	</script>
</html>
