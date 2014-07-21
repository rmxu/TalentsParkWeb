<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_pw_change.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_pw_change.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//语言包引入
	$u_langpackage=new userslp;
	//变量获得
	$user_id = get_sess_userid();
	$formerly_pw = short_check(get_argp('formerly_pw'));
	$new_pw = short_check(get_argp('new_pw'));
	$new_pw_repeat = short_check(get_argp('new_pw_repeat'));
	$model = short_check(get_argg('model'));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<script type="text/javascript">
// 检测原密码
old_password = function(){
	var old_password = document.getElementById('formerly_pw');
	if(old_password.value=='') {
		parent.Dialog.alert('<?php echo $u_langpackage->u_fill_old_password;?>！');
		old_password.focus();
		return false;
	}
	return true;
};
// 检测密码
user_password = function(){
	var user_password = document.getElementById('new_pw');
	if(user_password.value=='') {
		parent.Dialog.alert('<?php echo $u_langpackage->u_fill_new_password;?>！');
		user_password.focus();
		return false;
	} else if(user_password.value.length<6 || user_password.value.length>16) {
		parent.Dialog.alert('<?php echo $u_langpackage->u_new_password_format_error;?>！');
		user_password.focus();
		return false;
	}
	return true;
};

// 检测确认密码
user_repassword = function(){
	var user_password = document.getElementById('new_pw');
	var user_repassword = document.getElementById('new_pw_repeat');
	if(user_repassword.value=='') {
		parent.Dialog.alert('<?php echo $u_langpackage->u_repeat_fill_new_password;?>！');
		user_repassword.focus();
		return false;
	} else if(user_repassword.value!=user_password.value) {
		parent.Dialog.alert('<?php echo $u_langpackage->u_pw2_err;?>');
		return false;
	}
	return true;
};

function check_form(){
	if(old_password() && user_password() && user_repassword()){
		return true;
	}else{
		return false;
	}
}
</script>
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
<h2 class="app_user"><?php echo $u_langpackage->u_conf;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
        <li><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
        <li class="active"><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
        <li><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
        <li><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
    </ul>
</div>

<form id="form1" name="form1" method="post" class="form_table" action="do.php?act=user_pw_change" onsubmit="return check_form();">
<table border="0" class='form_table'>
  <tr>
    <th width="18%"><?php echo $u_langpackage->u_fml_pw;?></th>
    <td width="82%"><input class="small-text2" type="password" name="formerly_pw" id="formerly_pw" value="" autocomplete="off" /></td>
  </tr>
  <tr>
    <th><?php echo $u_langpackage->u_new_pw;?></th>
    <td><input class="small-text2" type="password" name="new_pw" id="new_pw" value="" /></td>
  </tr>
  <tr>
    <th><?php echo $u_langpackage->u_repeat_pw;?></th>
    <td><input class="small-text2" type="password" name="new_pw_repeat" id="new_pw_repeat" value="" /></td>
  </tr>
  <tr>
    <td></td>
    <td class="gray"><?php echo $u_langpackage->u_character_length;?> </td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="Submit" class="regular-btn" value="<?php echo $u_langpackage->u_b_con;?>" /><input type="button" name="Submit2" class="regular-btn" onclick="location.href='modules.php?app=user_pw_change'" value="<?php echo $u_langpackage->u_b_can;?>" /></td>
  </tr>
</table>
</form>
</body>
</html>
