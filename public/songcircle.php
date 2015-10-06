<?php session_start(); ?>
<?php require_once("../includes/initialize.php"); ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Songcircle - Virtual Songwriter's Circle</title>
        <meta name="description" content="Share your newest song in a live virtual songwriter's circle">
        <!-- a meta "description" can and should be included on each independent page to DESCRIBE it -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta property="og:url" content="http://www.songfarm.ca/songcircle.php">
        <meta property="og:title" content="Songcircle - Virtual Songwriter's Circle">
        <meta property="og:description" content="Participate in a virtual songwriter's circle and workshop your songs with other songwriters - all from the comfort of your home. Register Today!">
        <meta property="og:image" content="http://www.songfarm.ca/images/songfarm_logo_l.png">
        <meta property="og:image:width" content="1772">
        <meta property="og:image:height" content="1170">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link href="css/index.css" rel="stylesheet">
        <link href="css/songcircle.css" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="//platform.linkedin.com/in.js">
            api_key:   77fxwmu499ca9c
            authorize: true
        </script>
        <!--[if lt IE 9]>
          <script src="js/html5-shiv/html5shiv.min.js"></script>
          <script src="js/html5-shiv/html5shiv-printshiv.min.js"></script>
          <script src="js/respond.js" type="text/javascript"></script>
          <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <![end if]-->

    </head>
		<body>
      <?php include("../includes/layout/header.php") ?>

      <article id="songcircle">
        <h1>Songcircle</h1>
        <section>
          <h2>What is a Songcircle?</h2>
          <p>
            A Songcircle is a virtual songwriter's circle - a group of songwriters coming together, sharing their songs and ideas, inspiring and helping one another, and having a good time doing it - all done over the web.
          </p>
          <p>
            The <strong>Songcircle</strong> is one of Songfarm's trademark features. To participate in one, all you need is an internet connection, a webcam and a song.
          </p>
          <img src="images/buttons/register_m.png" class="register">
          <!-- <p>
            <span class="register">Register Today</span> and take part in the next Songcircle!
          </p> -->
        </section>
        <aside id="schedule">
          <h3>Scheduled Songcircles:</h3>
          <p>
            None scheduled currently.
          </p>
        </aside>
      </article>
			<!-- Use RDFa to define songcircle events // schema.org -->

      <?php include("../includes/layout/footer.php") ?>
      <?php require_once(LIB_PATH.DS."forms/register.php"); ?>
      <script type="text/javascript" src="js/social.js"></script>
      <script type="text/javascript" src="js/jquery.validate.min.js"></script>
      <script type="text/javascript" src="js/forms.js"></script>

      <!-- remember to use the '<time></time>' attribute when scheduling songcircles-->

		</body>
</html>
