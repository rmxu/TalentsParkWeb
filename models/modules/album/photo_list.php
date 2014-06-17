<?php
	//引入语言包
	$a_langpackage=new albumlp;
	$mn_langpackage=new menulp;

	//变量取得
	$album_id = intval(get_argg('album_id'));
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$page_num=intval(get_argg('page'));

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	require("foundation/module_users.php");

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";
	$t_album_comment = $tablePreStr."album_comment";

	$album_row=array();

	$album_row=api_proxy("album_self_by_aid","*",$album_id);
	$photo_rs=api_proxy("album_photo_by_aid","*",$album_id);

	$host_id=$album_row['user_id'];
	$album_information = $album_row['album_info'];

	if($ses_uid==$host_id){
		$no_pht = $a_langpackage->a_no_upl.",<a href='modules.php?app=photo_upload&album_id=".$album_id."'>".$a_langpackage->a_upl_pht."</a>";
	}else{
		$no_pht = $a_langpackage->a_f_no_pht;
	}

	if($is_self=='Y'){
		$a_who=$a_langpackage->a_mine;
		$user_id=$ses_uid;
		$button="";
	}else{
		$holder_name=get_hodler_name($url_uid);
		$a_who=str_replace('{holder}',filt_word($holder_name),$a_langpackage->a_holder)."</div>";
		$user_id=$album_row['user_id'];
		$button="content_none";
	}

	//数据显示控制
	$isNull=0;
	$show_data=1;
	$none_photo="content_none";

	if(empty($photo_rs)){
		$isNull=1;//判断结果集是否为空
		$show_data=0;
		$none_photo="";
	}
?>