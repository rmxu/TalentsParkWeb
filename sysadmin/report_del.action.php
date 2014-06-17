<?php
require("session_check.php");

//语言包引入
$m_langpackage=new modulelp;

$dbo = new dbex;
dbtarget('r',$dbServs);

//数据表定义区
$t_report = $tablePreStr."report";

//变量取得
$report_id=intval(get_argg('rid'));
$type=get_argg('type');

switch($type){
	case "1":
	$group_id=intval(get_argg('redid'));
	$type_value=0;
	require 'group_lock.action.php';
	break;
	
	case "0":
	$blog_id=intval(get_argg('redid'));
	$sendor_id=intval(get_argg('uedid'));
	require 'blog_del.action.php';		
	break;
	
	case "3":
	$photo_id=intval(get_argg('redid'));
	$user_id=intval(get_argg('uedid'));
	require 'photo_del.action.php';		
	break;
	
	case "2":
	$album_id=intval(get_argg('redid'));
	$user_id=intval(get_argg('uedid'));
	require 'album_del.action.php';		
	break;
	
	case "4":
	$pid=intval(get_argg('redid'));
	$sendor_id=intval(get_argg('uedid'));
	require 'poll_del.action.php';		
	break;
	
	case "8":
	$share_id=intval(get_argg('redid'));
	$u_id=intval(get_argg('uedid'));
	require 'share_del.action.php';		
	break;
	
	case "9":
	$subject_id=intval(get_argg('redid'));
	$sendor_id=intval(get_argg('uedid'));
	require 'subject_del.action.php';		
	break;
	
	case "10":
	$user_id=intval(get_argg('redid'));
	$type_value=0;
	require 'member_lock.action.php';		
	break;
	
	default:
	$is_check=check_rights("c36");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	break;
}

$sql = "delete from $t_report where report_id =$report_id";
$is_success=$dbo -> exeUpdate($sql);
if($type==''){
	echo $m_langpackage->m_del_suc;
}
?>