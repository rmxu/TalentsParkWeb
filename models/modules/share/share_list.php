<?php
	//引入公共模块
	require("foundation/fpages_bar.php");
	require("foundation/module_users.php");

	require("foundation/module_share.php");
	require("api/base_support.php");

	//语言包引入
	$s_langpackage=new sharelp;
	$rf_langpackage=new recaffairlp;
	$mn_langpackage=new menulp;

	//变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$mod=short_check(get_argg('m'));

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//数据表定义
	$t_share=$tablePreStr."share";
	$t_users=$tablePreStr."users";
	dbtarget('r',$dbServs);
	$dbo=new dbex;

	$share_rs=array();
	switch($mod){
		case "mine":
		$id_cols="user_id=$ses_uid";
		break;
		default:
		$id_cols="user_id=$userid";
		break;
	}
	$order_by="order by add_time desc";
	$type='getRs';
	$dbo->setPages(20,$page_num);//设置分页
	$share_rs=get_db_data($dbo,$t_share,$id_cols,$order_by,$type);
	$page_total=$dbo->totalPage;//分页总数

	$button_show_mine="";
	$button_show_his="content_none";
	if($is_self=='Y'){
	  $str_title=$s_langpackage->s_mine;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$str_title=str_replace("{holder}",$holder_name,$s_langpackage->s_who_share);
		$button_show_mine="content_none";
		$button_show_his="";
	}

	//控制数据显示
	$content_data_none="content_none";
	$content_data_set="";
	$isNull=0;
	if(empty($share_rs)){
		$isNull=1;
		$content_data_none="";
		$content_data_set="content_none";
	}
?>