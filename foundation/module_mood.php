<?php
function get_last_mood($dbo,$t_mood,$user_id){
	$sql = "select mood,add_time from $t_mood where user_id=$user_id order by add_time desc limit 1";
	return $dbo->getRow($sql);
}
?>