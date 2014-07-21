<?php
	//引入语言包
	$a_langpackage=new albumlp;

	//变量取得
	$album_id=intval(get_argg('album_id'));
	$album_name = short_check(get_argp('album_name'));
	$album_information = long_check(get_argp('album_information'));
	$tag = short_check(get_argp('tag'));
	$privacy=short_check(get_argp('privacy'));
	$user_id = get_sess_userid();

	//数据表定义区
	$t_album = $tablePreStr."album";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	$sql = "update $t_album set `album_name`='$album_name',`user_id`=$user_id,`album_info`='$album_information',`tag`='$tag',`update_time`=NOW(),`privacy`='$privacy' where album_id=$album_id";
	$dbo -> exeUpdate($sql);
	//回应信息
	action_return(1,"","");
?>
