<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/helloword/helloword_list.html
 * 如果您的模型要进行修改，请修改 models/modules/helloword/helloword_list.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//数据表定义区
$ses_uid=get_sess_userid();
$is_self_mode='partLimit';
require("foundation/auser_validate.php");
require("foundation/fpages_bar.php");
//数据表定义区
$t_hello = $tablePreStr."helloWord";
$dbo=new dbex();
//读写分离定义方法
dbtarget('r',$dbServs);
$sql="select * from $t_hello";
$hello_list= $dbo->getRs($sql);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/row_menus.css">
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/right.css">
</head>
<body ><h1 style="text_align:center">HelloWord</h1>
<div class='main_right_title'>
<table width="100%">
<tr><td>编号</td><td>信息</td><td>操作</td></tr>
<?php foreach($hello_list as $rs){?>
<tr><td><?php echo $rs['id'];?></td><td><?php echo $rs['msg'];?></td><td><a
href="do.php?act=hello_del&hello_id=<?php echo $rs['id'];?>">删除</a></td></tr>
<?php }?>
</table>
</div>
<form action="do.php?act=hello_add" method="post">
信息:<input name="hello_msg"/><br/>
<input type="submit" value="提交"/>
</form>
</body>
</html>