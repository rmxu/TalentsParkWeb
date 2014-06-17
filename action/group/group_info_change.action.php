<?php
//引入语言包
$g_langpackage=new grouplp;

//变量区
$user_id=get_sess_userid();
$group_id=short_check(get_argg('group_id'));

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex;

//引入公共函数
	require("foundation/module_group.php");

//数据表定义
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";

//权限判断
$role=pri_limit($dbo,$user_id,$group_id);
if($role==2){
	action_return(0,$g_langpackage->g_no_privilege,"-1");
}

//定义写操作
	dbtarget('w',$dbServs);

	$group_name=short_check(get_argp('group_name'));
	$group_resume=short_check(get_argp('group_resume'));
	$group_join_type=intval(get_argp('group_join_type'));
	$group_type_id=intval(get_argp('group_type_id'));
	$group_type_name=short_check(get_argp('group_type_name'));
	$tag=short_check(get_argp('tag'));
	$gonggao=short_check(get_argp('affiche'));
	$is_pic="";

	if(isset($_FILES['attach']) && $_FILES['attach']['name'][0]!=''){
		$up = new upload();
		$up->set_dir('uploadfiles/group_logo/','{y}/{m}/{d}');//目录设置
		$fs = $up->execute();
		if($fs[0]['flag']==-1){
			action_return(0,$g_langpackage->g_logo_limit,"-1");
		}
		$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];
		@unlink(get_argp('old_group_logo'));
		$is_pic=",group_logo='$fileSrcStr'";
	}
	  $sql="update $t_groups set group_name='$group_name',affiche ='$gonggao',tag = '$tag',group_resume = '$group_resume',group_join_type = $group_join_type,group_type = '$group_type_name',group_type_id=$group_type_id $is_pic where group_id=$group_id";
		$dbo->exeUpdate($sql);
		$jump="modules.php?app=group_manager&group_id=$group_id";
	  action_return(1,'',$jump);
?>

