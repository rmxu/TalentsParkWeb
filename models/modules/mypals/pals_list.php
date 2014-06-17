<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");

	//引入公共模块
	require("foundation/module_mypals.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$mp_langpackage=new mypalslp;
	$user_id=get_sess_userid();
	$user_ico=get_sess_userico();
	$sort_id=intval(get_argg('sort_id'));
	$search_name=short_check(get_argp('search_name'));

	//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_sort=$tablePreStr."pals_sort";

	//当前页面参数
	$page_num=trim(get_argg('page'));
	$show_none_str=$mp_langpackage->mp_no_pals;

	$dbo=new dbex;
	dbtarget('r',$dbServs);
	$sort_str='';
	$mp_list_rs=array();
	$mp_sort_list=array();
	$sql="select * from $t_mypals where user_id=$user_id and accepted > 0 ";

	if($sort_id!=''){
		$str=$mp_langpackage->mp_whole;
		$show_none_str=$mp_langpackage->mp_sort_pals;
		$sql.=" and pals_sort_id = $sort_id ";
	}else if($search_name!=''){
		$show_none_str=$mp_langpackage->mp_none_search;
		$sql.=" and pals_name like '%$search_name%' ";
	}
	$sql.=" order by pals_sort_id desc ";

	$dbo->setPages(20,$page_num);//设置分页
	$mp_list_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数
	
	$none_data="content_none";
	$isNull=0;
	if(empty($mp_list_rs)){
		$none_data="";
		$isNull=1;
	}
	$mp_sort_list=api_proxy("pals_sort");//取得好友圈分类
?>