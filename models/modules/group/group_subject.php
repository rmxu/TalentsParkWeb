<?php 
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//限制时间段访问站点
	limit_time($limit_action_time);
	
	//引入模块公共方法文件
	require("foundation/module_album.php");
	require("foundation/module_group.php");
	
	//引入语言包
	$g_langpackage=new grouplp;
	
	//变量区
	$user_id=get_sess_userid();
	$join_group=get_sess_jgroup();
	$group_id=intval(get_argg('group_id'));
	$creat_group=get_sess_cgroup();
	$u_id=intval(get_argg('user_id'));
	$role='';
	$album_id='';
	
	//权限判断
	$role=api_proxy("group_member_by_role",$group_id,$user_id);
	$role=$role[0];
	if(!isset($role)){
		echo "<script type='text/javascript'>alert(\"$g_langpackage->g_no_privilege\");window.history.go(-1);</script>";exit();
	}
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
?>
