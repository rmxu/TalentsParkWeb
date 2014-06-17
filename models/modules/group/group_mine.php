<?php
	//引入语言包
	$g_langpackage=new grouplp;

	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	require("foundation/module_users.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	$group_rs=array();

	//按钮控制
	$button=0;
	$button_show="content_none";
	$button_hidden="";

	if($is_self=='Y'){
		$group_title=$g_langpackage->g_mine;
		$no_data=$g_langpackage->g_none_group;
		$button=1;
		$button_show="";
		$button_hidden="content_none";
		$show_mine="";
		$show_his="content_none";
	}else{
		$show_mine="content_none";
		$show_his="";
		$holder_name=get_hodler_name($url_uid);
		$group_title=str_replace("{holder}",$holder_name,$g_langpackage->g_his_group);
		$no_data=$g_langpackage->g_none;
	}

	$group_rs = api_proxy("group_self_by_uid","*",$userid,'getRs');

	//数据显示控制
	$list_show="";
	$list_hidden="content_none";
	if(empty($group_rs)){
		$list_show="content_none";
		$list_hidden="";
	}

	$g_join_num=$g_langpackage->g_join_num;
?>
