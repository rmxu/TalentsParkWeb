<?php
require("session_check.php");
require("atool_box.php");
//当前可访问的应用工具
$appArray=array(
	"start"=>'modules/start.php',
	"hstart"=>'modules/homestart.php',
);
$appArray=array_merge($appArray,$tools_box_array);
$appId=get_argg('app');
$apptarget=$appArray[$appId];
if(isset($apptarget)){
	require($apptarget);
}
else{
	echo 'no pages!';
}

?>