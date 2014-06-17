<?php
	//引入语言包
	$a_langpackage=new albumlp;

	//变量取得
	$photo_id = intval(get_args('photo_id'));
	$information=long_check(get_argp('information_value'));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_photo = $tablePreStr."photo";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//更改图片信息
	$sql = "update $t_photo set photo_information='$information' where photo_id=$photo_id and user_id=$user_id";
	$dbo->exeUpdate($sql);
	echo filt_word($information);
?>
