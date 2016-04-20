<?php

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

/* Local site root */
defined('SITE_ROOT') 	? NULL : define('SITE_ROOT', DS.'wamp'.DS.'www'.DS.'Songfarm-Oct2015'); // 'songfarm'.DS.

/* TEST site root for test.songfarm.ca */
// defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'songfarm'.DS.'public_html'.DS.'test');

/* LIVE site root */
// defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'songfarm'.DS.'public_html');

defined('CORE_PATH') 	? NULL : define('CORE_PATH' , SITE_ROOT.DS.'public');
defined('LIB_PATH') 	? NULL : define('LIB_PATH', SITE_ROOT.DS.'includes');
defined('CLASS_PATH') ? NULL : define('CLASS_PATH', LIB_PATH.DS.'class');
defined('IMAGE_PATH') ? NULL : define('IMAGE_PATH', SITE_ROOT.DS.'uploaded_images');
defined('EMAIL_PATH') ? NULL : define('EMAIL_PATH', SITE_ROOT.DS.'email');

/* Load database config file first */
require_once(LIB_PATH.DS."config.php");

/* Init Classes */
require_once(CLASS_PATH.DS."database.php");
require_once(CLASS_PATH.DS."validateLogin.php");
require_once(CLASS_PATH.DS."session.php");
require_once(CLASS_PATH.DS."user.php");
require_once(CLASS_PATH.DS."image.php");
require_once(CLASS_PATH.DS."songbook.php");
require_once(CLASS_PATH.DS."songcircle.php");
require_once(CLASS_PATH.DS."message.php");
// require_once(LIB_PATH.DS."login.php");

/* Load basic functions */
require_once(LIB_PATH.DS."functions.php");

/**
* NOTE: include this on a per need basis ??
*/
// include_once(EMAIL_PATH.DS.'email_data.php');

?>
