<?php
//权限控制，必须登录
$is_self_mode='';
$is_login_mode='accessLimit';
$ses_uid=get_sess_userid();
$lim_rdurl="login";
require("foundation/auser_validate.php");
?>