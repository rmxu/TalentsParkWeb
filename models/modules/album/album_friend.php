<?php
	//引入语言包
	$a_langpackage=new albumlp;
	require("foundation/auser_mustlogin.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	require("servtools/menu_pop/trans_pri.php");
	
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();
	$pals_id_str=get_sess_mypals();

	//数据表定义区
	$t_album = $tablePreStr."album";
	
	$dbo=new dbex;
	dbtarget('r',$dbServs);

	$page_num=intval(get_argg('page'));
	$page_total='';
	$album_rs=array();
	if($pals_id_str){
		$album_rs = api_proxy("album_self_by_uid","*",$pals_id_str);
	}
	
	$isNull=0;//不为空则设置为零
	$a_friend="";
	$t_fri="content_none";
	if(empty($album_rs)){
		$isNull=1;//判断结果集是否为空
		$a_friend="content_none";
		$t_fri="";		 	
	}
?>