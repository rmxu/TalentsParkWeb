<?php
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

?>