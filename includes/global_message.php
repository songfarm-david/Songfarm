<?php if(isset(Message::$message)){	echo "<div class=\"global_msg\">".Message::$message."</div>";	} ?>
<script>
// code for displaying songcircle message
var div = $('div.global_msg').html();
if(div){
	var div = $('div.global_msg');
	div.delay('4000').fadeOut('500');
}
</script>
