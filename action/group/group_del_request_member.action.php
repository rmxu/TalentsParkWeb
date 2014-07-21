<?php
//引入语言包
	$g_langpackage=new grouplp;
	require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));
	$userid=intval(get_argg('userid'));
	$type=short_check(get_argg('type'));

//定义读取
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//数据表定义
	$t_groups=$tablePreStr."groups";
  $t_group_members=$tablePreStr."group_members";

//引入公共函数
	require("foundation/module_group.php");

//权限判断
	$role=pri_limit($dbo,$user_id,$group_id);

	if($role==2){
	action_return(0,$g_langpackage->g_no_privilege,"-1");
	}

	$group_info=api_proxy("group_self_by_gid","group_req_id",$group_id);
	$group_r_id=preg_replace("/,$userid,/",",",$group_info['group_req_id']);

//定义写操作
	dbtarget('w',$dbServs);
	$sql="update $t_groups set group_req_id='$group_r_id' where group_id=$group_id";
  $dbo->exeUpdate($sql);

	$sql="delete from $t_group_members where group_id=$group_id&&user_id=$userid";
	$dbo->exeUpdate($sql);

	//刷新提醒页面
        echo "<script type='text/javascript'>
  parent.frames['remind'].location.reload();
  </script>";

	$jump="modules.php?app=group_member_manager&group_id=".$group_id;
	if(isset($type)&&$type=="remind"){
		$jump="modules.php?app=remind_group&group_id=".$group_id;
	}
	action_return(1,"",$jump);
?>

