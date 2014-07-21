<?php
//引入语言包
	$pu_langpackage=new publiclp;
	$rp_langpackage=new reportlp;

//引入公共方法	
	require("foundation/aintegral.php");
	require("api/base_support.php");

//变量定义区
	$visitor_id=get_sess_userid();
	$host_id=intval(get_argg('holder_id'));
	$type_id=intval(get_argg('type_id'));
	$sendor_id=intval(get_argg('sendor_id'));
	$parent_id=intval(get_argg('parent_id'));
	$com_id=intval(get_argg('com_id'));
	
	if($visitor_id!=$host_id){
		echo $pu_langpackage->pu_not_pri;exit;
	}
		
//数据表定义
  $t_share=$tablePreStr."share";
  $t_share_comment=$tablePreStr."share_comment";
  
	$t_poll=$tablePreStr."poll";
	$t_poll_comment=$tablePreStr."poll_comment";
	
	$t_album=$tablePreStr."album";
  $t_album_comment = $tablePreStr."album_comment";
  
	$t_photo=$tablePreStr."photo";
  $t_photo_comment = $tablePreStr."photo_comment";  
  
  $t_blog=$tablePreStr."blog";
  $t_blog_comment=$tablePreStr."blog_comment";
  
  $t_group=$tablePreStr."groups";
  $t_subject=$tablePreStr."group_subject";
  $t_subject_comment=$tablePreStr."group_subject_comment";
  
  $t_mood=$tablePreStr."mood";
  $t_mood_comment=$tablePreStr."mood_comment";
  
  $dbo=new dbex();
  
	switch($type_id){
		case "0":
		$t_table=$t_blog;
		$t_table_com=$t_blog_comment;
		$mod_col="log_id";
		break;
		case "1":
		$group_info=api_proxy("group_sub_by_sid","group_id",$com_id);
		$group_id=$group_info['group_id'];
		dbtarget('w',$dbServs);
		$sql="update $t_group set comments=comments-1 where group_id=$group_id";
		$dbo->exeUpdate($sql);
		$t_table=$t_subject;
		$t_table_com=$t_subject_comment;
		$mod_col="subject_id";
		break;
		case "2":
		$t_table=$t_album;
		$t_table_com=$t_album_comment;
		$mod_col="album_id";
		break;
		case "3":
		$t_table=$t_photo;
		$t_table_com=$t_photo_comment;
		$mod_col="photo_id";
		break;
		case "4":
		$t_table=$t_poll;
		$t_table_com=$t_poll_comment;
		$mod_col="p_id";
		break;
		case "5":
		$t_table=$t_share;
		$t_table_com=$t_share_comment;
		$mod_col="s_id";
		break;
		case "6":
		$t_table=$t_mood;
		$t_table_com=$t_mood_comment;
		$mod_col="mood_id";
		break;
		default:
		echo 'error';
		break;
	}  

//定义读操作
	  dbtarget('w',$dbServs);	
	  $is_true=0;
	  $sql="delete from $t_table_com where comment_id=$com_id";
	  if($dbo->exeUpdate($sql)){
		  $sql="update $t_table set comments=comments-1 where $mod_col=$parent_id";
		  
		  $is_true=$dbo->exeUpdate($sql);
	  }
	  if($is_true){
		  increase_integral($dbo,$int_del_com_msg,$sendor_id);
		  echo "success";
	  }else{
	  	echo $rp_langpackage->rp_del_los;
	  }

?>

