<?php
if(isset($_POST['submit'])){
	var_dump($_POST);
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SongTag</title>
</head>
<body>
	<h1>SongTag</h1>
	<!-- Songtab Form -->
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<p>
			Original Song or Cover?
		</p>
		<input type="radio" name="type" value="0">Original
		<input type="radio" name="type" value="1">Cover
		<p>
			Name of Song
		</p>
		<input type="text" name="song_name" maxlength="250">
		<p>
			Original Artist
		</p>
		<input type="text" name="cover_artist" maxlength="100">
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
		<input type="submit" name="submit" value="Submit SongTag">
	</form>
</body>
</html>
