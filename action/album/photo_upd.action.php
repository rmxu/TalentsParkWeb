<?php
	//引入语言包
	$a_langpackage=new albumlp;

	//变量取得
	$album_id=intval(get_argg('album_id'));
	$photo_id=array();
 	$photo_information=array();
 	$album_skin=short_check(get_argp('album_skin'));
 	$photo_id=get_argp('photo_id');
 	$photo_information=get_argp('photo_information');
 	$photo_name=get_argp('photo_name');
	$user_id=get_sess_userid();

	//变量定义区
	$t_album = $tablePreStr."album";
	$t_photo = $tablePreStr."photo";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//添加图片信息
  foreach($photo_id as $id){
  	$information=each($photo_information);
  	$name=each($photo_name);
  	$id = intval($id);
  	$information = long_check($information['value']);
  	$name = short_check($name['value']);
  	$sql = "update $t_photo set photo_information = '$information',photo_name='$name' where photo_id=$id";
		$dbo -> exeUpdate($sql);
	}
	if(!empty($album_skin)){
		$sql="update $t_album set album_skin = '$album_skin' where album_id=$album_id";
		$dbo->exeUpdate($sql);
  }  
	//回应信息
	action_return(1,"","modules.php?app=album");
?>
