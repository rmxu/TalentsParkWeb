<?php
//引入语言包
	$mp_langpackage=new mypalslp;
	
	$mypals_id=intval(get_argg("mypals_id"));
	$sort_id=intval(get_argg("sort_id"));
	$user_id=get_sess_userid();
	$my_pals=get_sess_mypals();
	$my_pals=",".$my_pals.",";
	
	$mypals=preg_replace("/,$mypals_id,/",",",$my_pals);
	$mypals=preg_replace(array("/^,/","/,$/"),array("",""),$mypals);

//数据表定义区
		$t_users=$tablePreStr."users";
		$t_mypals=$tablePreStr."pals_mine";
		$t_pals_sort=$tablePreStr."pals_sort";

//定义读操作
		dbtarget('w',$dbServs);
		$dbo=new dbex();
   		$sql="delete from $t_mypals where pals_id=$mypals_id and user_id=$user_id"; 
		if($dbo->exeUpdate($sql)){
			$sql="update $t_mypals set accepted=1 where user_id=$mypals_id and pals_id=$user_id";
	 		$dbo->exeUpdate($sql);
		
			$sql="update $t_pals_sort set count=count-1 where id=$sort_id";
			$dbo->exeUpdate($sql);
		}
		set_sess_mypals($mypals);
		action_return(0,'',"");
?>
