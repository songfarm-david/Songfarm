<?php require_once("../includes/initialize.php");
require_once(LIB_PATH.DS."login.php"); ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Songfarm nurtures music talent and cultivates songwriters' careers from the ground up!">
        <title>Songfarm - Growing Music Talent From The Ground Up</title>
        <!-- <link rel="shortcut icon" type="image/x-icon" href="images/songfarm_favicon.png" /> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta property="og:url" content="http://www.songfarm.ca">
        <meta property="og:title" content="Cultivating Music Talent From The Ground Up">
        <meta property="og:description" content="Songfarm is a feedback, exposure and live-collaboration platform for aspiring singer/songwriters. Upload your raw videos, receive feedback from the Songfarm Community of Artists, Industry Professionals and Fans and begin growing your career. Register Today!">
        <meta property="og:image" content="http://www.songfarm.ca/images/songfarm_logo_l.png">
        <meta property="og:image:width" content="1772">
        <meta property="og:image:height" content="1170">
        <!-- <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> -->
        <link href="css/index.css" rel="stylesheet" type="text/css">
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
        <style>
          span.farmedOut{
            font-style: italic;
          }
        </style>
    </head>
    <body id="top">
      <!-- Javascripts for handling social networking features -->
      <script type="text/javascript" src="js/social.js"></script>
      <!-- Logo and Navigation -->
      <?php include("../includes/layout/header.php") ?>
      <!-- Main Content -->
      <main>

        <!-- Banner Slide -->
        <article id="banner" class="slide-container">
          <h2 class="hide">Highlights</h2><!-- hide this, too -->
          <div class="slide-data">
            <section class="slide-panel" data-image="images/banner/slide_1.jpg" id="slide-1">
              <h3><span class="bold">Nurturing Talent</span><br> Harvesting Success</h3>
              <p>
                Songfarm is an organic growth, exposure, and collaboration platform for <br>aspiring and professional singer/songwriters.
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_4.jpg" id="slide-4">
              <h3><span class="bold">Organic Exposure</span><br> and Feedback</h3>
              <p>
                Songfarm is designed to expose your talents and provide feedback on your&nbsp;songs.
              </p>
              <p>
                Upload your video and start receiving feedback from the Songfarm Community of<br> Artists, Industry Professionals and Music Fans.
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_2.jpg" id="slide-2">
              <h3><span class="bold">Direct Networking</span><br> and Natural Growth</h3>
              <p>
                Connect directly to other Artists, Industry Professionals and Music Fans,<br> grow your fanbase, discover new opportunities, and let your career take root.
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_5.jpg" id="slide-5">
              <h3>Virtual<br><span class="bold">Songwriter's Circles</span></h3>
              <p>
                Workshop your newest song in a virtual songwriter's circle and get real-time<br> feedback from other artists.
                <strong><a href="songcircle.php" title="Songcircle - A virtual songwriter's circle">Register for one today!</a></strong>
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_6.jpg" id="slide-6">
              <h3><span class="bold">Live-Streaming</span><br>Concerts</h3>
              <p>
                Broadcast your talents to all your biggest fans and make live-streaming concerts<br> an essential part of your career growth.
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_3.jpg" id="slide-3">
              <h3><span class="bold">Transparent</span><br> Business Model</h3>
              <p>
                No third parties. No middle men.
              </p>
              <p>
                Songfarm Artists retain 100% of their earnings.
              </p>
            </section>
            <section class="slide-panel" data-image="images/banner/slide_7.jpg" id="slide-7">
              <h3><span class="bold">Cheap Enough Even</span><br> for the Starving Artist</h3>
              <p>
                Songfarm is free to use for Artists and Fans.
              </p>
              <p>
                Full Artist Membership costs only $2.99/mo. Cancel anytime.
              </p>
            </section>
          </div>
        </article>

        <!-- About -->
        <article id="about" >
          <div>
            <h2>About</h2>
            <p>
              Songfarm is a video-based exposure and live-collaboration platform for singer/songwriters. It is also a talent and music discovery site for Industry Professionals and Fans.
              With a focus on "campfire style" performances and live-streaming technology, Songfarm aims to create a more honest and authentic music experience over the web.
              No additives or by-products. Just pure music goodness.
            </p>
            <div class="register"></div>
            <!-- contains the 'Register Today' button -->
          </div>
        </article>

        <!-- Features -->
        <article id="features" >
          <h2>Features</h2>
          <div class="divide-one"></div>
          <div class="section first">
            <section class="feature">
              <img src="images/icons/songbook_icon.png">
              <h3>Songbook</h3>
              <p>
                Quickly and easily track, sort and organize all your finished and rough songs, lyrics, covers and co-writes, as well as analytics. All this so you can focus on more important things &ndash; like your music.
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/farmedOut_icon.png">
              <h3>Get Farmed Out</h3>
              <p>
                One of the goals of Songfarm is to help get your songs discovered and "Farmed Out" to music consumers and Industry professionals. When you do, you'll receive the <span class="farmedOut">Farmed Out Badge</span> publically visible on your profile and the freshly harvested song.
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/tipJar_icon.png">
              <h3>Tip Jar</h3>
              <p>
                Songfarm believes in supporting music talent. For that reason we've wired up the tip jar to feed direcly into your bank account so you'll receive instant deposits everytime a supporter donates to the cause.
              </p>
            </section>
            <div class="divide-two"></div>
          </div><!-- end of section first -->

          <div class="section second">
            <section class="feature">
              <img src="images/icons/collab_icon.png">
              <h3>Live Collaboration</h3>
              <p>
                Collaborate live in a virtual Songwriter's Circle and receive real-time feedback from other songwriters to help take your music to the next level. Join a live <strong><a href="songcircle.html" title="Participate in a Songcircle">Songcircle</a></strong> today!
                <!-- the strong tag represents a strong importance and is used to emphasize the Songcircle -->
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/campfire_icon.png">
              <h3>Campfire Style Performance</h3>
              <p>
                Songfarm's video performances capture authentic music talent. One guitar. One voice. As if you were listening around a campfire.
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/analytics_icon.png">
              <h3>Analytics</h3>
              <p>
                Discover your most popular songs, who's listening, and where in the world your biggest fans reside so you can make smarter decisions with your career.
              </p>
            </section>
            <div class="divide-three"></div>
          </div><!-- end of section-second -->

          <div class="section third">
            <section class="feature">
              <img src="images/icons/fairBus_icon.png">
              <h3>Fair Business Practice</h3>
              <p>
                Receive 100% of the money you make on Songfarm. Whether through live performances, downloads, donations or Industry deals, Songfarm Artists always get a fair shake.
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/liveConcert_icon.png">
              <h3>Live Concerts</h3>
              <p>
                Host a live concert to all your biggest fans from the comfort of your home and earn performance revenue without ever setting foot on a tour bus.
              </p>
            </section>
            <section class="feature">
              <img src="images/icons/community_icon.png">
              <h3>The Songfarm Community</h3>
              <p>
                Songfarm is home to aspiring singer/songwriters, music industry professionals and fans. You never know who you'll meet when you become part of the Songfarm Community.              </p>
            </section>
            <div class="divide-four"></div>
          </div><!-- end of section third -->
        </article>

        <!-- Contact Us -->
        <div class="rounded-contact"></div>
        <article id="contactUs" >
          <h2>Contact Us</h2>
          <?php include(LIB_PATH.DS."forms/contact_form.php"); ?>

          <!-- Footer -->
          <?php include(LIB_PATH.DS."layout/footer.php") ?>
        </article>

      </main>

      <!-- registration form -->
      <?php require_once(LIB_PATH.DS."forms/register.php"); ?>
      <!-- end of: registration form -->



      <!-- Javascripts -->
      <script type="text/javascript" src="js/jquery.validate.min.js"></script>
      <script type="text/javascript" src="js/slide-gallery.js"></script>
      <!-- // <script type="text/javascript" src="js/register_form.js"></script>
      // <script type="text/javascript" src="js/contact_form.js"></script> -->
      <script type="text/javascript" src="js/forms.js"></script>
      <script>
      // Picture element HTML5 shiv
      document.createElement( "picture" );
      </script>
      <script src="js/picturefill.min.js" async></script>
      <!-- banner preload script -->
      <script>
      //smooth scrolling function
      $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
          if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
              $('html,body').animate({
                scrollTop: target.offset().top
              }, 1000);
              return false;
            }
          }
        });
      });

      // scroll function to make back to top button appear
      $(window).on('scroll', function() {
          var y_scroll_pos = window.pageYOffset;
          var scroll_pos_test = 600;             // set to whatever you want it to be

          if(y_scroll_pos > scroll_pos_test) {
            // $("#back-to-top").css('display','block');
            $("#back-to-top").fadeIn();

          }else{
            // $("#back-to-top").css('display','none');
            $("#back-to-top").fadeOut();

          }
      });

      // facebook share trigger event
      $(".facebook").on('click',function(){
        FB.ui({
            method: 'share',
            href: 'songfarm.ca/',
        });
      });

      // twitter share trigger event
      $(".twitter").on('click',function(){
        // location.href='https://twitter.com/share';
        var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = "https://twitter.com/intent/tweet?url=http%3A%2F%2Fwww.songfarm.ca&text=Growing%20authentic%20music%20talent%20from%20the%20ground%20up!&hashtags=songfarmdotca",
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;
        window.open(url, 'twitter', opts);
        return false;
      });

      $(".linkedIn").on('click',function(){
        // location.href="https://www.linkedin.com/shareArticle?mini=true&url=http://songfarm.ca";
        var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = "https://www.linkedin.com/shareArticle?mini=true&url=http://songfarm.ca",
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;
        window.open(url, 'linkedIn', opts);
        return false;
      });

      </script>

      <a href="#top"><div id="back-to-top"></div></a>
    </body>
</html>
