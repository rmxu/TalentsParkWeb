<?php
	//引入公共模块
	require("foundation/module_poll.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$pol_langpackage=new polllp;

	//变量声明区
	$poll_rs=array();

	$mod=get_argg('m')? get_argg('m'):"new";

	//当前页面参数
	$page_num=trim(get_argg('page'));
	$new_active='';
	$hot_active='';
	$reward_active='';
	
	switch($mod){
		case "new":
		$new_active="active";
		$poll_rs=api_proxy("poll_self_by_new","*");
		break;
		case "hot":
		$hot_active="active";
		$poll_rs=api_proxy("poll_self_by_hot","*");
		break;
		case "reward":
		$reward_active="active";
		$poll_rs=api_proxy("poll_self_by_reward","*");
		break;
		default:echo "error";
	}

	//数据显示
	$none_data="content_none";
	$isNull=0;
	if(empty($poll_rs)){
		$isNull=1;
		$none_data="";
	}
?>