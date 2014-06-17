<?php 
	//引入模块公共方法文件
	require("foundation/module_album.php");
	require("api/base_support.php");
	
	//语言包引入
	$u_langpackage=new userslp;
	
	//变量获得
	$user_id =get_sess_userid();
	$album_id=intval(get_argg('album_id'));
	
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
?>