<?php
	//引入语言包
	$mb_langpackage=new msgboardlp;

	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	require("foundation/fpages_bar.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	$mes_rs=array();
	$page_num=intval(get_argg('page'));
	$mes_rs=api_proxy("msgboard_self_by_uid","*",$userid);
	$isNull=$mes_rs ? 0:1;

	if($is_self=='Y'){
		api_proxy("msgboard_set",$ses_uid);
		$msg_title=$mb_langpackage->mb_msgb;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$msg_title=str_replace("{holder}",$holder_name,$mb_langpackage->mb_who_msg);
	}
?>