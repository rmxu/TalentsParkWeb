<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function outbox_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="mess_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$uid=get_sess_userid();
	$t_scrip=$tablePreStr."msg_outbox";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " mess_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_scrip where user_id = $uid $condition order by $by_col $order ";
  if($cache==1&&$cache_key!=''){
		$key='outbox_'.$cache_key.$uid.'_'.$num;
		$key_mt='outbox_'.$cache_key.'mt_'.$uid.'_'.$num;
		$result_rs=model_cache($key,$key_mt,$dbo,$sql,$get_type);
	}
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function scrip_outbox_get_mine($fields="*",$to_id="",$is_read="",$date=""){
	$to_id_str=filt_num_array($to_id);
	$fields=filt_fields($fields);
	$condition="";
	$condition.=str_replace("{date}","add_time",date_filter($date));
	if($is_read!==''){
		$is_read=intval($is_read);
		$condition.=" and state = $is_read ";
	}
	if($to_id!=''){
		$condition.=" and to_user_id in ($to_id_str) ";
	}
	return outbox_read_base($fields,$condition);
}

?>