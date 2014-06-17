<?php
header("content-type:text/html;charset=utf-8");
require("../configuration.php");
require("../includes.php");

$error_type=isset($_GET['error_type']) ? $_GET['error_type'] : '';

//语言包引入
$er_langpackage=new errorlp;

$error_array = array(
	"dberr" => $er_langpackage->er_db_unset,
);

if(isset($error_array[$error_type])!=''){
	$show_error=$error_array[$error_type];
}else{
	$show_error=$er_langpackage->er_dont_know;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iweb_sns-系统错误提示</title>
<link href="error/error.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main"><img src="error/snslogo.gif"/>
<ul>
	<li><?php echo $show_error;?></li>
  <li><a href="http://tech.jooyea.com/bbs/forumdisplay.php?fid=21"><?php echo $er_langpackage->commit_bug;?></a></li>
</li>
</ul>
</div>
</body>
</html>