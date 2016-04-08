<?php include('../includes/initialize.php') ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Background Basics</title>
	<link rel="stylesheet" href="css/global.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<style>
		html{	width: 100%;
			/*height: 100%;*/
			/*overflow: hidden;*/
		}
		body{ max-width: 100%; background: #ffffff; }
		nav{
			position: static;
			z-index: 10;
			background: #fff;
			/*-webkit-box-shadow: 20px 30px 10px -24px rgba(0, 0, 0, 0.35);
			-moz-box-shadow: 20px 30px 10px -24px rgba(0, 0, 0, 0.35);
			box-shadow: 0px 20px 25px -10px rgba(0, 0, 0, 0.35);
			border:1px solid green;
			border-bottom-left-radius: 30px;*/
		}
		header#header{ max-width: 1600px; margin: 0 auto; }
		button.register.medium{ display: none; }

		main{
			position: relative; z-index: 1;
			width: 100%; margin: 0;
			box-sizing: inherit;
			background-size: 100%, 100%;
			overflow: hidden;
			/*height: 900px;*/
			/*border: 2px solid black;*/
			background-position: left top;
			/*background: url('images/songcircle/floating_notes.png') no-repeat 0% 100%, rgb(255,255,255);
			background: url('images/songcircle/floating_notes.png') no-repeat 0% 100%, -moz-linear-gradient(top,  rgb(255,255,255) 0%, rgb(253,255,251) 16%, rgb(237,252,217) 23%, rgb(225,250,189) 27%, rgb(188,244,109) 37%, rgb(154,230,59) 46%, rgb(144,226,43) 49%, rgb(95,184,39) 64%, rgb(31,155,58) 75%, rgb(4,146,67) 81%, rgb(0,145,69) 83%, rgb(0,145,69) 100%);
			background: url('images/songcircle/floating_notes.png') no-repeat 0% 100%, -webkit-linear-gradient(top,  rgb(255,255,255) 0%,rgb(253,255,251) 16%,rgb(237,252,217) 23%,rgb(225,250,189) 27%,rgb(188,244,109) 37%,rgb(154,230,59) 46%,rgb(144,226,43) 49%,rgb(95,184,39) 64%,rgb(31,155,58) 75%,rgb(4,146,67) 81%,rgb(0,145,69) 83%,rgb(0,145,69) 100%);*/
			background: url('images/songcircle/floating_notes.png') no-repeat 0% 100%, linear-gradient(to bottom, rgb(255,255,255) 0%,rgb(253,255,251) 16%,rgb(237,252,217) 23%,rgb(225,250,189) 27%,rgb(188,244,109) 37%,rgb(154,230,59) 46%,rgb(144,226,43) 49%,rgb(95,184,39) 64%,rgb(31,155,58) 75%,rgb(4,146,67) 81%,rgb(0,145,69) 83%,rgb(0,145,69) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#009145',GradientType=0 );
		}

		article#banner{
			position: relative; z-index: 10;
			height: 515px; padding-top: 1em;
			/* styles for the bg image */
			background: url('images/songcircle/girlguitarcut.png') no-repeat transparent;
			background-size: 75%;
			background-position: 100% 0;

			/* white blur box shadow over image */
			-webkit-box-shadow: inset 0px 25px 50px -10px rgb(255, 255, 255);
			-moz-box-shadow: inset 0px 25px 50px -10px rgb(255, 255, 255);
			box-shadow: inset 0px 25px 50px -10px rgb(255, 255, 255);
		}
		article#banner img, h1, h2{ margin-left: 19%; }
		article#banner h1, h2{ width: 64%; line-height: 1.5; }
		article#banner h1{ font-size: 2.5em; margin-top: 0.5em; margin-bottom: 0.5em; }
		article#banner h2{ font-size: 1.5em; width: 45%; font-style: italic; }

		article#schedule{
			position: relative; top: -160px;
			background: url('images/songcircle/white_notes.png') no-repeat transparent;
			height: 318px;
		}
		#schedule div#schedule_container{
			width: 65%;	min-width: 950px;
			position: relative; z-index: 100;
			top: 110px;
			margin: 0 auto;
		}
		#schedule div#datesList_container{
			position: absolute; top: -90px;
			width:40%; min-width: 440px;
			font-size: 1.5em;
			padding-left: 0.5em;
		}
		#datesList_container p:first-child, #datesList_container .selected{ color: #3F9FEB;}
		#datesList_container p:last-child{ font-weight: 900; }
		#schedule #datesList_container p{ padding: 0.25em;}
		#schedule #datesList_container .selected, .month{ font-weight: bold; }
		#schedule #datesList_container .selected:before{
			content: url('images/songcircle/green_arrow.png');
			margin-right: 3px;
		}
		#schedule table{
			width: 100%; text-align: center;
			font-weight: 900;	line-height: 1.4;
			border: 3px solid #fff;
			border-radius: 30px;
			box-shadow: 0px 1px 6px 2px rgba(0,0,0,0.45);
			background: #e5fcca;
			background: -moz-linear-gradient(top,  #e5fcca 0%, #99ee20 45%, #89dc3d 70%, #7ec919 80%, #92ea41 100%);
			background: -webkit-linear-gradient(top,  #e5fcca 0%,#99ee20 45%,#89dc3d 70%,#7ec919 80%,#92ea41 100%);
			background: linear-gradient(to bottom,  #e5fcca 0%,#99ee20 45%,#89dc3d 70%,#7ec919 80%,#92ea41 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e5fcca', endColorstr='#92ea41',GradientType=0 );
		}
		#schedule table td{
			width: 33.3%;
			font-size: 1.25em;
			vertical-align: middle;
			padding: 1em 0;
		}
		#schedule table td:nth-child(2){
			position: absolute; top: -12px;
			/*width: 33.3%;*/
			height: 134px;
			padding: 1.65em 0 0;
			font-size: 1.45em;
			line-height: 1.25;
			border-radius: 30px;
			box-shadow: 0px 0px 10px 5px rgba(0,0,0,0.25);

			background: rgb(255,255,255);
			background: -moz-linear-gradient(top,  rgb(255,255,255) 0%, rgb(255,255,255) 17%, rgb(252,252,252) 23%, rgb(235,235,235) 49%, rgb(213,213,213) 75%, rgb(242,242,242) 90%, rgb(255,255,255) 100%);
			background: -webkit-linear-gradient(top,  rgb(255,255,255) 0%,rgb(255,255,255) 17%,rgb(252,252,252) 23%,rgb(235,235,235) 49%,rgb(213,213,213) 75%,rgb(242,242,242) 90%,rgb(255,255,255) 100%);
			background: linear-gradient(to bottom,  rgb(255,255,255) 0%,rgb(255,255,255) 17%,rgb(252,252,252) 23%,rgb(235,235,235) 49%,rgb(213,213,213) 75%,rgb(242,242,242) 90%,rgb(255,255,255) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff',GradientType=0 );
		}
		#schedule .triggerParticipantsTable{ font-size: 0.75em; text-decoration: underline; }
		/* green arrow */
		#schedule table td:nth-child(2) img{
			position: absolute;
			display: block;
			left: calc(50% - ( 19px/2 ));
			bottom: 10px;
		}
		#schedule table input[type="submit"]{
			padding: 9px 0;
			width: 75%;
			font-size: 1.75em;
			font-weight: 700;
			color: white;

			border:2px solid white;
			border-radius: 24px;
			box-shadow: 0px 1px 6px 2px rgba(255,255,255,.85);

			background: rgb(134,231,255);
			background: -moz-linear-gradient(top,  rgb(134,231,255) 0%, rgb(63,216,255) 17%, rgb(18,121,222) 100%);
			background: -webkit-linear-gradient(top,  rgb(134,231,255) 0%,rgb(63,216,255) 17%,rgb(18,121,222) 100%);
			background: linear-gradient(to bottom,  rgb(134,231,255) 0%,rgb(63,216,255) 17%,rgb(18,121,222) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#86e7ff', endColorstr='#1279de',GradientType=0 );
		}
		#schedule table input[type="submit"]:hover{
			cursor: pointer;
		}
		#schedule table input[type="submit"]:before{
			content: 'pizza';
			/*content:url('../images/arrows_and_lines/slider_arrow_right.png');*/
		  /*position:absolute; left:-15px; top:3px;*/
		}
		#schedule .month, #schedule .triggerParticipantsTable{ color: #3F9FEB; /* powder blue */ font-weight: bold; }
		#schedule span.event_name{ color: #009145; /* forest green */}

		article#about{
			position: relative; top: -285px; z-index: 2;
			background: #ffffff;
			width: 79.5%;	margin: 0 auto;
			border-radius: 12px;
			padding: 1em 7.5% 4em;
			/*background: url('images/songcircle/floating_notes.png') no-repeat transparent;*/
		}
		#about h3{ font-size: 2em; font-weight: 500; padding: 3.25em 0 2.5em; }
		#about p{
			font-size: 1.3em;
			padding: 1.5em 0 0;
			line-height: 1.3;
		}
		#about hr{
			height:1px;
			font-size: 2em;
			border: none;
			background-image: linear-gradient(to right, #333 10%, rgba(255, 255, 255, 0) 0%);
			background-position: top;
			background-size: 10px 1px;
			background-repeat: repeat-x;
		}

		/*footer div.share-links{ left: 10.2% } /* left margin for full width footer */

		@media screen and (max-width: 1290px) and (min-width: 840px){
			article#banner{
				background-size: 102%;
				background-position: -300% 0%;
			}
			article#about{
				width: 85%;
			}
		}
		@media screen and (max-width: 1100px){
			article#about{
				width: 88%;
			}
			#schedule div#schedule_container{
				width:97%;
			}
		}

		@media screen and (max-width: 950px){
			article#schedule{ top: 60px; }
			#schedule div#schedule_container, #schedule table, #about{
				min-width: initial;
				width: 400px;
			}
			#schedule table td, #schedule table input[type="submit"]{
				width: 100%;
			}
			#schedule table{
				position: relative;
				bottom: 60px;
			}
			#schedule div#datesList_container{ top: -164px; }
			#schedule table td:nth-child(2){
				top: 115px; left: 0;
			}
			#schedule table td:last-child{
				position: absolute;
				top: -236px; left: 0;
			}
			article#about{ top: 90px; margin-bottom: 300px; }
			article#about{ width: 100%; border-radius: 0px; }

		}

		/* at this point, change the stacking order songcircle schedule */
		@media screen and (max-width: 839px){
			article#banner{
				background-size: 140%;
				background-position: 12.5% 0%;
			}
			#schedule div#schedule_container{
				/*width: 80%; */
				min-width: 320px;
			}
			/*footer div.share-links{ left: 4.2%; }*/
		}

		@media screen and (max-width: 520px){
			#schedule div#schedule_container, #schedule table{
				width: 94%; margin: 0 auto;
			}
		}

	</style>
</head>
<body>
	<?php include("../includes/layout/header.php") ?>
	<main>
		<!-- start Main Banner -->
		<article id="banner">
			<img src="images/songcircle/songcircle_logo.png" alt="songcircle logo" width="367" height="82">
			<h1>
				Real songs. Real songwriters. <span class="bold">Real-time.</span>
			</h1>
			<h2>
				Workshop your song live in a virtual Songwriters' Circle. Get real-time feedback, collaborate with musicians, and perfect your craft. Register for a Songcircle today!
			</h2>
		</article>
		<!-- end of Main Banner -->
		<!-- start Schedule -->
		<article id="schedule">
			<h3 class="hide">Songcircle Schedule</h3>
			<div id="schedule_container">
				<div id="datesList_container">
					<p><i>Upcoming Songcircles in <span class="month">March</span></i></p>
					<p><span class="selected">11th /</span> 25th / 29th </p>
				</div>
				<table>
					<tbody>
						<tr>
							<td>
								Monday, <span class="month">March 23rd</span>, 2015 -<br />7:00pm UTC
							</td>
							<td>
								Songfarm <span class="event_name">Open Songcircle</span>
								<br>
								<span class="triggerParticipantsTable">(0 of 6 participants registered)</span>
								<img src="images/songcircle/green_arrow.png" alt="" height="22" width="19">
							</td>
							<td>
								<input type="submit" value="Register">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</article>
		<!-- end of Schedule -->
		<!-- start About Songcircle -->
		<article id="about">
			<h3>What is a <b>Songcircle?</b></h3>
			<hr>
			<p>
				A <a href="#linkToBlog" style="color:blue; text-decoration:underline;">Songcircle</a> is an <em>opportunity</em> for Songwriters to gather together, workshop their ideas, collaborate, and get real-time feedback from artists from all over the globe.
			</p>
			<p>Conducted over the internet in real-time, Songcircles are open to virtually anyone with an internet connection, a webcam, and a song.</p>
			<p>
				What traditionally had to be done in brick and mortar establishments is now being made possible by Songfarm as an alternative to songwriter's who may not have access to a songwriter's circle where they live; it's an exciting new way for songwriters to nurture their craft and grow their network - all without having to leave the house.
			</p>
			<p>
				Experience one for yourself. <a href="songcircle.php#headline" style="color:blue; text-decoration:underline;">Register for a Songcircle today!</a>
			</p>
		</article>
		<!-- end of About Songcircle -->
	</main>
	<?php include("../includes/layout/footer.php") ?>
</body>
</html>
