<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function group_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="group_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_group=$tablePreStr."groups";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " group_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  $sql=" select $fields from $t_group where $is_pass $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function group_self_by_gid($fields="*",$id,$get_type=''){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	if(strpos($id_str,",")){
		$condition=" and group_id in ($id_str) ";
	}else{
		$condition=" and group_id = $id_str ";
		$get_type=($get_type=='getRs')?"getRs":"getRow";
	}
	return group_read_base($fields,$condition,$get_type);
}

function group_self_by_uid($fields="*",$id='',$get_type=''){
	global $tablePreStr;
	$id=intval($id) ? $id : get_sess_userid();
	$t_group_members=$tablePreStr."group_members";
	$gid_array=array();
	$dbo=new dbex;
  dbplugin('r');
	$sql="select group_id from $t_group_members where user_id='$id' and state>0";
	$gid_array=$dbo->getRs($sql);
	if($gid_array){
		$gid_str='';
		foreach($gid_array as $rs){
			if($gid_str!=''){
				$gid_str.=',';
			}
			$gid_str.=$rs['group_id'];
		}
		$fields=filt_fields($fields);
		return group_self_by_gid($fields,$gid_str,$get_type);
	}else{
		return array();
	}
}

function group_self_get_new($fields="*",$num=10){
	$num=intval($num);
	return group_read_base($fields,'','',$num,'','',1,"new_");
}

function group_self_by_subs($fields="*",$num=10){
	$num=intval($num);
	return group_read_base($fields,'','',$num,'subjects_num','',1,"subs_");
}

function group_self_by_pals($fields="*"){
	$fields=filt_fields($fields);
	$group_id_str='';
	global $tablePreStr;
	$t_group_members=$tablePreStr."group_members";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$pals_id=get_sess_mypals();
	$sql=" select group_id from $t_group_members where user_id in ($pals_id) ";
	$group_data=$dbo->getRs($sql);
	foreach($group_data as $rs){
		$group_id_str.=$rs['group_id'].",";
	}
	$group_id_str=preg_replace("/,$/","",$group_id_str);
	return group_self_by_gid($fields,$group_id_str);
}

function group_self_by_memberCount($fields="*",$num=10){
	$fields=filt_fields($fields);
	return group_read_base($fields,"","",$num,"member_count");
}

?>