<?php
	//变量取得
  $sort_id=intval(get_argg('id'));
	$user_id=get_sess_userid();


	//数据表定义区
	$t_pals_sort=$tablePreStr."pals_sort";
	$t_mypals=$tablePreStr."pals_mine";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$sql = "delete from $t_pals_sort where id=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	
	$sql="update $t_mypals set pals_sort_name=NULL , pals_sort_id=0 where pals_sort_id=$sort_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	
	action_return(0,"","");
?>
