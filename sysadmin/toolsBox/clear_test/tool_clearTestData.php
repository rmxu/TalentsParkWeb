<?php
	require("toolsBox/clear_test/ftool_clearTestData.php");
	//语言包引入
	$t_langpackage=new toollp;
	$dbo = new dbex;
	dbtarget('w',$dbServs);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/right.css">
</head>
<body>
<div class="container">
	<div class="rs_head">删除测试信息</div>
</div>
<table class='main main_left'>
	<tr>
		<td><?php echo del_user($dbo,'');?></td>
	</tr>	
	<tr>
		<td><input type='button' class='top_button' value='返回上级菜单' onclick='window.history.go(-1);' /></td>
	</tr>
<table>
</body>
</html>			