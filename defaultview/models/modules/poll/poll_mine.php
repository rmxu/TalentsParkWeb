<?php
	//引入公共模块
	require("foundation/module_poll.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("foundation/module_users.php");
	
	//引入语言包
	$pol_langpackage=new polllp;
	
	//变量声明区
	$ses_uid=get_sess_userid();
	$url_uid=intval(get_argg('user_id'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	$poll_rs=array();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));

	$poll_rs=api_proxy("poll_self_by_uid","*",$userid);
	$title_str=$pol_langpackage->pol_mine;

	if($is_self=='N'){
		$holder_name=get_hodler_name($url_uid);
		$title_str=str_replace("{name}",$holder_name,$pol_langpackage->pol_who_page);
	}	

	//数据显示
	$none_data="content_none";	
	$isNull=0;
	if(empty($poll_rs)){
		$isNull=1;
		$none_data="";
	}
?>