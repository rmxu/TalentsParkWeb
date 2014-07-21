<?php
//引入语言包
$g_langpackage=new grouplp;

require("api/base_support.php");
//变量区
	$user_id=get_sess_userid();
	$group_id=short_check(get_argg('group_id'));
	$dbo=new dbex;

//数据表定义
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";
	$t_users=$tablePreStr."users";

//取得用户加入的群组
	$join_group_array=api_proxy("user_self_by_uid","join_group",$user_id);
	$user_join_group=preg_replace("/,$group_id,/",",",$join_group_array['join_group']);

//写方式
  dbtarget('w',$dbServs);

//更新用户表
	$sql="update $t_users set join_group='$user_join_group' where user_id=$user_id";
	$dbo->exeUpdate($sql);

//更新群组人数
  $sql="update $t_groups set member_count=member_count-1 where group_id=$group_id";
  $dbo->exeUpdate($sql);

//删除群组关系表
  $sql="delete from $t_group_members where group_id=$group_id && user_id=$user_id";
  $dbo->exeUpdate($sql);

//更新session
	set_sess_jgroup($user_join_group);
	action_return(1,'',"");
?>