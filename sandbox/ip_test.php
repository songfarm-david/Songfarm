<?php

$ip = '255.255.255.255';


if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)){
	echo 'is valid IP';
} else {
	echo 'is NOT valid IP';
}

?>
