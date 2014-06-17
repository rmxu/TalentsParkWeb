<?php
	//引入语言包
	$a_langpackage=new albumlp;

	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("foundation/module_album.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";
	$t_users = $tablePreStr."users";

	$album_rs=array();
	$album_rs=api_proxy("album_self_by_uid","*",$userid);

	if($is_self=='Y'){
		$a_who=$a_langpackage->a_mine;
		$guide_txt=$a_langpackage->a_no_alb.",<a href='modules.php?app=album_edit'>".$a_langpackage->a_crt_alb."</a>,<a href='modules.php?app=album_friend'>".$a_langpackage->a_fri_alb."</a>";
		$button="";
	}else{
		$holder_name=get_hodler_name($url_uid);
		$a_who=str_replace('{holder}',filt_word($holder_name),$a_langpackage->a_holder);
		$guide_txt=$a_langpackage->a_no_fri;
		$button="content_none";
	}

	$isNull=0;//不为空则设置为零
	$a_main="";
	$guide="content_none";
	if(empty($album_rs)){
		$isNull=1;//判断结果集是否为空
		$a_main="content_none";
		$guide="";
	}
?>