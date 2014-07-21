<?php
	//引入语言包
	$u_langpackage=new userslp;

	//变量获得
	$user_id =get_sess_userid();
	$model = short_check(get_argg('model'));
	$marry = short_check(get_argp('marry'));
	$birth_year = short_check(get_argp('birth_year'));
	$birth_month = short_check(get_argp('birth_month'));
	$birth_day = short_check(get_argp('birth_day'));
	$blood = short_check(get_argp('blood'));
	$reside_city = short_check(get_argp('reside_city'));
	$reside_province = short_check(get_argp('reside_province'));
	$birth_city = short_check(get_argp('birth_city'));
	$birth_province = short_check(get_argp('birth_province'));
	$qq = short_check(get_argp('qq'));
	
	$is_finish=intval(get_argg('is_finish'));
	
	//表声明区
	$t_users = $tablePreStr."users";
	$t_online=$tablePreStr."online";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

//更新users表
	$sql = "update $t_users set birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',user_marry='$marry',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day',user_blood='$blood',user_qq='$qq'
			where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

//更新online表
	$sql = "update $t_online set birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',birth_year='$birth_year' where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

	//回应信息
	action_return(1,"","modules.php?app=user_info&single=$is_finish&user_id=$user_id");
?>
