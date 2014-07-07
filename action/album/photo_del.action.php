<?php
	//引入语言包
	$a_langpackage=new albumlp;
	require("api/base_support.php");
	require("foundation/aintegral.php");

	//变量取得
	$photo_id = intval(get_argg('photo_id'));
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";

	$album_row = api_proxy("album_self_by_aid","album_skin",$album_id);
	$photo_row = api_proxy("album_photo_by_photoid","photo_thumb_src,photo_src",$photo_id);

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//删除照片
	if($album_row['album_skin']==$photo_row['photo_thumb_src']){
		$album_skin = 'uploadfiles/album/logo.jpg';
		$sql = "update $t_album set album_skin = '$album_skin' where album_id=$album_id";
		$dbo -> exeUpdate($sql);
	}
	@unlink($photo_row['photo_src']);
	@unlink($photo_row['photo_thumb_src']);

	$sql = "delete from $t_photo where photo_id=$photo_id and user_id=$user_id";

	if($dbo -> exeUpdate($sql)){
		increase_integral($dbo,$int_del_photo,$user_id);
	}

	$sql = "update $t_album set photo_num=photo_num-1,update_time=NOW() where album_id=$album_id";
	$dbo -> exeUpdate($sql);

	//回应信息
	action_return(1,"","modules.php?app=photo_list&album_id=$album_id");
?>
