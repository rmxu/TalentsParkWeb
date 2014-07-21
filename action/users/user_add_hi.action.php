<?php
	//引入语言包
	$hi_langpackage=new hilp;
	require("api/base_support.php");
	
	
	//变量取得
	$hi = short_check(get_argg('hi_t'));
	$from_user_id = get_sess_userid();
	$from_user_name = get_sess_username();
	$from_user_ico = get_sess_userico();
	$to_user_id = intval(get_argg('to_userid'));
	
	if($to_user_id==$from_user_id){
		echo $hi_langpackage->hi_no_self;
		exit;
	}

	//数据表定义区
	$t_hi = $tablePreStr."hi";
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	$sql="select add_time from $t_hi where from_user_id = $from_user_id and to_user_id = $to_user_id ";
	$hi_row=$dbo->getRow($sql);
	if(date("Y-m-d",strtotime($hi_row['add_time']))==date("Y-m-d",time())){
		echo $hi_langpackage->hi_limit;
		exit;		
	}
	$sql="insert into $t_hi(from_user_id,from_user_name,from_user_ico,hi,to_user_id,add_time) value($from_user_id,'$from_user_name','$from_user_ico',$hi,$to_user_id,now())";
	if($dbo->exeUpdate($sql)){
		api_proxy("message_set",$to_user_id,$hi_langpackage->hi_remind,"modules.php?app=user_hi",0,4,"remind");
		echo "success";
	}else{
		echo $hi_langpackage->hi_los;
	}
	
?>