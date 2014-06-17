<?php
	require("toolsBox/clear_test/ftool_clearTestData.php");
	//语言包引入
	$t_langpackage=new toollp;
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$uid=intval(get_argp('user_id'));
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
<form action='' method='post'>
<table class='main main_left'>
	<?php if($uid==0){?>
	<tr>
		<td>输入要删除用户的id值:<input type='text' name='user_id' /><input type='submit' value='删除' class='top_button' /></td>
	</tr>

	<?php }else{
				echo del_user($dbo,$uid);
	}
	?>
	<tr>
		<td><input type='button' class='top_button' value='返回上级菜单' onclick='window.history.go(-1);' /></td>
	</tr>	
<table>
</form>
</body>
</html>