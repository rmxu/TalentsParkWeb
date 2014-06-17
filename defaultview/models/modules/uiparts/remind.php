<?php
//引入语言包
$rem_langpackage=new remindlp;

//引入提醒模块公共函数
require("foundation/module_remind.php");
require("foundation/fdelay.php");
require("api/base_support.php");

//表定义区
$t_online=$tablePreStr."online";
$isset_data="";
$DELAY_ONLINE=4;
$is_action=delay($DELAY_ONLINE);
if($is_action){
	$dbo=new dbex;
	dbtarget('w',$dbServs);
	update_online_time($dbo,$t_online);
	rewrite_delay();
}
$remind_rs=api_proxy("message_get","remind");
if(empty($remind_rs)){
	$isset_data="content_none";
}
?>