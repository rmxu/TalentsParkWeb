<?php
	//引入语言包
	$mo_langpackage=new moodlp;
	
	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$userico=get_sess_userico();
	$pals_id=get_sess_mypals();
	
	//引入模块公共权限过程文件
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//数据表定义区
	$t_mood = $tablePreStr."mood";
	$t_users = $tablePreStr."users";
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	$mood_rs=api_proxy("mood_self_by_uid","*","$pals_id");

	//分页显示
	$isNull=0;
	$data_none='content_none';
	$show_data='';	
	if(empty($mood_rs)){
		$isNull=1;
		$data_none='';
		$show_data='content_none';
	}
?>