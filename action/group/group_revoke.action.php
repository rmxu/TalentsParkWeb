<?php
//引入语言包
	$g_langpackage=new grouplp;
	require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$userid=intval(get_argg('userid'));
	
//数据表定义
	$t_users=$tablePreStr."users";
	$t_groups=$tablePreStr."groups";
  $t_group_members=$tablePreStr."group_members";
  
//定义读操作
	dbtarget('r',$dbServs);	
	$dbo=new dbex();
	
//引入公共函数
	require("foundation/module_group.php");

//权限判断
	$role=pri_limit($dbo,$user_id,$group_id);

	if($role>=1){
		action_return(0,$g_langpackage->g_no_privilege,"-1");exit;
	}

	$group_row = api_proxy("group_self_by_gid","group_manager_name,group_manager_id,group_name",$group_id);

	$group_manager=$group_row['group_manager_name'];
	$group_manager_id=$group_row['group_manager_id'];

  $user_row=api_proxy("user_self_by_uid","user_name",$userid);
	$username=$user_row['user_name'];

//所撤销的管理员不存在
	if(!preg_match("/,$userid,/",$group_manager_id)){
		action_return(0,$g_langpackage->g_no_manager,"-1");
		}

	$group_manager=preg_replace("/\|$username\|/","|",$group_manager);
	$group_manager_id=preg_replace("/,$userid,/",",",$group_manager_id);
	
//定义写操作
	dbtarget('w',$dbServs);	
	
  $sql="update $t_groups set group_manager_name='$group_manager',group_manager_id='$group_manager_id' where group_id=$group_id";
  $dbo->exeUpdate($sql);
   
  $sql="update $t_group_members set role=2 where user_id=$userid&&group_id=$group_id";
  $dbo->exeUpdate($sql);
  
	$title=$g_langpackage->g_you_as.$group_row['group_name'].$g_langpackage->g_admin_revocation;
	$scrip_content=$g_langpackage->g_you_as.$group_row['group_name'].$g_langpackage->g_admin_revocation;
	$is_success=api_proxy('scrip_send',$g_langpackage->g_system_sends,$title,$scrip_content,$userid,0);
	if($is_success){
		api_proxy("message_set",$userid,"{num}".$g_langpackage->g_a_notice,"modules.php?app=msg_notice",0,1,"remind");
	}  
	
	$jump="modules.php?app=group_member_manager&group_id=$group_id";
	action_return(1,'',$jump);

?>

