<?php
	
	//变量取得
	$user_id = get_sess_userid();
	$hi_id = short_check(get_argg('hi_id'));
	$del_array=get_argp("attach");
	
	//数据表定义区
	$t_hi = $tablePreStr."hi";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	if($del_array==NULL){
		$del_array[]=$hi_id;
	}
	
	foreach($del_array as $rs){
		$rs=short_check($rs);
		$sql="delete from $t_hi where hi_id=$rs";
		$dbo->exeUpdate($sql);
	}
		
	action_return(1,"",-1);

?>