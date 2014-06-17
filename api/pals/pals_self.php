<?php
include(dirname(__file__)."/../includes.php");

//好友圈基础api函数
function pals_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="pals_sort_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_pals=$tablePreStr."pals_mine";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$limit=$num ? " limit $num ":"";
  $sql=" select $fields from $t_pals where $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
			$dbo->setPages(20,$page_num);
		}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function pals_self_by_paid($fields="*",$id='',$accept=1){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$uid=get_sess_userid();
	$condition=" user_id = $uid ";
	if($accept!=''){
		$accept=intval($accept);
	}
	$get_type="";
	if($id_str!=''){
		if(strpos($id_str,",")){
			$condition.=" and pals_id in ($id_str) ";
		}else{
			$condition.=" and pals_id = $id_str ";
			$get_type="getRow";
		}
	}
	$condition.=" and accepted >= $accept ";
	return pals_read_base($fields,$condition,$get_type);
}

function pals_self_by_sort($fields="*",$sort){
	$uid=get_sess_userid();
	$fields=filt_fields($fields);
	$sort_str=filt_str_array($sort);
	$condition=" user_id = $uid and pals_sort_name in ($sort_str) ";
	return pals_read_base($fields,$condition);
}

function pals_self_by_online($fields="*",$num=""){
	$num=intval($num);
	$limit='';
	if($num!=0){
		$limit=" limit $num ";
	}
	$fields=filt_fields($fields);
	$pals_id_str=get_sess_mypals();
	global $tablePreStr;
	$t_online=$tablePreStr."online";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select $fields from $t_online where user_id in ($pals_id_str) and hidden = 0 order by active_time desc $limit ";
  return $dbo->getRs($sql);
}

function pals_self_by_uid($fields="*",$id,$num=''){
	$num=intval($num);
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,",") ? " user_id in ($id_str) and accepted>0 " : " user_id = $id_str and accepted>0 ";
	return pals_read_base($fields,$condition,'getRs',$num);
}

function pals_self_isset($holder_id,$pals_id=''){
	global $tablePreStr;
	$t_pals=$tablePreStr."pals_mine";
	$result_rs=array();
	$pals_id=$pals_id ? $pals_id:get_sess_userid();
	if($pals_id){
		$dbo=new dbex;
	  dbplugin('r');
	  $sql="select id from $t_pals where user_id=$holder_id and pals_id=$pals_id";
	  $result_rs=$dbo->getRow($sql);
	}else{
		$result_rs=0;
	}
	return $result_rs;
}
?>