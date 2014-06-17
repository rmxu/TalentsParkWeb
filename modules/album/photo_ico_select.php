<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/photo_ico_select.html
 * 如果您的模型要进行修改，请修改 models/modules/album/photo_ico_select.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
require($webRoot."/foundation/fcontent_format.php");
require($webRoot."/foundation/module_album.php");
require($webRoot."/api/base_support.php");

//引入语言包
$a_langpackage=new albumlp;

//变量取得
$album_id = intval(get_argg('album_id'));
$url_uid=intval(get_argg('user_id'));
$type=short_check(get_argg('type'));

$photo_rs=array();
$photo_rs=api_proxy("album_photo_by_aid","*",$album_id);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body>
<?php foreach($photo_rs as $val){?>
<?php $size=getimagesize($webRoot.$val['photo_thumb_src']);?>
<div class="album_photo_box">
    <a href="<?php echo do_url($type,$val['photo_src'],$url_uid);?>" hidefocus="true"><?php src_wh($size[0],$size[1],$siteDomain.$val['photo_thumb_src']);?></a>
</div>
<?php }?>
<?php if(empty($photo_rs)){?>
	<div class="guide_info">
		<?php echo $a_langpackage->a_no_upl;?>,<a href='modules.php?app=photo_upload&album_id=<?php echo $album_id;?>'><?php echo $a_langpackage->a_upl_pht;?></a>
	</div>
<?php }?>
</body>
</html>