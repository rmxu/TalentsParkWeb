<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//引入语言包
	$mp_langpackage=new mypalslp;
	$b_langpackage=new bloglp;

   //变量定义
   $user_id=get_sess_userid();   
   $pals_sort_rs = api_proxy("pals_sort","*",$user_id);
?>