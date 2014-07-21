<?php
	//引入语言包
	$g_langpackage=new grouplp;
	
	//引入公共模块
	require("foundation/module_group.php");
	require("api/base_support.php");
	
	$role='';
	$user_id=get_sess_userid();
	$group_id=intval(get_argg('group_id'));

	$text_join_type='';
	
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)||$role>=2){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}
	
	$group_row=api_proxy("group_self_by_gid","*",$group_id);
	
	//群组加入方式预订
  $text_join_type=join_type($group_row['group_join_type']);

?>
