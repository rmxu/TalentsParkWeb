<?php
	//引入语言包
	$g_langpackage=new grouplp;
	
	//引入公共模块
	require("foundation/module_group.php");
	require("api/base_support.php");
	
	//缓存功能区
	$group_sort_rs=api_proxy("group_sort_by_self");
	$group_type=group_sort_list($group_sort_rs,"");
?>