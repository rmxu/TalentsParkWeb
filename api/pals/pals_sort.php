<?php
include(dirname(__file__)."/../includes.php");

//好友圈基础api函数
function pals_sort($uid=''){
	$uid=intval($uid);
	if($uid==0){
		$uid=get_sess_userid();
	}
	global $tablePreStr;
	$t_pals_sort=$tablePreStr."pals_sort";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select * from $t_pals_sort where user_id=$uid ";
	$result_rs=$dbo->getALL($sql);
	return $result_rs;
}

//取得默认分组
function pals_sort_def(){
	global $tablePreStr;
	$t_pals_sort=$tablePreStr."pals_def_sort";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select * from $t_pals_sort ";
	$key="pals_def_sort/list/order_num/0/all";
	$key_mt="pals_def_sort/list/order_num/0/all_mt";
	$result_rs=model_cache($key,$key_mt,$dbo,$sql);
	if(empty($result_rs)){
		$result_rs=$dbo->getALL($sql);
	}
	return $result_rs;
}
?>