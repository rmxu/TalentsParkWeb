<?php
  //引入公共模块
  require("foundation/module_mypals.php");
	require("foundation/module_blog.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;
	$mn_langpackage=new menulp;

  //变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$is_friend=intval(get_argg('is_friend'));
	$ulog_id=intval(get_argg("id"));
	$is_admin=get_sess_admin();
	$blog_row=array();

  //引入模块公共权限过程文件
  $is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

  //数据表定义
  $t_blog=$tablePreStr."blog";

  //初始化数据库操作对象
  $dbo=new dbex;
  if($ses_uid!=get_session('b_'.$ulog_id)){
	  //读写分离方法-写操作
	  dbtarget('w',$dbServs);
	  $sql="update $t_blog set hits=hits+1 where log_id=$ulog_id";
	  $dbo->exeUpdate($sql);
	  set_session('b_'.$ulog_id,$ses_uid);
	}

	//控制数据显示
	$show_error="content_none";
	$is_show=1;
	$error_str='';
	if($ulog_id){
		$blog_row=api_proxy("blog_self_by_bid","*",$ulog_id);
	  if($is_self=='N'){
	  	require("servtools/menu_pop/trans_pri.php");
	  	$is_show=check_pri($blog_row['log_id'],$blog_row['privacy']);
	  }
	}
	//控制按钮显示
	$button_ctrl_his="content_none";
	$button_ctrl_mine="";

	if(get_argg('is_friend')==1){
		$button_ctrl_his="";
		$button_ctrl_mine="content_none";
	}

	if(empty($blog_row)){
		$error_str=$b_langpackage->b_error;
		$is_show=0;
		$show_error="";
	}
?>