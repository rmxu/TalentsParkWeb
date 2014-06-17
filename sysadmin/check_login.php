<?php
header("content-type:text/html;charset=utf-8");
require("../foundation/asession.php");
require("../configuration.php");
require("includes.php");

//语言包引入
$l_langpackage=new loginlp;

//表定义区
$t_admin=$tablePreStr."admin";
$t_backgroup=$tablePreStr."backgroup";

$admin_name=short_check(get_argp('admin_name'));
$admin_password=short_check(get_argp('admin_password'));
$dbo = new dbex;
dbtarget('w',$dbServs);
$sql=" select * from $t_admin where admin_name='$admin_name' and is_pass=1 ";
$admin_info=$dbo->getRow($sql);
if($admin_info['admin_name']==$admin_name&&$admin_password==$admin_info['admin_password']){
	echo "<script type='text/javascript' src='servtools/rpc.js'></script>";
	set_session('admin_id',$admin_info['admin_id']);
	set_session('admin_group',$admin_info['admin_group']);
	if($admin_info['admin_group']!='superadmin'){
		$sql="select rights from $t_backgroup where gid='$admin_info[admin_group]'";
		$rights=$dbo->getRow($sql);
		if($rights){
			set_session('rights',$rights['rights']);
		}
	}
	set_sess_admin($admin_info['admin_name']);sleep(1);
	echo "<script type='text/javascript'>window.location.href='main.php';</script>";
}else{
	echo "<script type='text/javascript'>alert('$l_langpackage->l_mismatch');window.location.href='login.php';</script>";exit();
}
?>