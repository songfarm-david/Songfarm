<?php
// /* ADJUST THE HOUR IN THE TIME BELOW FOR TESTING PURPOSES */
// date_default_timezone_set('UTC');
// $date = new DateTime('2015-11-15T19:00:00', new DateTimeZone('UTC'));
// echo 'UTC Time: '.$date->format('l, F jS, Y \a\t g:i A').'<br>';
// echo '<hr>';
// // get the user time zone somehow, must be type 3...
// $user_timezone_london = new DateTimeZone('Europe/London');
// $user_timezone_toronto = new DateTimeZone('America/Toronto');
// $user_timezone_vancouver = new DateTimeZone('America/Vancouver');
// // instantiate new date object with UTC date of songcircle
// $dt = new DateTime($date_of_songcircle);
// $dt2 = new DateTime($date_of_songcircle);
// // set time zone for that date
// $date->setTimeZone($user_timezone_london);
// echo 'Time in London: '.$date->format('l, F jS, Y \a\t g:i A').' [ '.($user_timezone_london->getOffset($date)/60/60).' hour(s) ]<br>';
// $date->setTimeZone($user_timezone_toronto);
// echo 'Time in Toronto: '.$date->format('l, F jS, Y \a\t g:i A').' [ '.($user_timezone_toronto->getOffset($date)/60/60).' hour(s) ]<br>';
// $date->setTimeZone($user_timezone_vancouver);
// echo 'Time in Vancouver: '.$date->format('l, F jS, Y \a\t g:i A').' [ '.($user_timezone_vancouver->getOffset($date)/60/60).' hour(s) ]<br>';
?>
