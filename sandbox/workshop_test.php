<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Test Workshop</title>
	<script type="text/javascript" src="../public/js/jquery-1.11.3.min.js"></script>
	<style>
		html,body{
			height: 100%;
			margin:0; padding:0;
		}
		html{overflow-y: hidden;}
		header{
			width:822px; /* get percentage conversions? */
			height:170px;
			margin:94px auto 66px;
			background: blue;
		}
		div{
			width:100%;
			height:68px;
			border-bottom: 2px solid #ccc;
			background: #ddd;
		}
		ul{
			width:1096px;
			height:100%;
			margin:0 auto;
			padding:0;
			box-sizing: border-box;
			position: relative;
			z-index: 6;
		}
		li{
			display: inline-block;
			background: #eee;
			border: 2px solid #ccc;
			border-bottom: none;
			width:50%;
			margin:0; padding: 0;
			box-sizing: border-box;
			height: 100%;
			text-align: center;
			line-height: 60px;
			font-size: 1.85em;
		}
		li:last-child{
			border-left: none;
		}
		li a{
			display: block;
			height:inherit;
		}
		li a:hover{
			background: #ddd;
		}
		.active{
			height:103%;
		}
		.active a:hover{
			height:105%;
			background: none;
			cursor: default;
		}
		.hide{display: none;}
		section{
			background:#fff;
			height:100%;

		}
		section article{
			width:1096px;
			margin:0 auto;
		}
	</style>
</head>
<body>
	<header>
		<h1>Mock Header</h1>
	</header>

	<div>
		<ul>
			<li class="active">
				<a href="#" data-name="Songbook">Songbook</a>
			</li><li>
				<a href="#" data-name="Songcircle">Songcircle</a>
			</li>
		</ul>
	</div>
	<section>
		<article id="Songbook" class="songbook">
			<h2>Songbook</h2>
		</article>
		<article id="Songcircle" class="songcircle hide">
			<h2>Songcircle</h2>
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
</body>
