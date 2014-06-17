<?php
	//引入语言包
	$a_langpackage=new albumlp;
	require("api/base_support.php");

	//变量取得
	$photo_id = intval(get_argg('photo_id'));
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);

	$photo_row = api_proxy("album_photo_by_photoid","photo_thumb_src",$photo_id);

	//读写分离定义函数
	dbtarget('w',$dbServs);

	//设置封面
	$sql = "update $t_album set album_skin = '$photo_row[photo_thumb_src]' where album_id=$album_id and user_id=$user_id";
	$dbo -> exeUpdate($sql);

	//回应信息
	action_return(1,"","modules.php?app=photo_list&album_id=$album_id");
?>
