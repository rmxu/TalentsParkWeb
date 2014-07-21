<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	$is_check=check_rights("c29");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	
	//变量取得
	$comment_id=intval(get_argg('cid'));
	$v_id=intval(get_argg('v_id'));
	$type=short_check(get_argg('type'));
	$main_id=intval(get_argg('main_id'));
	
	//数据表定义区
	$t_table='';
	switch ($type){
		case "photo_comment":
		$col="photo_id";
		$t_main=$tablePreStr."photo";
		$t_table=$tablePreStr."photo_comment";
		break;
		case "blog_comment":
		$col="log_id";
		$t_main=$tablePreStr."blog";
		$t_table=$tablePreStr."blog_comment";
		break;
		case "album_comment":
		$col="album_id";
		$t_main=$tablePreStr."album";
		$t_table=$tablePreStr."album_comment";
		break;
		case "group_subject_comment":
		$col="subject_id";
		$t_main=$tablePreStr."group_subject";
		$t_table=$tablePreStr."group_subject_comment";
		break;
		case "poll_comment":
		$col="p_id";
		$t_main=$tablePreStr."poll";
		$t_table=$tablePreStr."poll_comment";
		break;
		case "share_comment":
		$col="s_id";
		$t_main=$tablePreStr."share";
		$t_table=$tablePreStr."share_comment";
		break;
		case "mood_comment":
		$col="mood_id";
		$t_main=$tablePreStr."mood";
		$t_table=$tablePreStr."mood_comment";
		break;
		default:
		echo 'error';
		break;
	}

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//删除评论
	$sql = "delete from $t_table where comment_id=$comment_id";
	if($dbo -> exeUpdate($sql)){
		$sql="update $t_main set comments=comments-1 where $col = $main_id";
		$dbo->exeUpdate($sql);
		increase_integral($dbo,$int_del_com_msg,$v_id);
		echo $m_langpackage->m_del_suc;
	}
?>
