<?php
include(dirname(__file__)."/../includes.php");

//用户基础api函数
function user_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="user_id",$order="desc",$cache="",$cache_key=""){
	global $allow_cols;
	global $tablePreStr;
	$t_user=$tablePreStr."users";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$allow_cols=array(
		"user_id","user_marry","user_email","user_name","user_sex","user_call","birth_province","birth_city","reside_province","reside_city","user_ico","user_add_time","birth_year","birth_month","birth_day","user_blood","user_qq","creat_group","join_group","guest_num","integral","lastlogin_datetime","hidden_pals_id","hidden_type_id","inputmess_limit","palsreq_limit","is_pass","access_limit","access_questions","access_answers","forget_pass"
	);
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " user_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	if($fields=="*"){
		$fields=join(",",$allow_cols);
	}elseif(strpos($fields,",")){
		$fields=check_item($fields,$allow_cols);
	}else{
		if(!in_array($fields,$allow_cols)){
			$fields='user_id';
		}
	}
  $sql=" select $fields from $t_user where is_pass=1 $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}

function user_self_by_uid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and user_id in ($id_str) ";
	}else{
		$condition=" and user_id = $id_str ";
		$get_type="getRow";
	}
	return user_read_base($fields,$condition,$get_type);
}

function user_self_by_gnum($fields="*",$num=10){
	$fields=filt_fields($fields);
	return user_read_base($fields,"","",$num,"guest_num","desc",1,"gnum_");
}

function user_self_by_integral($fields="*",$num=10){
	$fields=filt_fields($fields);
	return user_read_base($fields,"","",$num,"integral","desc",1,"int_");
}

function user_self_by_logindate($fields="*",$date,$num=10){
	$fields=filt_fields($fields);
	$condition=str_replace("{date}","lastlogin_datetime",date_filter($date));
	return user_read_base($fields,$condition,"",$num,"lastlogin_datetime");
}

function user_self_by_new($fields="*",$num=4){
	$fields=filt_fields($fields);
	return user_read_base($fields,"","",$num);
}

function user_self_by_total(){
	global $tablePreStr;
	$t_user=$tablePreStr."users";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$sql="select count(*) as total from $t_user";
	$result_rs=$dbo->getRow($sql);
	return $result_rs['total'];
}
?>