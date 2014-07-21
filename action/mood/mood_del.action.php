<?php
	//引入语言包
	$mo_langpackage=new moodlp;
	require("foundation/module_affair.php");

	//变量取得
	$mood_id = intval(get_argg('mood_id'));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_mood = $tablePreStr."mood";
	$t_mood_com=$tablePreStr."mood_comment";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//删除心情
	$sql = "delete from $t_mood where mood_id=$mood_id and user_id=$user_id";
	$dbo->exeUpdate($sql);

	$sql="delete from $t_mood_com where mood_id=$mood_id";
	$dbo->exeUpdate($sql);

	del_affair($dbo,6,$mood_id);

	//回应信息
	action_return(1,"","-1");
?>