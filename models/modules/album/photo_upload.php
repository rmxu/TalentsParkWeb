<?php
	//引入语言包
	$a_langpackage=new albumlp;

	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//限制时间段访问站点
	limit_time($limit_action_time);

	//引入模块公共方法文件
	require("foundation/module_album.php");

	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();

	//变量定义区
	$t_online = $tablePreStr."online";
	$t_album = $tablePreStr."album";
	
	if($album_id==''){
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		$sql="select count(*) as a_num from $t_album where user_id=$user_id";
		$album_num=$dbo->getRow($sql);
		if(!$album_num['a_num']){
			echo '<script type="text/javascript">window.location.href="modules.php?app=album_edit";</script>';
		}
	}
	
	$session_code=md5(rand(0,10000));

	$sess_code_str=$session_code."|".$user_id;

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$sql="update $t_online set session_code = '$session_code' where user_id=$user_id";
	$dbo->exeUpdate($sql);

	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id,500);
?>