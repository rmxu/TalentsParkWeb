<?php
	//引入模块公共方法文件
	require("foundation/aanti_refresh.php");
	require("foundation/ftag.php");

	//引入语言包
	$a_langpackage=new albumlp;

	//变量取得
	$album_name = short_check(get_argp('album_name'));
	$album_information = short_check(get_argp('album_information'));
	$privacy=short_check(get_argp('privacy'));
	$user_id =get_sess_userid();
	$user_name = get_sess_username();
	$tag=short_check(get_argp('tag'));

	//防止重复提交
	antiRePost($album_name);
	
	if($album_name==''){
		action_return(1,"",-1);exit;
	}

	//数据表定义区
	$t_album = $tablePreStr."album";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	$album_skin = "uploadfiles/album/logo.jpg";
	$sql = "insert into $t_album (`album_name`,`user_id`,`user_name`,`album_info`,`add_time`,`privacy`,`album_skin`,`tag`,`update_time`) " .
			"values('$album_name',$user_id,'$user_name','$album_information',NOW(),'$privacy','$album_skin','$tag',NOW()); ";
	$dbo -> exeUpdate($sql);
	$album_id = mysql_insert_id();

	//标签功能
	$tag_id=tag_add($tag);
	$tag_state=tag_relation(0,$tag_id,$album_id);

	//回应信息
	action_return(1,"","modules.php?app=photo_upload&album_id=$album_id");
?>