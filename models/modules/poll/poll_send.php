<?php
  //引入公共模块
  require("foundation/fpages_bar.php");
	require("foundation/module_poll.php");
	require("api/base_support.php");
	//限制时间段访问站点
	limit_time($limit_action_time);
  //语言包引入
  $pol_langpackage=new polllp;
  
  //变量区
	$user_id = get_sess_userid();
	$user_info = api_proxy("user_self_by_uid","integral",$user_id);
?>