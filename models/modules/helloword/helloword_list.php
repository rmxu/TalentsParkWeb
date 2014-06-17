<?php
//数据表定义区
$ses_uid=get_sess_userid();
$is_self_mode='partLimit';
require("foundation/auser_validate.php");
require("foundation/fpages_bar.php");
//数据表定义区
$t_hello = $tablePreStr."helloWord";
$dbo=new dbex();
//读写分离定义方法
dbtarget('r',$dbServs);
$sql="select * from $t_hello";
$hello_list= $dbo->getRs($sql);
?>