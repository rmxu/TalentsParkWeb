<?php
//引入模块公共方法文件
require("foundation/module_users.php");
require("api/base_support.php");
//引入语言包
	$g_langpackage=new grouplp;

//变量区
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$userid=intval(get_argg('userid'));
	$type=short_check(get_argg('type'));

//数据表定义
  $t_group_members=$tablePreStr."group_members";
  $t_users=$tablePreStr."users";
  $t_groups=$tablePreStr."groups";

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//引入公共函数
	require("foundation/module_group.php");

//权限判断
	$role=pri_limit($dbo,$user_id,$group_id);
	if($role==2){
	action_return(0,"$g_langpackage->g_no_privilege","-1");
	}

//取得被批准组员的信息
$user_info=get_user_info($dbo,$t_users,$userid);
$user_join_group=$user_info['join_group'];
$user_join_group=empty($user_join_group)? ",".$group_id.",":$user_join_group.$group_id.",";

//取得群组信息
$group_info=api_proxy("group_self_by_gid","group_req_id,group_name,group_resume",$group_id);

//定义写操作
	dbtarget('w',$dbServs);

	$group_r_id=preg_replace("/,$userid,/",",",$group_info['group_req_id']);

	$sql="update $t_group_members set state=1 where group_id=$group_id&&user_id=$userid";
	$dbo->exeUpdate($sql);

  $sql="update $t_groups set member_count=member_count+1,group_req_id='$group_r_id' where group_id=$group_id";
  $dbo->exeUpdate($sql);

//更新用户表
	$sql="update $t_users set join_group='$user_join_group' where user_id=$userid";
	$dbo->exeUpdate($sql);

	//纪录新鲜事
	$title=$g_langpackage->g_joined_group.'<a href="home.php?h='.$user_id.'&app=group_space&group_id='.$group_id.'" target="_blank">'.$group_info['group_name'].'</a>';
	$content=$group_info['group_resume'];
	$is_suc=api_proxy("message_set",0,$title,$content,0,1,$userid);

	if(isset($type)&&$type=="remind"){
		$jump="modules.php?app=remind_group&group_id=".$group_id;
		action_return(1,"",$jump);
	}else{
		$jump="modules.php?app=group_member_manager&group_id=".$group_id;
		action_return(1,"",$jump);
	}

?>

