<?php
	//引入语言包
	$gu_langpackage=new guestlp;

	//引入公共模块
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	$guest_rs = api_proxy("guest_self_by_uid","*",$userid,20);

	if($is_self=='Y'){
		$guest_title=$gu_langpackage->gu_title;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$guest_title=str_replace("{holder}",filt_word($holder_name),$gu_langpackage->gu_who);
	}
	$none_data="content_none";
	$show_data="";
	if(empty($guest_rs)){
		$none_data="";
		$show_data="content_none";
	}
?>