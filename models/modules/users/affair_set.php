<?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;
	$pr_langpackage=new privacylp;
	$user_id=get_sess_userid();

	//表声明区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";

	$dbo=new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);

	$type_array=array();
	for($def_num=0;$def_num<=6;$def_num++){
		$type_array[]=$pr_langpackage->{'pr_type_'.$def_num};
	}

	$mypals = getPals_mine_all($dbo,$t_mypals,$user_id);

	$whole_type=",1,2,3,4,5,6,";
	$user_row = api_proxy("user_self_by_uid","hidden_pals_id,hidden_type_id",$user_id);
	$hidden_p=$user_row['hidden_pals_id'];
	$hidden_t=$user_row['hidden_type_id'];
	$hidden_pals_rs=array();
	$hidden_type_rs=array();

	if(!empty($hidden_p) && $hidden_p!=","){
		$hidden_pals_rs = api_proxy("user_self_by_uid","user_id,user_name",$hidden_p);
	}

	if(!empty($hidden_t) && $hidden_t!=","){
		$hidden_type_array=explode(",",$hidden_t);
		foreach($hidden_type_array as $value){
			if($value!=''){
				$hidden_type_rs[$value]=$pr_langpackage->{'pr_type_'.$value};
			}
		}
	}

?>