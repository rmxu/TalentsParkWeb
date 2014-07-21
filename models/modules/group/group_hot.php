<?php
	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	//引入语言包
	$g_langpackage=new grouplp;

	//变量区
	$user_id=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));

	$group_hot_rs=array();
	$my_group=api_proxy("user_self_by_uid","join_group,creat_group",$user_id);
	$user_join_group=$my_group['join_group'];
	$user_creat_group=$my_group['creat_group'];

	//缓存功能区
	$group_hot_rs = api_proxy("group_self_by_memberCount","*");

	//数据显示控制
	$list_display="";
	$list_none="content_none";
	if(empty($group_hot_rs)){
		$list_display="content_none";
		$list_none="";
	}
?>
