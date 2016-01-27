<!--

This page will act as a template for any user confirmation pages,
namely pages like songcircleConfirmUser and songcircleRemoveUser.

It should leave a space to insert some php...
Perhaps it can read $error_msg array or $success_array

NOTE: this page needs styling

-->
<!doctype>
<html>
	<head>
		<meta charset="utf-8">
		<title>Songfarm</title>
		<meta name="description" content="Share your newest song in a live virtual songwriter's circle">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="stylesheet" href="../../public/css/index.css" type="text/css">
		<script type="text/javascript" src="../../public/js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="../../public/js/spin.js/spin.min.js"></script>
		<style>
			body{
				background: rgba(0, 255, 0, 0.09);
				height: 100%; width: 100%;
				max-width: 100%;
				margin: 0;
			}
			div.confirmMsg{
				position: absolute;;
				top:50%; left:50%;
				transform: translateY(-50%) translateX(-50%);
				display: inline-block;
				/*width:auto;*/
				padding:1.5em 4em 8em;
				margin: -2.5em auto;
				border:1px dotted rgba(56, 97, 56, 0.71);
				background: #fff;
				font-size: 2.2em;
				text-align: center;
				border-radius: 6px;
				box-shadow: -1px 1px 5px 1px;
			}
			div#spinLoader{
				position: absolute;	z-index:50;
				top:50%; left:50%;
				transform: translateY(-50%) translateX(-50%);
				display: block;	width:50%; height:50%;
			}
			div#spinLoader p{
				position:absolute; bottom:-1.5em;
				left:50%;	transform: translateX(-50%);
				font-size: 0.75em; font-style: italic;
			}
			span#ellipsis{
				position:absolute;
				font-style: inherit;
			}
		</style>
	</head>
	<body>
		<div class="confirmMsg">
			<?php

				$error_msg = $success_msg = '';

				if( $error_msg && is_array($error_msg) ){
					foreach ($error_msg as $error) {
						echo $error . '<br>';
					}
				} elseif($success_msg){
					echo $success_msg;
				} else {
					echo '<p>Nothing to show you</p>';
				}


			?>
			<div id="spinLoader">
				<p>redirecting<span id="ellipsis"></span></p>
			</div>


		</div>
	</body>

	<script>
		// on page load event, set timer.
		window.onload = function(){

			// target location
			redirectURL = 'http://test.songfarm.ca';

			var target = document.getElementById('spinLoader');
			var spinner = new Spinner(opts).spin(target);

			// get the element
			var para = $('div#spinLoader p');
			var span = $('span#ellipsis');
			// init counter
			var counter =	0;

			setInterval(function(){

				if(counter <= 2){
					// append a period on to the element
					$(span).append('.');
					// augment the counter
					counter++;
				} else {
					var replace = $(span).text().replace('...','');
					$(span).text(replace);
					counter = 0;
				}
			}, 1000);

		}; // end of window.onload

		// display a redirecting to... with a load wheel
		var opts = {
		  lines: 11 // The number of lines to draw
		, length: 16 // The length of each line
		, width: 14 // The line thickness
		, radius: 26 // The radius of the inner circle
		, scale: 0.8 // Scales overall size of the spinner
		, corners: 1 // Corner roundness (0..1)
		, color: [
			'#A0F122', // green
			'#E1D11A', // yellow
			'#AC44A1', // purple
			'#557FC2', // blue
			'#D86919', // pinky red
		]// #rgb or #rrggbb or array of colors
		, opacity: 0.25 // Opacity of the lines
		, rotate: 0 // The rotation offset
		, direction: 1 // 1: clockwise, -1: counterclockwise
		, speed: 0.9 // Rounds per second
		, trail: 60 // Afterglow percentage
		, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
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
