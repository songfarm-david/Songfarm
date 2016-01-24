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
		<title>Songfarm</title>
		<link href="../../public/css/index.css" rel="stylesheet" type="text/css">
		<style>
			body{
				background: rgba(0, 255, 0, 0.09);
				height: 100%; width: 100%;
				max-width: 100%;
				margin: 0;
			}
			div.confirmMsg{
				position: relative;
				top:50%; transform: translateY(-85%);
				width:50%; padding:1.5em 3em;
				margin: 0 auto;
				border:1px dotted rgba(56, 97, 56, 0.71);
				background: #fff;
				font-size: 1.8em;
				text-align: center;
				border-radius: 6px;
				box-shadow: -1px 1px 5px 1px;
			}
		</style>
	</head>
	<body>
		<div class="confirmMsg">
			<?php

				if( $error_msg && is_array($error_msg) ){
					foreach ($error_msg as $error) {
						echo $error . '<br>';
					}
				} else {
					if($success_msg){
						echo $success_msg;
					}
				}

			?>
		</div>
	</body>
</html>
