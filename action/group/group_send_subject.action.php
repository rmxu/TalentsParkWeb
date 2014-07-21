<?php
//引入公共模块
  require("foundation/aanti_refresh.php");
	require("foundation/aintegral.php");
	require("foundation/module_group.php");
	require("foundation/ftag.php");

//引入语言包
	$g_langpackage=new grouplp;

//变量区
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$user_ico=get_sess_userico();
	$join_group=get_sess_jgroup();
	$creat_group=get_sess_cgroup();
	$group_id=intval(get_argg('group_id'));
	$ulog_title=short_check(get_argp("LOG_TITLE"));
  $ulog_txt=big_check(get_argp("CONTENT"));
  $u_id=intval(get_argg('user_id'));
  $tag=short_check(get_argp('tag'));

//防止重复提交
	antiRePost($ulog_title);

//数据表定义
  $t_group_subject=$tablePreStr."group_subject";
  $t_group=$tablePreStr."groups";

//权限判定
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//写入方式
	dbtarget('w',$dbServs);
  $sql="insert into $t_group_subject (user_id,title,content,add_time,group_id,user_name,hits,user_ico,`tag`) values($user_id,'$ulog_title','$ulog_txt',NOW(),$group_id,'$user_name',0,'$user_ico','$tag')";
  $dbo->exeUpdate($sql);
  $last_id=mysql_insert_id();

	$sql="update $t_group set subjects_num=subjects_num+1 where group_id=$group_id";
	$dbo->exeUpdate($sql);

//标签功能
	$tag_id=tag_add($tag);
	$tag_state=tag_relation(4,$tag_id,$last_id);

	increase_integral($dbo,$int_subject,$user_id);

  $jump="modules.php?app=group_space&group_id=$group_id&user_id=".$u_id;
  action_return(1,"",$jump);
?>

