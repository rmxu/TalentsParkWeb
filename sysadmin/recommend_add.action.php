<?php
require("session_check.php");
require("../api/Check_MC.php");
	$is_check=check_rights("c07");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	
	//数据表定义区
	$t_recommend=$tablePreStr."recommend";
	$t_users=$tablePreStr."users";

	//变量区
	$user_name = short_check(get_argp('uname'));
	$user_ico = short_check(get_argg('uico'));
	$is_pass=short_check(get_argg('upass'));
	$user_id = intval(get_argg('uid'));
	$guest_num=intval(get_argg('gnum'));
	$user_sex = intval(get_argg('usex'));

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$sql="insert into $t_recommend (user_id,user_name,user_ico,is_pass,guest_num,user_sex,show_ico) "
		."values ('$user_id','$user_name','$user_ico','$is_pass','$guest_num','$user_sex','$user_ico')";
		
	$sql1="update $t_users set is_recommend=1 where user_id=$user_id";
	if($dbo->exeUpdate($sql)&&$dbo->exeUpdate($sql1)){
		$key_mt='recommend/list/rec_order/all/0_mt';
		updateCache($key_mt);
		
		echo $m_langpackage->m_recomed;
	}else{
		echo $m_langpackage->m_recomed_lose;
	}
?>