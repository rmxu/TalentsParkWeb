<?php
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");
//引入语言包
$u_langpackage=new userslp;

$t_users = $tablePreStr."users";
$email=get_argp('email');
$user_vericode=get_argp("veriCode");
if(strtolower($_SESSION['verifyCode'])!=strtolower($user_vericode)){
	action_return(0,$u_langpackage->u_code_error,"-1");
}
unset($_SESSION['verifyCode']);
$dbo = new dbex;
dbtarget('r',$dbServs);
$sql="select user_id,user_name from $t_users where user_email='$email'";
$user_row=$dbo->getRow($sql);

if($user_row){
	$code=md5($email.time());
	$mailbody = $u_langpackage->u_dear.$user_row['user_name'].$u_langpackage->u_user.'：<br />'.$u_langpackage->u_hello.'<br />'.$u_langpackage->u_you_as.$siteName.$u_langpackage->u_click_link_amended.'<br />
							<a href="'.$siteDomain.'modules.php?app=user_pw_reset&uid='.$user_row['user_id'].'&email='.$email.'&code='.$code.'">'.$siteDomain.'modules.php?app=user_pw_reset&uid='.$user_row['user_id'].'&email='.$email.'&code='.$code.'</a>';

	$mailtitle = $siteName.$u_langpackage->u_forgot_password;

	$email_array=explode('@',$email);
	$email_site=strtolower($email_array[1]);

	$utf8_site=array("hotmail.com","gmail.com");
	if(!in_array($email_site,$utf8_site)){
		$mailbody = iconv('UTF-8','GBK',$mailbody);
		$mailtitle = iconv('UTF-8','GBK',$mailtitle);
	}
	$smtp = new smtp($smtpAddress,$smtpPort,true,$smtpUser,$smtpPassword);
	$is_success=$smtp->sendmail($email,$smtpEmail,$mailtitle,$mailbody,'HTML');

	$sql="update $t_users set `forget_pass`='$code' where `user_email`='$email'";
	$dbo->exeUpdate($sql);
	
	if($is_success){
		action_return(1,$u_langpackage->u_sent_successfully,$indexFile);
	}else{
		action_return(0,$u_langpackage->u_send_failed,'-1');
	}
}else{
	action_return(0,$u_langpackage->u_not_find_user_data,-1);
}
?>