<?php
//引入语言包
$g_langpackage=new grouplp;
require("api/base_support.php");

//变量区
$group_id=intval(get_argg('group_id'));
$userid=intval(get_argg('userid'));
$creat_group=get_sess_cgroup();

//权限判断
if(!preg_match("/,$group_id,/",$creat_group)){
	action_return(0,$g_langpackage->g_no_privilege,"-1");
}

//数据表定义
	$t_users=$tablePreStr."users";
	$t_groups=$tablePreStr."groups";
  $t_group_members=$tablePreStr."group_members";

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//判断管理员是否已经为3个
	$manager_name=api_proxy("group_self_by_gid","group_manager_name,group_manager_id,group_name",$group_id);

  $group_manager_name=$manager_name['group_manager_name'];
  $group_manager_id=$manager_name['group_manager_id'];

  if(count(explode(",",$group_manager_id))>=5)
  {
	  	action_return(0,$g_langpackage->g_m_limit,"-1");
	}

//取得委任管理员的名字
  $user_name = api_proxy("user_self_by_uid","user_name",$userid);
  $user_name=$user_name['user_name'];

//定义写操作
  dbtarget('w',$dbServs);
  $group_manager_name=empty($group_manager_name)?"|".$user_name."|":$group_manager_name.$user_name."|";
  $group_manager_id=empty($group_manager_id)?",".$userid.",":$group_manager_id.$userid.",";

  $sql="update $t_groups set group_manager_name='$group_manager_name',group_manager_id='$group_manager_id' where group_id=$group_id";
  $dbo->exeUpdate($sql);

  $sql="update $t_group_members set role=1 where user_id=$userid&&group_id=$group_id";
  $dbo->exeUpdate($sql);

	$title=$g_langpackage->g_you_assigned.$manager_name['group_name'].$g_langpackage->g_group_administrator;
	$scrip_content=$g_langpackage->g_you_assigned.$manager_name['group_name'].$g_langpackage->g_group_administrator;
	$is_success=api_proxy('scrip_send',$g_langpackage->g_system_sends,$title,$scrip_content,$userid,0);
	if($is_success){
		api_proxy("message_set",$userid,"{num}".$g_langpackage->g_a_notice,"modules.php?app=msg_notice",0,1,"remind");
	}
  $jump="modules.php?app=group_member_manager&group_id=$group_id";
  action_return(1,'',$jump);
?>

