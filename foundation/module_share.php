<?php
function share_act($dbo,$type_id,$for_content_id,$s_title='',$tag='',$link_href='',$link_thumb='',$re_m_link=''){
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$userico=get_sess_userico();
	global $tablePreStr;
	$t_share=$tablePreStr.'share';
	if($for_content_id==0){
		$sql="select max(s_id) as max_id from $t_share";
		$last_id=$dbo->getRow($sql);
		if($last_id['max_id']==NULL){
			$for_content_id=1;
		}else{
			$for_content_id=$last_id['max_id']+1;
		}
	}
	$sql="insert into $t_share ( type_id,user_id,user_name,user_ico,add_time,for_content_id,s_title,out_link,movie_thumb,movie_link,`tag`) values "
			."($type_id,$user_id,'$user_name','$userico',NOW(),$for_content_id,'$s_title','$link_href','$link_thumb','$re_m_link','$tag')";
	$dbo->exeUpdate($sql);
	return mysql_insert_id();
}

function get_db_data($dbo,$table,$condition,$order_by,$type){
	$sql="select * from $table where $condition $order_by";
	$db_result=$dbo->{$type}($sql);
	return $db_result;
}
?>