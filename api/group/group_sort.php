<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function group_sort_by_self()
{
	global $tablePreStr;
	$t_group_type=$tablePreStr."group_type";
	$result_rs=array();
	$dbo=new dbex;
	dbplugin('r');
	$sql="select * from $t_group_type order by order_num desc";
	$key="group_sort/list/order_num/0/all";
	$key_mt='group_sort/list/order_num/0/all_mt';
	$result_rs=model_cache($key,$key_mt,$dbo,$sql);
	if(empty($result_rs)){
		$result_rs=$dbo->getRs($sql);
	}
	return $result_rs;
}
?>