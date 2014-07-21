<?php
header("content-type:text/html;charset=utf-8");
require(dirname(__file__)."/../foundation/asession.php");
require(dirname(__file__)."/../configuration.php");
require(dirname(__file__)."/includes.php");
$is_admin=get_sess_admin();
if($is_admin=='')
{
	echo "<script type='text/javascript'>top.location.href='".$siteDomain."sysadmin/login.php';</script>";
	exit();
}
?>