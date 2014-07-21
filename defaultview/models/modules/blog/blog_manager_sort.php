<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("foundation/module_blog.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;

  //变量定义
  $user_id=get_sess_userid();

  //数据表定义  
  $t_blog_sort=$tablePreStr."blog_sort";
  $blog_sort_rs=api_proxy("blog_sort_by_uid",$user_id);
?>