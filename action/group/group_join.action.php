<?php
//引入模块公共方法文件
require("api/base_support.php");

//引入语言包
$g_langpackage=new grouplp;

//变量定义区
$user_id=get_sess_userid();
$user_name=get_sess_username();
$user_sex=get_sess_usersex();
$user_join_group=get_sess_jgroup();
$user_creat_group=get_sess_cgroup();
$userico=get_sess_userico();
$group_id=intval(get_argg('group_id'));

//表定义
$t_users=$tablePreStr."users";
$t_groups=$tablePreStr."groups";
$t_group_members=$tablePreStr."group_members";

if(empty($user_id)){echo $g_langpackage->g_false;exit;}

//读定义
dbtarget('r',$dbServs);
$dbo=new dbex;

//判断是否已经提交了申请
$sql="select state from $t_group_members where user_id=$user_id and group_id=$group_id";
$is_reg=$dbo->getRow($sql);

if($is_reg['state']>=1){
	echo $g_langpackage->g_rep_join;
	exit();
}
if(isset($is_reg['state'])){
	echo $g_langpackage->g_rep_reg;
	exit();
}

//取得群组加入方式
$group_row=api_proxy("group_self_by_gid","group_join_type,group_name,is_pass,add_userid,group_manager_id,group_req_id",$group_id);

if($group_row['is_pass']==0){
	echo $g_langpackage->g_lock_group;
	exit();
}

//得到群组数据
$group_name=$group_row['group_name'];
$creat_id=$group_row['add_userid'];
$manager_id=$group_row['group_manager_id'];

if($group_row['group_join_type']==2){
	echo $g_langpackage->g_refuse_join;
	exit();
}

if($group_row['group_join_type']==0){
	$user_join_group=empty($user_join_group)? ",".$group_id.",":$user_join_group.$group_id.",";
	//写定义
	dbtarget('w',$dbServs);
	//更新用户表
	$sql="update $t_users set join_group='$user_join_group' where user_id=$user_id";
	$dbo->exeUpdate($sql);
	//插入群组关系表
	$sql="insert into $t_group_members (group_id,user_id,user_name,user_sex,state,role,add_time,user_ico) values ($group_id,$user_id,'$user_name','$user_sex',1,2,NOW(),'$userico')";
	if($dbo->exeUpdate($sql)){
		//增加群组人数
		$sql="update $t_groups set member_count=member_count+1 where group_id=$group_id";
		$dbo->exeUpdate($sql) or die('false');
	}

	//纪录新鲜事
	$title=$g_langpackage->g_joined_group.'<a href="home.php?h='.$user_id.'&app=group_space&group_id='.$group_id.'" target="_blank">'.$group_name.'</a>';
	$content='<a href="home.php?h='.$user_id.'&app=group_space&group_id='.$group_id.'" target="_blank">'.$group_name.'</a>';
	$is_suc=api_proxy("message_set",0,$title,$content,0,1);

	//更新session
	set_sess_jgroup($user_join_group);
	echo $g_langpackage->g_join_suc;
	exit();
}

if($group_row['group_join_type']==1){
	dbtarget('w',$dbServs);
	$g_req_id=empty($group_row['group_req_id'])?",".$user_id.",":$group_row['group_req_id'].$user_id.",";
	$sql="update $t_groups set group_req_id='$g_req_id' where group_id=$group_id";
	$dbo->exeUpdate($sql);
	$sql="insert into $t_group_members(group_id,user_id,user_name,user_sex,state,role,add_time,user_ico) values($group_id,$user_id,'$user_name','$user_sex',0,2,NOW(),'$userico')";
	$dbo->exeUpdate($sql);
	$g_manager=api_proxy("group_member_manager","user_id",$group_id);
	foreach($g_manager as $val){
		api_proxy("message_set",$val['user_id'],$g_langpackage->g_remind,"modules.php?app=group_member_manager&group_id=".$group_id,0,10,"remind");//提醒机制
	}
	echo $g_langpackage->g_reg_suc;
	exit();
}
?>