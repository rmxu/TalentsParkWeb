<?php
require('api/base_support.php');
//引入语言包
$u_langpackage=new userslp;

$t_users = $tablePreStr."users";
$email=get_argg('email');
$code=trim(get_argg('code'));
$user_id=get_argg('uid');

if($email=='' || $code=='' || $user_id==''){
	echo $u_langpackage->u_parameter_missing;exit;
}
$user_info=api_proxy("user_self_by_uid","forget_pass,user_email",$user_id);
if(empty($user_info)){
	echo $u_langpackage->u_user_info_not_exist;exit;
}
$user_email=$user_info['user_email'];
$user_forget_pass=$user_info['forget_pass'];
if($user_email==$email && $user_forget_pass==$code){
	set_session('forgeter',$user_id);
	set_session('forgetcode',$code);
}else{
	echo $u_langpackage->u_info_not_match;exit;
}
?>