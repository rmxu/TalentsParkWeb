<?php
include(dirname(__file__)."/../includes.php");

//用户基础api函数
function user_recommend_base($fields="*",$condition="",$get_type="",$num="",$by_col="rec_order",$order="asc",$cache="",$cache_key=""){
	global $tablePreStr;
	$t_recommend=$tablePreStr."recommend";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " rec_order ";
	$order = $order ? $order:"asc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_recommend where is_pass = 1 $condition order by $by_col $order ";
	//缓存机制
	$key='recommend/list/rec_order/all/0';
	$key_mt='recommend/list/rec_order/all/0_mt';
	$result_rs=model_cache($key,$key_mt,$dbo,$sql,$get_type);
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}

function user_recommend_get($fields="*"){
	$fields=filt_fields($fields);
	return user_recommend_base($fields);
}
?>