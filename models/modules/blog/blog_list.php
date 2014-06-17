<?php
  //引入公共模块
  require("foundation/fpages_bar.php");
	require("foundation/module_users.php");
	require("foundation/module_blog.php");
	require("foundation/fcontent_format.php");
	require("foundation/module_mypals.php");
	require("servtools/menu_pop/trans_pri.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;

  //变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$sort_name=get_argg('sort_name') ? urldecode(get_argg('sort_name')):"-1";
	$sort_id=get_argg('sort_id');
	if($sort_id===0){
		$sort_name=0;
	}

  //数据表定义
  $t_blog=$tablePreStr."blog";
  $t_users=$tablePreStr."users";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$page_num=intval(get_argg('page'));
	$is_friend='';
	$no_data_text=$b_langpackage->b_no_fri_blog;

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	if($is_self=='Y'){
		$str_title=$b_langpackage->b_mine;
		$no_data_text=$b_langpackage->b_none;
		$url_userid='';
	}else{
		$holder_name=get_hodler_name($url_uid);
		$str_title=str_replace("{holder}",$holder_name,$b_langpackage->b_his_blog);
		$url_userid="&user_id=".$userid;
	}

	$blog_rs=array();
	$blog_rs=api_proxy("blog_self_by_uid","*",$userid,$sort_id);

	//控制数据显示
	$content_data_none="content_none";
	$isNull=0;
	if(empty($blog_rs)){
		$isNull=1;
		$content_data_none="";
	}
	?>