<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_pw_reset.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_pw_reset.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/layout.css">
<script language="javascript" src="servtools/ajax_client/ajax.js"></script>
<script language="javascript" src="skin/default/js/jooyea.js"></script>
</head>
<body>
<?php require('uiparts/guestheader.php');?>
<div class="forget_box">
<form action='do.php?act=user_pw_change&forget_pws=1' method='post'>
 <h2><?php echo $u_langpackage->u_set_new_password;?>！</h2>
	<p style="padding-left:123px"><?php echo $u_langpackage->u_new_pw;?>：<input type='password' name='new_pw' /></p>
	<p style="padding-left:123px"><?php echo $u_langpackage->u_repeat_pw;?>：<input type='password' name='new_pw_repeat' /></p>
	<p><input  class="button" type='submit' value='<?php echo $u_langpackage->u_modify;?>' /></p>
</form>
</div>
<?php require('uiparts/footor.php');?>
</body>
</html>