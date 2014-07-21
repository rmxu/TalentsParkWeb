<?php
	//语言包引入
	$u_langpackage=new userslp;
	$user_id =get_sess_userid();
	
	//引入模块公共方法文件 
	require("foundation/module_users.php");
	$tpl_array=explode("/",$skinUrl);
	$tpl_name=$tpl_array[0];
	$dress_url="skin/".$tpl_name."/home/";
	
	//引入装扮数据
	require($dress_url."tip.php");
?>