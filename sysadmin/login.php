<?php
header("content-type:text/html;charset=utf-8");
require("../configuration.php");
require("includes.php");
	//语言包引入
	$l_langpackage=new loginlp;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type='text/javascript' src='../servtools/md5.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $l_langpackage->l_backend?></title>
<link rel="stylesheet" type="text/css" media="all" href="css/login.css" />
<script type='text/javascript'>
	function check_form(){
		var admin_name=document.getElementById("admin_name").value;
		var admin_password=document.getElementById("admin_password").value;
		if(admin_password==""||admin_name==""){
			alert('<?php echo $l_langpackage->l_null?> ');return false;
		}else{
			document.getElementById("admin_password").value=MD5(admin_password);
		}
	}
	window.onload = function(){document.getElementById('admin_name').focus();}
</script>
</head>
<body>
<div id="login">
	<div class="warp">
    	<div class="content">
            <h1></h1>
            <form action="check_login.php" method="post" name="login_main" id="login_main" onsubmit="return check_form();">
                <div class="item"><div class="input"><div class="icon" title="<?php echo $l_langpackage->l_name?>"></div><input value="" tabindex="1" id="admin_name" type="text" name="admin_name" /></div><label><?php echo $l_langpackage->l_name;?>：</label></div>
                <div class="item"><div class="input"><div class="icon2" title="<?php echo $l_langpackage->l_pw?>"></div><input value="" tabindex="2" id="admin_password" type="password" name="admin_password" /></div><label><?php echo $l_langpackage->l_pw;?>：</label></div>
                <input type="submit" tabindex="3" value="" class="submit" />
            </form>
            <p class="copyright">Powered by iWebSNS V1.0 Copyright © 2009-2010</p>
        </div>   
    </div>
</div>
</body>
</html>