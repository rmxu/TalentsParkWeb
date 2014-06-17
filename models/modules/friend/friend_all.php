<?php
	//引入语言包
	$f_langpackage=new friendlp;
	
	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	
	//当前页面参数
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//数据表定义区
	$t_users=$tablePreStr."users";

	$mypals_rs = api_proxy("pals_self_by_uid","*",$userid);
	
	if($is_self=='Y'){
		$friend_title=$f_langpackage->f_friend;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$friend_title=str_replace("{holder}",$holder_name,$f_langpackage->f_visitor);
	}
	
	$text_no_friend="content_none";
	$text_friend="";
	$isNull=0;
	if(empty($mypals_rs)){
		$isNull=1;
		$text_no_friend="";
		$text_friend="content_none";
	}
?>