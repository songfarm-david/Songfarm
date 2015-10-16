<?php

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

/* Site root for test.songfarm.ca */
// defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'songfarm'.DS.'public_html'.DS.'test');

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'wamp'.DS.'www'.DS.'Songfarm'.DS.'Songfarm-Oct2015');
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT.DS.'includes');
defined('CORE_PATH') ? NULL : define('CORE_PATH' , SITE_ROOT.DS.'public');
defined('IMAGE_PATH') ? NULL : define('IMAGE_PATH', SITE_ROOT.DS.'uploaded_images');

// Load database config file first
require_once(LIB_PATH.DS."config.php");
// Load classes
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."validateLogin.php");
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."user.php");
require_once(LIB_PATH.DS."image.php");
require_once(LIB_PATH.DS."songbook.php");
require_once(LIB_PATH.DS."songcircle.php");
// Load basic functions
require_once(LIB_PATH.DS."functions.php");

?>
