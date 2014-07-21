<?php
//引入提醒模块公共函数
require("foundation/module_remind.php");
require("foundation/fdelay.php");

//数据表定义区
$t_online=$tablePreStr."online";
$DELAY_ONLINE=4;

//更新当前用户时间
$is_action=delay($DELAY_ONLINE);
if($is_action){
	$dbo=new dbex;
	dbtarget('w',$dbServs);
	update_online_time($dbo,$t_online);
	rewrite_delay();
}
?>