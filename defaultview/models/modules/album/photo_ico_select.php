<?php
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
?>