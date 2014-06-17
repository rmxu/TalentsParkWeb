<?php
	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
  	$sort_id=intval(get_argg('id'));
	$user_id=get_sess_userid();
	$sort_name=short_check(get_argp('name'));

	//数据表定义区
	$t_blog_sort=$tablePreStr."blog_sort";
	$t_blog=$tablePreStr."blog";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql = "update $t_blog_sort set name='$sort_name' where id=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	
	$sql="update $t_blog set log_sort_name='$sort_name' where log_sort=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	
	echo $b_langpackage->b_change_sort_suc;

	
?>
