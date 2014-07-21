<?php
//引入模块公共方法文件
require("foundation/module_poll.php");
require("api/base_support.php");

//引入语言包
$pol_langpackage=new polllp;

//变量声明区
	$user_id=get_sess_userid();
	$set_option=short_check(get_argg('set_option'));
  $pid=intval(get_argg('pid'));
  $poll_info=array();
  
  if($set_option=="add_award"){
		$u_int = api_proxy("user_self_by_uid","integral",$user_id);
	}
  $poll_info = api_proxy("poll_self_by_pollid","*",$pid);
?>
