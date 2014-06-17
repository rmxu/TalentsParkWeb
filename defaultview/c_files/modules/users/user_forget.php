<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_forget.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_forget.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//�������԰�
$u_langpackage=new userslp;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" href="skin/<?php echo $skinUrl;?>/css/layout.css" />
<script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script>
<script type='text/javascript'>
function getVerCode(){
  document.getElementById("verCodePic").src="servtools/veriCodes.php?vc="+Math.random();
}
function check_form(){
	var vericode=document.getElementById('veriCode').value;
	var email=document.getElementById('email').value;
	if(email==''){
		alert('<?php echo $u_langpackage->u_email_not_empty;?>');
		return false;
	}
	if(vericode==''){
		alert('<?php echo $u_langpackage->u_code_not_empty;?>');
		return false;
	}
	return true;
}
</script>
</head>
<body>
<?php require('uiparts/guestheader.php');?>
<div class="forget_box">
	<form action='do.php?act=user_forget' onsubmit='return check_form()' method='post'>
	  <h2><?php echo $u_langpackage->u_forget_password;?>？</h2>
<table>
          <tr>
            <td width="224" height="40" align="right"><?php echo $u_langpackage->u_enter_registration_use;?>eMail： </td>
            <td height="40" colspan="2" align="left"><input type="text" name="email" id="email" /></td>
          </tr>
          <tr>
            <td height="40" align="right"><?php echo $u_langpackage->u_enter_code;?>：</td>
            <td width="116" height="40" align="left"><input type="text" style="width:110px;" name="veriCode" id="veriCode" maxlength="5" /></td>
            <td width="332" height="40"> <img border="0" src="servtools/veriCodes.php" height=20 style="margin:0 0 -3px 5px;" id="verCodePic" /> <a href="javascript:;" onclick='getVerCode();return false;'><?php echo $u_langpackage->u_not_see;?>？</a></td>
          </tr>
          <tr>
            <td></td>
            <td height="50" colspan="2"><input class="button" type="submit" value="<?php echo $u_langpackage->u_retrieve_password;?>" /></td>
          </tr>
</table>
	</form>
</div>
<?php require('uiparts/footor.php');?>
</body>
</html>