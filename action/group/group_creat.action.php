<?php
//引入模块公共方法文件
require("api/base_support.php");
require("foundation/aanti_refresh.php");
require("foundation/ftag.php");

//引入语言包
$g_langpackage=new grouplp;

//权限验证
if(!get_argp('action')){
	action_return(0,"$g_langpackage->g_no_privilege","-1");
}

//变量声明区
$user_id=get_sess_userid();
$user_name=get_sess_username();
$sess_user_sex=get_sess_usersex();
$sess_creat_group=get_sess_cgroup();
$userico=get_sess_userico();
$group_name=short_check(get_argp('group_name'));
$group_resume=short_check(get_argp('group_resume'));
$group_join_type=intval(get_argp('group_join_type'));
$group_type_id=intval(get_argp('group_type_id'));
$group_type_name=short_check(get_argp('group_type_name'));
$tag=short_check(get_argp('tag'));

//判断用户已经建立的群组数量
if(count(explode(",",$sess_creat_group))>=5){
	action_return(0,$g_langpackage->g_c_limit,"-1");
}

//数据表定义区
$t_groups=$tablePreStr."groups";
$t_users=$tablePreStr."users";
$t_group_members=$tablePreStr."group_members";

//判定是否有图片
$fileSrcStr='uploadfiles/group_logo/default_group_logo.jpg';
$thumb_src='';
if($_FILES['attach']['name'][0]!=''){
  $base_dir="uploadfiles/group_logo/";
  $up = new upload();
  $up->set_dir($base_dir,'{y}/{m}/{d}');//目录设置
  $up->set_thumb(150,150); //缩略图设置
  $fs = $up->execute();
  if($fs[0]['flag']==-1){
  	action_return(0,$g_langpackage->g_logo_limit,"-1");
  }
	$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];
	unlink($fileSrcStr);
	$thumb_src=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];
}

//定义写操作
dbtarget('w',$dbServs);
$dbo=new dbex();

//插入group数据表
$sql="insert into $t_groups (add_userid,member_count,group_name,group_resume,group_creat_name,group_logo,group_join_type,group_time,tag,group_type,group_type_id) values($user_id,1,'$group_name','$group_resume','$user_name','$thumb_src',$group_join_type,NOW(),'$tag','$group_type_name',$group_type_id)";
if($dbo->exeUpdate($sql)){
	$last_id=mysql_insert_id();
}

//标签功能
$tag_id=tag_add($tag);
$tag_state=tag_relation(2,$tag_id,$last_id);

//新鲜事
$title=$g_langpackage->g_create_group.'<a href="home.php?h='.$user_id.'&app=group_space&group_id='.$last_id.'" target="_blank">'.$group_name.'</a>';
$content='<a href="home.php?h='.$user_id.'&app=group_space&group_id='.$last_id.'" target="_blank">'.$group_name.'</a>';
$is_suc=api_proxy("message_set",0,$title,$content,0,1);

//更新users表
$sess_creat_group=empty($sess_creat_group)? ",".$last_id.",":$sess_creat_group.$last_id.",";
$sql="update $t_users set creat_group='$sess_creat_group' where user_id=$user_id";
$dbo->exeUpdate($sql);

//插入group_members数据表
$sql="insert into $t_group_members (group_id,user_id,user_name,user_sex,state,role,add_time,user_ico) values($last_id,$user_id,'$user_name','$sess_user_sex',1,0,NOW(),'$userico')";
$dbo->exeUpdate($sql);

//更新session
set_sess_cgroup($sess_creat_group);

//回应信息
action_return(1,'',"");

?>