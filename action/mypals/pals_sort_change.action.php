<?php
	//变量取得
  $sort_id=intval(get_argg('id'));
	$user_id=get_sess_userid();
	$sort_name=short_check(get_argp('name'));

	//数据表定义区
	$t_pals_sort=$tablePreStr."pals_sort";
	$t_mypals=$tablePreStr."pals_mine";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	$sql = "update $t_pals_sort set name='$sort_name' where id=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	$sql="update $t_mypals set pals_sort_name='$sort_name' where pals_sort_id=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
?>
