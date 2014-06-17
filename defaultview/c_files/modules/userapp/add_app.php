<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/userapp/add_app.html
 * 如果您的模型要进行修改，请修改 models/modules/userapp/add_app.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入语言包
$pl_langpackage=new pluginslp;
require("api/base_support.php");

//变量区
$id=intval(get_argg('id'));
$app_rs=api_proxy("plugins_get_pid",$id);

//默认plugins图像地址
$def_image="skin/".$skinUrl."/images/plu_def.jpg";
$def_small_image="skin/".$skinUrl."/images/plu_def_small.gif";
$image_url=$app_rs['image'] ? "plugins/".$app_rs['name']."/".$app_rs['image']:$def_image;
$small_url=$app_rs['image'] ? preg_replace("/([^\.]+)(\.\w+)/","$1_small$2","plugins/".$app_rs['name']."/".$app_rs['image']):$def_small_image;

//取得已经有的应用
$u_apps=get_sess_apps();
if(strpos(",,$u_apps,",",$id,")){
	echo '<script type="text/javascript">location.href="plugins/'.$app_rs['name'].'/'.$app_rs['url'].'";</script>';
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=mag_app"><?php echo $pl_langpackage->pl_return_mine;?></a></div>
    <h2 class="app_set"><?php echo $pl_langpackage->pl_add_app;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:;"><?php echo $pl_langpackage->pl_add;?><?php echo $app_rs["title"];?></a></li>
        </ul>
    </div>
	<form action='do.php?act=add_app&id=<?php echo $app_rs["id"];?>' method='post'>
    <div class="rs_head"><?php echo $pl_langpackage->pl_add;?><a href="javascript:document.forms[0].submit()" title="<?php echo $app_rs["title"];?>"><?php echo $app_rs["title"];?></a>，<?php echo $pl_langpackage->pl_add_hint;?></div>
    <ul class="app_list">
   		<li onmouseover="this.className += ' list_app_active';" onmouseout="this.className='app_list';">
            <div class="figure_box"><img src="<?php echo $image_url;?>" /></div>
            <h3 class="name"><a href="javascript:document.forms[0].submit()" title="<?php echo $app_rs['title'];?>"><?php echo $app_rs['title'];?></a></h3>
            <p class="description"><?php echo $app_rs["info"];?><br /><br /><input id="choose" value="1" checked="checked" style="vertical-align:middle" name="is_affair" type="checkbox"><label for="choose"><?php echo $pl_langpackage->pl_tell_pals;?></label></p>
            <div class="app_control" style="display:block">
            	<a href="javascript:document.forms[0].submit()" title="<?php echo $pl_langpackage->pl_agree;?>"><?php echo $pl_langpackage->pl_enter_app;?></a> <a href="javascript:history.go(-1)" title="<?php echo $pl_langpackage->pl_cancel;?>"><?php echo $pl_langpackage->pl_cancel;?></a>
            </div>
        </li>
    </ul>

	</form>
</body>
</html>
