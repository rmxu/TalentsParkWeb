<?php
	//引入语言包
	$f_langpackage=new friendlp;

	//引入公共模块
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	
	//加为好友 打招呼
	$show_add_friend="";
	$add_friend="mypalsAddInit";
	$send_hi="hi_action";
	if(!$ses_uid){
		$add_friend='goLogin';
		$send_hi='goLogin';
	}

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	if($is_self=='Y'){
		$show_add_friend="content_none";
	}
	$mypals_rs = api_proxy("pals_self_by_uid","*",$userid,5);
?>