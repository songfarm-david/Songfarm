<?php

defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

/* Local site root */
defined('SITE_ROOT') 	? NULL : define('SITE_ROOT', DS.'wamp'.DS.'www'.DS.'Songfarm-Oct2015'); // 'songfarm'.DS.

/* TEST site root for test.songfarm.ca */
// defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'songfarm'.DS.'public_html'.DS.'test');

/* LIVE site root */
// defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'songfarm'.DS.'public_html');

/* default email for admininstrative notices */
defined("ADMIN_EMAIL") 	? NULL : define("ADMIN_EMAIL",'davidburkegaskin@gmail.com');

/* Oovoo App Token */
defined("APP_TOKEN") 		? NULL : define("APP_TOKEN",'MDAxMDAxAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADiaTDLn71m8RSXRS7BI3wOVosqd%2F%2BxHpClES%2B4DjkBpcNYjpNu%2FtZLacCpyqvEc9GWK5ON%2By0Sh3yFsInHKNteOYJXpE3O2vYia1SEPXKAZoLelwOulWL7Vt%2FRJEiweO%2Frwc6djcpog%2FhyhRIobGVt');

defined('CORE_PATH') 	? NULL : define('CORE_PATH' , SITE_ROOT.DS.'public');
defined('LIB_PATH') 	? NULL : define('LIB_PATH', SITE_ROOT.DS.'includes');
defined('CLASS_PATH') ? NULL : define('CLASS_PATH', LIB_PATH.DS.'class');
defined('IMAGE_PATH') ? NULL : define('IMAGE_PATH', SITE_ROOT.DS.'uploaded_images');
defined('EMAIL_PATH') ? NULL : define('EMAIL_PATH', SITE_ROOT.DS.'email');
defined('ERROR_PATH') ? NULL : define('ERROR_PATH', SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s"));

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

/* Load basic functions */
require_once(LIB_PATH.DS."functions.php");

?>
