<?php 
	//引入模块公共方法文件
	require("foundation/module_album.php");
	
	//语言包引入
	$u_langpackage=new userslp;
	
	//变量获得
	$user_id =get_sess_userid();
	$photo_url=short_check(get_argg('photo_url'));
	$img_info=getimagesize($photo_url);
?>