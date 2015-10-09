/**
* Controls dropdown navigation menu on
* workshop.php & profile.php
*
*/

// user settings dropdown
$('ul#settings-trigger').on('mouseover', function(){
	$('#settings-drop-down').show();
});
$('ul#settings-drop-down li:first-child, ul#settings-trigger').on('mouseout', function(){
	$('#settings-drop-down').hide();
});
