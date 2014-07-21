<?php
//积分规则
function increase_integral($dbo,$integral,$user_id){
	global $tablePreStr;
	$t_users=$tablePreStr."users";
	$integral=intval($integral);
	$sql="update $t_users set integral = integral + $integral where user_id=$user_id";
	$dbo->exeUpdate($sql);
}
?>