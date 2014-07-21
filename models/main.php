<?php
header("content-type:text/html;charset=utf-8");
require("foundation/asession.php");
require("configuration.php");
require("includes.php");
//必须登录才能浏览该页面
require("foundation/auser_mustlogin.php");
require("foundation/module_users.php");
require("foundation/fplugin.php");
//语言包引入
$u_langpackage=new userslp;
$mn_langpackage=new menulp;
$pu_langpackage=new publiclp;
$mp_langpackage=new mypalslp;
$s_langpackage=new sharelp;
$hi_langpackage=new hilp;
$l_langpackage=new loginlp;
$rp_langpackage=new reportlp;
$ah_langpackage=new arrayhomelp;

$user_id=get_sess_userid();
$user_name=get_sess_username();
?>