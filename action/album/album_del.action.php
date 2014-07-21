<?php
	//引入语言包
	$a_langpackage=new albumlp;
	
	require("foundation/module_affair.php");
	require("api/base_support.php");

	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();

	//删除相册功能
	//数据表定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";
	$t_photo_comment = $tablePreStr."photo_comment";
	$t_album_comment = $tablePreStr."album_comment";

	$dbo = new dbex;
	//读写分离定义方法
	dbtarget('r',$dbServs);

	$photo_rs = api_proxy("album_photo_by_aid","*",$album_id);

	dbtarget('w',$dbServs);
	$photo_num=0;
	foreach($photo_rs as $val){
		@unlink($val['photo_src']);
		@unlink($val['photo_thumb_src']);

		//删除照片相关评论
		$photo_id = $val['photo_id'];
		$sql = "delete from $t_photo_comment where photo_id =$photo_id and user_id=$user_id";
		$dbo -> exeUpdate($sql);
		$photo_num++;
	}

	//删除相册有关照片
	$sql = "delete from $t_photo where album_id=$album_id and user_id=$user_id";
	$dbo -> exeUpdate($sql);

	//删除相册相关评论
	$sql = "delete from $t_album_comment where album_id=$album_id and user_id=$user_id";
	$dbo -> exeUpdate($sql);

	//删除相册
	$sql="delete from $t_album where album_id=$album_id and user_id=$user_id";

	if($dbo -> exeUpdate($sql)){
	  del_affair($dbo,2,$album_id);
	}
	//回应信息
	action_return(1,"","");
?>