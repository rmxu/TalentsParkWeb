<?php
include(dirname(__file__)."/../includes.php");
function plugins_set_mine($id,$is_del=0){
  $id=intval($id);
  $is_del=intval($is_del);
  $val='';
  $uid=get_sess_userid();
	global $tablePreStr;
	$t_users=$tablePreStr."users";
	$t_plugins=$tablePreStr."plugins";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $u_apps=get_sess_apps();
  if($is_del==0){
	  if($u_apps==''){
	  	$val=$id;
	  }else{
	  	$val=$u_apps.",".$id;
	  }
  }else{
  	$val=str_replace(",$id,","",",$u_apps,");
  }

  $sql=" update $t_users set use_apps = '$val' where user_id = $uid ";
  if($dbo->exeUpdate($sql)){
  	set_sess_apps($val);
  	if($is_del==0){
  		$sql=" update $t_plugins set use_num=use_num+1 where id=$id ";
  	}else{
  		$sql=" update $t_plugins set use_num=use_num-1 where id=$id ";
  	}
  	return $dbo->exeUpdate($sql);
  }else{
  	return 0;
  }
}
?>