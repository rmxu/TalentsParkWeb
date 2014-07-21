<?php
//引入语言包
$a_langpackage=new albumlp;

//必须登录才能浏览该页面
require("foundation/auser_mustlogin.php");

//限制时间段访问站点
limit_time($limit_action_time);

//变量取得
$album_id=intval(get_argg('album_id'));

//数据表定义区
$t_album = $tablePreStr."album";

if($album_id){
	require("api/base_support.php");
	$album_row = api_proxy("album_self_by_aid","*",$album_id);
	$album_id=$album_row['album_id'];
	$album_name=$album_row['album_name'];
	$tag=$album_row['tag'];
	$album_info=$album_row['album_info'];
	$album_pri=$album_row['privacy'];
	$act_url="do.php?act=album_upd&album_id=".$album_id;
	$act_str=$a_langpackage->a_b_upd;
	$top_str=$a_langpackage->a_edit;
}else{
	$act_url="do.php?act=album_creat";
	$act_str=$a_langpackage->a_b_crt;
	$album_pri="";
	$album_info="";
	$album_name="";
	$tag="";
	$album_id="";
	$top_str=$a_langpackage->a_creat;
}
?>