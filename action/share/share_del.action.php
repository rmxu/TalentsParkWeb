<?php
//引入模块公共方法文件
require("foundation/aintegral.php");
require("foundation/module_affair.php");
//引入语言包
	$s_langpackage=new sharelp;

//变量声明区
	$user_id=get_sess_userid();
	$share_id=intval(get_argg('share_id'));


  //数据表定义
  $t_share=$tablePreStr."share";
  $t_share_comment=$tablePreStr."share_comment";
  
  //初始化数据库操作对象
  $dbo=new dbex;
	dbtarget('w',$dbServs);	
	
	$sql="delete from $t_share_comment where s_id=$share_id";
	$dbo->exeUpdate($sql);
	
	$sql="delete from $t_share where s_id=$share_id";
	$dbo->exeUpdate($sql);
	
	increase_integral($dbo,$int_del_share,$user_id);
	del_affair($dbo,5,$share_id);
	//回应信息
	action_return(1,'','-1');

?>