<?php
//引入语言包
$l_langpackage=new loginlp;
$re_langpackage=new reglp;

require("foundation/module_mypals.php");
require("foundation/aintegral.php");

if(get_argp("u_email")==NULL||get_argp("u_email")=="您的 Email"){
	echo 'emailmsg|'.$l_langpackage->l_empty_mail;
	exit();
}

if(!login_check(get_argp("u_email"))){
	echo 'emailmsg|'.$re_langpackage->re_right_email;
	exit();
}

if(get_argp("u_pws")==NULL){
	echo 'pwdmsg|'.$l_langpackage->l_empty_pass;
	exit();
}

$u_email=short_check(get_argp("u_email")); //用户名已经记录了
$user_pws=md5(get_argp("u_pws")); //密码已经记录了
$hidden=intval(get_argp('hidden'));//登录方式

//数据表定义区
$t_users=$tablePreStr."users";
$t_group_members=$tablePreStr."group_members";
$t_online=$tablePreStr."online";
$t_mypals=$tablePreStr."pals_mine";
$t_frontgroup=$tablePreStr."frontgroup";

//定义读操作
dbtarget('r',$dbServs);
$dbo=new dbex;
$sql="select * from $t_users where user_email='$u_email'";
$user_info=$dbo->getRow($sql);

if(empty($user_info)){
	echo 'emailmsg|'.$l_langpackage->l_not_check;
	exit();
}

$get_pws=$user_info['user_pws'];

if($get_pws!=$user_pws){
	echo 'pwdmsg|'.$l_langpackage->l_wrong_pass;
	exit();
}
if($user_info['is_pass']==0){
	echo 'emailmsg|'.$l_langpackage->l_lock_u;
	exit();
}
$mypals=getMypals($dbo,$user_info['user_id'],$t_mypals);
set_sess_mypals($mypals);
set_sess_username($user_info['user_name']);
set_sess_userid($user_info['user_id']);
set_sess_usersex($user_info['user_sex']);
set_sess_cgroup($user_info['creat_group']);
set_sess_jgroup($user_info['join_group']);
set_sess_userico($user_info['user_ico']);
set_session('hidden_pals',$user_info['hidden_pals_id']);
set_session('hidden_type',$user_info['hidden_type_id']);
set_sess_plugins($user_info['use_plugins']);
set_sess_apps($user_info['use_apps']);
set_sess_online($hidden);
set_session($user_info['user_id']."_dressup",$user_info['dressup']);
$sql="select * from $t_frontgroup where gid='$user_info[user_group]'";
$rights=$dbo->getRow($sql);
if($rights)set_sess_rights($rights['rights']);
else  set_sess_rights("");

//定义写操作
dbtarget('w',$dbServs);
$now_time=time();

$last_data=date("Y-m-d",strtotime($user_info['lastlogin_datetime']));
$now_data=date("Y-m-d",$now_time);

if($last_data!=$now_data){
	increase_integral($dbo,$int_login,$user_info['user_id']);
}

$sql="delete from $t_online where user_id=$user_info[user_id]";
$dbo->exeUpdate($sql);
$sql="insert into $t_online (user_id,user_name,user_sex,user_ico,birth_province,birth_city,reside_province,reside_city,active_time,hidden,birth_year) values ".
"($user_info[user_id],'$user_info[user_name]',$user_info[user_sex],'$user_info[user_ico]','$user_info[birth_province]','$user_info[birth_city]','$user_info[reside_province]','$user_info[reside_city]',$now_time,$hidden,'$user_info[birth_year]')";
$dbo->exeUpdate($sql);
$sql="update $t_users set lastlogin_datetime=now(),login_ip='$_SERVER[REMOTE_ADDR]' where user_id=$user_info[user_id]";
$dbo->exeUpdate($sql);

if(get_sess_preloginurl()){
	 echo get_sess_preloginurl();
}else{
   echo $acttarget[1];
	 set_sess_preloginurl('');
}

?>