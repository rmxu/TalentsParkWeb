<?php
 //引入模块公共方法文件
 require("foundation/module_blog.php");

	//变量取得
  $pals_sort=short_check(get_argp("new_sort"));
	$user_id=get_sess_userid();
	$user_name=get_sess_username();

	//数据表定义区
	$t_pals_sort=$tablePreStr."pals_sort";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql = "insert into $t_pals_sort (name,user_id) value('$pals_sort',$user_id)";

	if($dbo->exeUpdate($sql)){
		action_return(0,"","");
	}else{
		action_return(1,$b_langpackage->b_add_fal,-1);
	}

?>
