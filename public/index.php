<?php require_once("../includes/initialize.php"); ?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="description" content="Songfarm nurtures music talent and cultivates songwriters' careers from the ground up!">
      <title>Songfarm - Grow Your Music</title>
      <!-- <link rel="shortcut icon" type="image/x-icon" href="images/songfarm_favicon.png" /> -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
      <meta property="og:url" content="http://www.songfarm.ca">
      <meta property="og:title" content="Cultivating Music Talent From The Ground Up">
      <meta property="og:description" content="Songfarm is a feedback, exposure and live-collaboration platform for aspiring singer/songwriters. Upload your raw videos, receive feedback from the Songfarm Community of Artists, Industry Professionals and Fans and begin growing your career. Register Today!">
      <meta property="og:image" content="http://www.songfarm.ca/images/songfarm_logo_l.png">
      <meta property="og:image:width" content="1772">
      <meta property="og:image:height" content="1170">
      <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
      <!-- CSS files -->
      <link type="text/css" rel="stylesheet" href="css/global_no_login.css">
      <link type="text/css" rel="stylesheet" href="css/index.css" >
      <!-- Javascripts -->
      <script src="js/jquery-1.11.3.min.js"></script><!-- fetch this off of Google's CDN -->
      <script src="js/jquery.validate.min.js"></script><!-- this one, too, if possible -->
      <script src="//platform.linkedin.com/in.js">
          // api_key:   77fxwmu499ca9c
          // authorize: true
      </script>
      <!--[if lt IE 9]>
        <script src="js/html5-shiv/html5shiv.min.js"></script>
        <script src="js/html5-shiv/html5shiv-printshiv.min.js"></script>
        <script src="js/respond.js"></script>
        <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
      <![end if]-->
  </head>
  <body id="page-top">
    <header id="header">
      <h1 class="hide">Songfarm Home Page</h1>
      <!-- Logo and Navigation -->
      <?php include("../includes/layout/navigation.php") ?>
      <!-- 'Register Now' button -->
      <button class="register medium" value="Register Today">Register Today</button>
    </header>
    <!-- Main Content -->
    <main>
      <!-- Banner Slide -->
      <section id="banner" class="slide-container">
        <h2 class="hide">Highlights</h2>
        <div class="slide-data">
          <article class="slide-panel" data-image="images/banner/slide_1" id="slide-1">
            <h3><span class="bold">Nurturing Talent</span><br> Harvesting Success</h3>
            <p id="caption-one">
              Songfarm is an organic growth, exposure, and collaboration platform for aspiring and professional singer/songwriters.
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_4" id="slide-4">
            <h3><span class="bold">Organic Exposure</span><br> and Feedback</h3>
            <p>
              Songfarm is designed to nurture your songwriting.
            </p>
            <p class="last-p-element">
              Upload your songs and get constructive criticism from the Songfarm Community of Artists, Industry Professionals and Music Fans.
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_2" id="slide-2">
            <h3><span class="bold">Direct Networking</span><br> and Natural Growth</h3>
            <p>
              Connect directly to other Artists, Industry Professionals and Music Fans.
            </p>
            <p class="last-p-element">
              Grow your fanbase, discover new opportunities, and let your career take root.
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_5" id="slide-5">
            <h3>Virtual<br><span class="bold">Songwriter's Circles</span></h3>
            <p>
              Workshop your songs in a virtual songwriter's circle and get real-time feedback from other artists.
              <strong><a href="songcircle.php" title="Songcircle - A virtual songwriter's circle">Register for one today!</a></strong>
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_6" id="slide-6">
            <h3><span class="bold">Live-Streaming</span><br>Concerts</h3>
            <p>
              Broadcast in real-time to all your biggest fans and make live-streaming concerts an essential part of your career growth.
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_3" id="slide-3">
            <h3><span class="bold">Transparent</span><br> Business Model</h3>
            <p>
              No third parties. No middle men.
            </p>
            <p class="last-p-element">
              Songfarm Artists retain 100% of their earnings.
            </p>
          </article>
          <article class="slide-panel" data-image="images/banner/slide_7" id="slide-7">
            <h3 id="caption-seven"><span class="bold">Cheap Enough Even</span><br> for the Starving Artist</h3>
            <p>
              Songfarm is free to use for Artists and Fans.
            </p>
            <p class="last-p-element">
              Full Artist Membership costs only $2.99/mo. Cancel anytime.
            </p>
          </article>
        </div>
      </section>

      <!-- About -->
      <article id="about">
        <div>
          <h2>About</h2>
          <p>
            Songfarm is a video-based exposure and live-collaboration platform for Songwriters. It is also a talent-sourcing and music discovery site for Industry professionals and Fans.
            With a focus on true performance talent and emerging live-streaming technology, Songfarm creates a more honest and authentic approach to experiencing great music on the internet.
            No additives or by-products. Just pure music goodness.
          </p>
          <button class="register medium">Register Today</button>
        </div>
      </article>

      <!-- Features -->
      <section id="features">
        <h2>Features</h2>
        <div class="divide-one"></div>
        <div class="section first">
          <article class="feature">
            <img src="images/icons/songbook_icon.png" title="Songbook" alt="">
            <h3 tabindex="0">Songbook</h3>
            <p>
              Quickly and easily track, sort and organize all your finished and rough songs, lyrics, covers and co-writes, as well as analytics. All this so you can focus on more important things &ndash; like your music.
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/farmedOut_icon.png" title="Farmed Out" alt="">
            <h3>Get Farmed Out</h3>
            <p>
              One of the goals of Songfarm is to help get your songs discovered and "Farmed Out" to music consumers and Industry professionals. When you do, you'll receive the <span class="farmedOut">Farmed Out Badge</span> publically visible on your profile and the freshly harvested song.
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/tipJar_icon.png" title="Tip Jar" alt="">
            <h3>Tip Jar</h3>
            <p>
              Songfarm believes in supporting music talent. For that reason we've wired up the tip jar to feed direcly into your bank account so you'll receive instant deposits everytime a supporter donates to the cause.
            </p>
          </article>
          <div class="divide-two"></div>
        </div><!-- end of section first -->

        <div class="section second">
          <article class="feature">
            <img src="images/icons/collab_icon.png" title="Live Collaboration" alt="">
            <h3>Live Collaboration</h3>
            <p>
              Collaborate live in a virtual Songwriter's Circle and receive real-time feedback from other songwriters to help take your music to the next level. Join a live <strong><a href="songcircle.html" title="Participate in a Songcircle" tabindex="-1">Songcircle</a></strong> today!
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/campfire_icon.png" title="Campfire Style" alt="">
            <h3>Campfire Style Performance</h3>
            <p>
              Songfarm's video performances capture authentic music talent. One guitar. One voice. As if you were listening around a campfire.
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/analytics_icon.png" title="Analytics" alt="">
            <h3>Analytics</h3>
            <p>
              Discover your most popular songs, who's listening, and where in the world your biggest fans reside so you can make smarter decisions with your career.
            </p>
          </article>
          <div class="divide-three"></div>
        </div><!-- end of section-second -->

        <div class="section third">
          <article class="feature">
            <img src="images/icons/fairBus_icon.png" title="Fair Business Practice" alt="">
            <h3>Fair Business Practice</h3>
            <p>
              Receive 100% of the money you make on Songfarm. Whether through live performances, downloads, donations or Industry deals, Songfarm Artists always get a fair shake.
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/liveConcert_icon.png" title="Live Concerts" alt="">
            <h3>Live Concerts</h3>
            <p>
              Host a live concert to all your biggest fans from the comfort of your home and earn performance revenue without ever having to set foot on a tour bus.
            </p>
          </article>
          <article class="feature">
            <img src="images/icons/community_icon.png" class="rule" title="The Songfarm Community" alt="">
            <h3>The Songfarm Community</h3>
            <p>
              Songfarm is home to aspiring singer/songwriters, music industry professionals and fans. You never know who you'll meet when you become part of the Songfarm Community.              </p>
          </article>
          <div class="divide-four"></div>
        </div><!-- end of section third -->
      </section>

      <!-- Contact Us -->
      <section id="contactUs" >
        <div class="rounded-contact"></div>
        <h2>Contact Us</h2>
        <?php include(LIB_PATH.DS."forms/contact_form.php"); ?>
      </section>

    </main>
    <!-- Footer -->
    <?php include(LIB_PATH.DS."layout/footer.php") ?>
    <a href="#page-top" title="Back to the top of the page"><div id="back-to-top"></div></a>
    <!-- Registration Form -->
    <?php require_once(LIB_PATH.DS."forms/register.php"); ?>
    <!-- Javascripts
    NOTE: which scripts are currently active???
    -->
    <script src="js/slide-gallery.js"></script>
    <script src="js/register_form.js"></script>
    <!-- NOTE: program contact form errors to not show on load -->
    <script src="js/contact_form.js"></script>
    <script src="js/forms.js"></script>
    <script src="js/login.js"></script>
    <script>
    // Picture element HTML5 shiv
    document.createElement( "picture" );
    </script>
    <!-- What does this do???
    <script src="js/picturefill.min.js" async></script>
    -->
    <script>

    /* mobile-size feature panel script */
    // declare global variables to hold feature headers and feature panels
    var featureHeaders;
    var featurePanels;
    var panelHeight = [];
    var currentHeight = 64;
    var intervalHandle;

    // Animate height
    function animateHeight(i, panelHeight){
      // when link is clicked, animate height to full panel height
      currentHeight += 10;
      featurePanels[i].style.height = currentHeight + "px";
      // clear interval if panel height is reached
      if( currentHeight > panelHeight ){
        // reset currentHeight to initial value
        currentHeight = 64;
        clearInterval(intervalHandle);
      }
    }

    //Display active panel, hides content of others
    function displayPanel(tabToActivate){
      // loop through all the headers
      for (var i = 0; i < featureHeaders.length; i++) {
        // if header is the same as the one clicked
        if(featureHeaders[i] == tabToActivate){
          // call setInterval
          intervalHandle = setInterval(animateHeight, 0, i, panelHeight[i]);
        } else {
          // set height to show header only and hide panel
          featurePanels[i].style.height = "64px";
        }
      }
    }

    // get height of panels only if screen width is less than 900px
    function checkScreenWidth(){
      // get width of screen wherever it is
      var screenWidth = window.innerWidth;
      if( screenWidth < 900 ){
        return true;
      }
    }

    // get panel height of each feature section
    function getPanelHeight(){
      for (var i = 0; i < featurePanels.length; i++) {
        panelHeight.push(featurePanels[i].clientHeight);
      }
    }

    window.onload = function(){
      // get all the h3s
      featureHeaders = document.getElementById("features").getElementsByTagName("h3");
      // get all the panels;
      featurePanels = document.getElementById("features").getElementsByClassName("feature");
      // if screen width is less than 900px
      if( checkScreenWidth() ){
        getPanelHeight()
        // activate the first header
        displayPanel(featureHeaders[0]);
      }
      // attach event listener for onclick and onfocus event for each header
      for (var i = 0; i < featureHeaders.length; i++) {
        // set a tabindex of 0 on each of the headers
        featureHeaders[i].setAttribute("tabindex", 0);
        // set onclick event on headers
        featureHeaders[i].onclick = function(e){
          /**
          * NOTE: if two events are fired before the first one has completed, you get an error
          */
          // if current height is greater than original size, cancel function call
          if(currentHeight > 64){
            return false;
          } else {
            displayPanel(this);
          }
        };
        // if the header is focused on
        featureHeaders[i].onfocus = function(e){
          // see event function above
          if(currentHeight > 64){
            return false;
          } else {
            displayPanel(this);
          }
        };
      }
    };

    window.onresize = function(){
      if(checkScreenWidth()){
        // if panelHeight is already set, return false
        if( panelHeight.length != 0 ){
          return false;
        } else {
          getPanelHeight();
          // activate the first header
          displayPanel(featureHeaders[0]);
        }

      }
    }


    //smooth scrolling function
    // $(function() {
    //   $('a[href*=#]:not([href=#])').click(function() {
    //     if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
    //       var target = $(this.hash);
    //       target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
    //       if (target.length) {
    //         $('html,body').animate({
    //           scrollTop: target.offset().top
    //         }, 1000);
    //         return false;
    //       }
    //     }
    //   });
    // });
    //
    // // scroll function to make back to top button appear
    // $(window).on('scroll', function() {
    //     var y_scroll_pos = window.pageYOffset;
    //     var scroll_pos_test = 600;             // set to whatever you want it to be
    //
    //     if(y_scroll_pos > scroll_pos_test) {
    //       // $("#back-to-top").css('display','block');
    //       $("#back-to-top").fadeIn();
    //     }else{
    //       // $("#back-to-top").css('display','none');
    //       $("#back-to-top").fadeOut();
    //     }
    // });
    //
    // // facebook share trigger event
    // $(".facebook").on('click',function(){
    //   FB.ui({
    //       method: 'share',
    //       href: 'songfarm.ca/',
    //   });
    // });
    //
    // // twitter share trigger event
    // $(".twitter").on('click',function(){
    //   // location.href='https://twitter.com/share';
    //   var width  = 575,
    //   height = 400,
    //   left   = ($(window).width()  - width)  / 2,
    //   top    = ($(window).height() - height) / 2,
    //   url    = "https://twitter.com/intent/tweet?url=http%3A%2F%2Fwww.songfarm.ca&text=Growing%20authentic%20music%20talent%20from%20the%20ground%20up!&hashtags=songfarmdotca",
    //   opts   = 'status=1' +
    //            ',width='  + width  +
    //            ',height=' + height +
    //            ',top='    + top    +
    //            ',left='   + left;
    //   window.open(url, 'twitter', opts);
    //   return false;
    // });
    //
    // $(".linkedIn").on('click',function(){
    //   // location.href="https://www.linkedin.com/shareArticle?mini=true&url=http://songfarm.ca";
    //   var width  = 575,
    //   height = 400,
    //   left   = ($(window).width()  - width)  / 2,
    //   top    = ($(window).height() - height) / 2,
    //   url    = "https://www.linkedin.com/shareArticle?mini=true&url=http://songfarm.ca",
    //   opts   = 'status=1' +
    //            ',width='  + width  +
    //            ',height=' + height +
    //            ',top='    + top    +
    //            ',left='   + left;
    //   window.open(url, 'linkedIn', opts);
    //   return false;
    // });

    </script>
    <!-- Javascripts for handling social networking features -->
    <!-- <script src="js/social.js"></script> -->
  </body>
</html>
