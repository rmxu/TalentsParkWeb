<?php
//变量取得
  $p_id=intval(get_argg('id'));
	$sort_name=short_check(get_argp('name'));
	$sort_id=intval(get_argp('sort_id'));
	$old_sort_id=intval(get_argp('old_sort_id'));
	$user_id=get_sess_userid();
	
//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_sort=$tablePreStr."pals_sort";
	
//读写分离定义函数	
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$sql="update $t_mypals set pals_sort_name='$sort_name' , pals_sort_id=$sort_id where pals_id=$p_id and user_id=$user_id";
	if($dbo->exeUpdate($sql)){
		$sql="update $t_pals_sort set count=count+1 where id=$sort_id";
		$dbo->exeUpdate($sql);
		if($old_sort_id!=0){
			$sql="update $t_pals_sort set count=count-1 where id=$old_sort_id";
			$dbo->exeUpdate($sql);
		}
	}
?>