<?php
//引入语言包
	$s_langpackage=new sharelp;
		
//引入公共方法
	require("foundation/fcontent_format.php");
	require("foundation/ainfo_collect.php");

//变量定义区

	$outer_link=get_argp('outer_link');
	
	$info_title=info_collect($outer_link);
		
	echo $info_title;
?>

