<?php
require("session_check.php");
//语言包引入
$l_langpackage=new loginlp;
$dbo = new dbex;
dbtarget('w',$dbServs);
$t_backgroup=$tablePreStr."backgroup";
$group_id=get_session('admin_group');
$sql="select name from $t_backgroup where gid='$group_id'";
$group_name=$dbo->getRow($sql);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="zh-cn">
<title><?php echo $l_langpackage->l_manage;?></title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css" />
<script type='text/javascript' src="js/jy.js"></script>
<style type="text/css">html body{overflow:hidden;}</style>
<script type="text/javascript" language="javascript" src="../servtools/dialog/zDrag.js"></script>
<script type="text/javascript" language="javascript" src="../servtools/dialog/zDialog.js"></script>
</head>
<body name="main">
<table height="100%" width="100%" cellspacing="0" cellpadding="0" border="0">
	<TR><TD height="80" colspan="3">      
        <!-- top开始 -->
        <div id="jyhead">
            <div class="logo">iwebsns<?php echo $l_langpackage->l_back_management_sys;?></div>
            <div class="nav">
                <ul class="menu">
                    <li class="active"><a href="left.php?part_id=default" target="BoardTitle" hidefocus="true"><?php echo $l_langpackage->l_Management_home;?></a></li>
                    <li><a href="left.php?part_id=base" target="BoardTitle" hidefocus="true"><?php echo $l_langpackage->l_global_set;?></a></li>
                    <li><a href="left.php?part_id=user" target="BoardTitle" hidefocus="true"><?php echo $l_langpackage->l_user_management;?></a></li>
                    <li><a href="left.php?part_id=ui" target="BoardTitle" hidefocus="true">UI<?php echo $l_langpackage->l_administration;?></a></li>
                    <li><a href="left.php?part_id=application" target="BoardTitle" hidefocus="true"><?php echo $l_langpackage->l_app_management;?></a></li>
                    <li><a href="left.php?part_id=tool" target="BoardTitle" hidefocus="true"><?php echo $l_langpackage->l_tool_box;?></a></li>
                </ul>
            </div>
            <div class="uinfo">
                <?php echo $l_langpackage->l_login_hello;?>，<em><?php echo get_sess_admin();?></em> 
                <a target="_top" hidefocus="true" href="login_out.php"><?php echo $l_langpackage->l_out;?></a>
            </div>
        </div>
        <!-- top结束 -->
	</TD></TR>
	<TR><TD height="20" colspan="3" style="background:#E7E7E7;"></TD></TR>
  <TR>
    <TD width="200" id="frmTitle" vAlign=center noWrap align=middle name="frmTitle">
      <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
      	<tr>
        	<td height="30" onClick="showMenu();" id="separator" class="menu_fold"></td>
        </tr>
      	<tr>
        	<td valign="top" id="menuBox">
            	<iframe id="BoardTitle" style="width:200px;height:100%;" name="BoardTitle" src="left.php?part_id=default" frameBorder="0"></iframe>
            </td>
        </tr>
      	<tr>
        	<td id="menuScrollBar" class="menu_scroll_bar">
				<div class="scrolldown" onClick="menuScroll('down');"></div>
				<div class="scrollup" onClick="menuScroll('up');"></div>
            </td>
        </tr>
      </table>
     </TD>
     <TD id="right_list" style="overflow:hidden;" width="95%" align="right">
		  <table width="100%" height="100%" style="margin:0px;" border="0" cellpadding="0" cellspacing="0">
	         <TR><td height="*" valign="top"  align="right">
	            <IFRAME id="right" scrolling=auto style=" WIDTH:100%;HEIGHT:100%;margin:0;" name="right" src="right.php" frameBorder=0></IFRAME>
	          </TD>
		      	</TR>
	         </TABLE>
    	</TD>
	</TR>
</TABLE>
<script type="text/javascript" language="javascript">
function menuScroll(direction)
{
	var menuBox = document.getElementById('menuBox');
	var menuBoxHeight = menuBox.offsetHeight;
	var subMenu = window.frames['BoardTitle'].document.getElementById('submenu');
	var subMenuHeight = subMenu.offsetHeight;
	var subMenuTop = subMenu.offsetTop;
	var stepLen = 30;
	
	if(direction == 'up')
	{
		if(subMenuTop < 0)
		{
			subMenu.style.top = (Math.abs(subMenuTop) < stepLen ? 0 : subMenuTop + stepLen) + 'px';
		}
	}
	else if(direction = 'down')
	{
		var subtraction = subMenuHeight - menuBoxHeight;
		if(subtraction > 0)
		{
			subMenu.style.top = subMenuTop - stepLen + 'px';
		}
		if(subtraction - Math.abs(subMenuTop) < stepLen)
		{
			subMenu.style.top = '-' + subtraction + 'px';
		}
	}
	else
	{
		return;
	}
}
function showMenuScrollBar()
{
	var menuBoxHeight = document.getElementById('menuBox').offsetHeight;
	var subMenuHeight = window.frames['BoardTitle'].document.getElementById('submenu').offsetHeight;
	document.getElementById('menuScrollBar').style.visibility = subMenuHeight > menuBoxHeight ? 'visible' : 'hidden';
}
window.onresize = function()
{
	showMenuScrollBar();
}
window.onload = function()
{
	showMenuScrollBar();
}
function showMenu()
{
	var separator = document.getElementById('separator');
	var menuBox = document.getElementById('menuBox');
	var menuScrollBar = document.getElementById('menuScrollBar');
	if(separator.className == 'menu_fold')
	{
		separator.className = 'menu_unfold';
		document.getElementById('BoardTitle').style.width = '0px';
		document.getElementById('frmTitle').style.width = '60px';
		menuBox.className = 'menu_box';
		menuScrollBar.style.visibility = 'hidden';
	}
	else if(separator.className == 'menu_unfold')
	{
		separator.className = 'menu_fold';
		document.getElementById('BoardTitle').style.width = '200px';
		document.getElementById('frmTitle').style.width = '200px';
		menuBox.className = '';
		showMenuScrollBar();
	}
	else
	{
		return;
	}
}
function pic_error(pic)
{
	pic.src='images/pic_none.gif';
}
</script>
</body>
</html>