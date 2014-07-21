<?php
//引入语言包
$g_langpackage=new grouplp;

require("foundation/module_affair.php");
require("api/base_support.php");

//变量区
$user_id=get_sess_userid();
$group_id=short_check(get_argg('group_id'));
$creat_group=get_sess_cgroup();
$join_group=get_sess_jgroup();

//权限判断
if(!preg_match("/,$group_id,/",$creat_group)){
	action_return(0,"$g_langpackage->g_no_privilege","-1");
}

//数据表定义
	$t_users=$tablePreStr."users";
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";
	$t_group_subject=$tablePreStr."group_subject";
	$t_group_subject_comment=$tablePreStr."group_subject_comment";

//读取方式
	dbtarget('r',$dbServs);
  $dbo=new dbex;

//读取用户信息
	$my_creat_group=api_proxy("user_self_by_uid","creat_group",$user_id);
	$my_c_group=preg_replace("/,$group_id,/",",",$my_creat_group['creat_group']);

//读取群组数据
  $group_data = api_proxy("group_self_by_gid","group_logo",$group_id);

//卸载群组logo
	@unlink($group_data['group_logo']);

//写入方式
	dbtarget('w',$dbServs);

//更新用户表
	$sql="update $t_users set creat_group='$my_c_group' where user_id=$user_id";
	$dbo->exeUpdate($sql);

//卸载群组表
	$sql="delete from $t_groups where group_id=$group_id";
	$dbo->exeUpdate($sql);

//卸载群组会员表
  $sql="delete from $t_group_members where group_id=$group_id";
  $dbo->exeUpdate($sql);

//卸载群组主题标
  $sql="delete from $t_group_subject where group_id=$group_id";
  $dbo->exeUpdate($sql);

//卸载群组评论表
  $sql="delete from $t_group_subject_comment where group_id=$group_id";
  $dbo->exeUpdate($sql);

//更新session
set_sess_cgroup($my_c_group);
del_affair($dbo,1,$group_id);
action_return(1,'',"");
?>