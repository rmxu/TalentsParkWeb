<?php
//引入语言包
	$g_langpackage=new grouplp;

//引入公共函数
	require("foundation/module_users.php");
	require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$userid=intval(get_argg('userid'));

//引入公共函数
	require("foundation/module_group.php");

//数据表定义
	$t_groups=$tablePreStr."groups";
  $t_group_members=$tablePreStr."group_members";
  $t_group_subject=$tablePreStr."group_subject";
  $t_users=$tablePreStr."users";

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//权限判断
	$role=pri_limit($dbo,$user_id,$group_id);
	if($role==2){
	action_return(0,"$g_langpackage->g_no_privilege","-1");
	}

	$user_info = api_proxy("user_self_by_uid","join_group",$userid);
	$user_join_group=$user_info['join_group'];
	$user_join_group=preg_replace("/,$group_id,/",",",$user_join_group);

//定义写操作
	dbtarget('w',$dbServs);

  $sql="delete from $t_group_members where user_id=$userid&&group_id=$group_id";
  $dbo->exeUpdate($sql);

  $sql="update $t_groups set member_count=member_count-1 where group_id=$group_id";
  $dbo->exeUpdate($sql);

  $sql="update $t_users set join_group='$user_join_group' where user_id=$userid";
  $dbo->exeUpdate($sql);

	$jump="modules.php?app=group_member_manager&group_id=$group_id";
	action_return(1,"",$jump);
?>

