<?php
//引入语言包
$rf_langpackage=new recaffairlp;
//引入提醒模块公共函数
require("api/base_support.php");
$remind_rs=api_proxy("message_get","remind",1,"*");//取得空间提醒
$content_data_none="content_none";
$isNull=0;
if(empty($remind_rs)){
	$isNull=1;
	$content_data_none="";
}

?>