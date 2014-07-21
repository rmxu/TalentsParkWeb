<?php
include(dirname(__file__)."/../includes.php");

//相册基础api函数
function album_photo_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="photo_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_photo=$tablePreStr."photo";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " photo_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
  $sql=" select $fields from $t_photo where $is_pass $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
			$dbo->setPages(20,$page_num);//设置分页
		}
  	$result_rs=$dbo->{$get_type}($sql);
  	$page_total=$dbo->totalPage;//分页总数
	}
	return $result_rs;
}

function album_photo_by_photoid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and photo_id in ($id_str) ";
	}else{
		$condition=" and photo_id = $id_str ";
		$get_type="getRow";
	}
	return album_photo_read_base($fields,$condition,$get_type);
}

function album_photo_by_aid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " and album_id in ($id_str) ":" and album_id=$id_str ";
	return album_photo_read_base($fields,$condition);
}

function album_photo_by_uid($fields="*",$id,$num=10){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " and user_id in ($id_str) ":" and user_id = $id_str ";
	return album_photo_read_base($fields,$condition,"getRs",$num);
}

function album_photo_by_coms($fields="*",$num=10){
	$num=intval($num);
	return album_photo_read_base($fields,"","","comments","desc",$num,1,"coms_");
}

function album_photo_get_new($fields="*",$num=10){
	$num=intval($num);
	return album_photo_read_base($fields,"","",$num,"","",1,"new_");
}
?>