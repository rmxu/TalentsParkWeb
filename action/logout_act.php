<?php
//会员退出
$dbo = new dbex;
dbtarget('r',$dbServs);
$t_online=$tablePreStr."online";
$user_id=get_sess_userid();
$sql="delete from $t_online where user_id=$user_id";
$dbo->exeUpdate($sql);

setcookie("IsReged",'');
session_destroy();
action_return(1,'','');
?>