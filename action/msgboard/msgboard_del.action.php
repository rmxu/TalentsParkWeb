<?php
	require("foundation/aintegral.php");
	require("api/Check_MC.php");

	//引入语言包
	$mb_langpackage=new msgboardlp;

	//变量取得
	$to_user_id = intval(get_argg('user_id'));
	$mess_id = intval(get_argg('mess_id'));
	$from_user_id=get_sess_userid();

	//数据表定义区
	$t_message = $tablePreStr."msgboard";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//评论相册
	$sql = "delete from $t_message where mess_id=$mess_id ";
	if($dbo -> exeUpdate($sql)){
		increase_integral($dbo,$int_del_com_msg,$to_user_id);
	}
	//回应信息
	action_return(1,"","-1");
?>
