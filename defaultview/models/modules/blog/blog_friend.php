<?php
	//引入公共模块
	require("foundation/auser_mustlogin.php");
	require("foundation/fpages_bar.php");
	require("foundation/module_blog.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	
	//语言包引入
	$b_langpackage=new bloglp;
	$rf_langpackage=new recaffairlp;
	
	//变量定义
	$user_id=get_sess_userid();
	$user_mypals=get_sess_mypals();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	$blog_rs=array();
	$page_total='';
	if($user_mypals!=''){
		$blog_rs=api_proxy("blog_self_by_uid","*",$user_mypals);
  }
  	//控制数据显示
		$content_data_none="content_none";
		$content_data_set="";
		$isNull=0;
	if(empty($blog_rs)){
		$isNull=1;
		$content_data_none="";
		$content_data_set="content_none";
	}  
?>