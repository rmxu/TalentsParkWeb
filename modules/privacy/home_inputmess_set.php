<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/privacy/home_inputmess_set.html
 * 如果您的模型要进行修改，请修改 models/modules/privacy/home_inputmess_set.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");
	
	//语言包引入
	$pr_langpackage=new privacylp;//变量获得
	$user_id=get_sess_userid();
	
	//表声明区
	$t_users=$tablePreStr."users";
	
	$dbo=new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);
	
	$select_items=array('inputmess_limit');
	$user_privacy=get_user_info_item($dbo,$select_items,$t_users,$user_id);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<script type="text/javascript">
	function check_form(){
		if(document.form1.input_mess.value==''){
			parent.Dialog.alert("<?php echo $pr_langpackage->pr_no_data;?>");
			return false;
		}
  }
</script>

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <h2 class="app_blog"><?php echo $pr_langpackage->pr_conf;?></h2>
    <div class="tabs">
        <ul class="menu">
		    <li><a href="modules.php?app=privacy" title="<?php echo $pr_langpackage->pr_access;?>" ><?php echo $pr_langpackage->pr_access;?></a></li>
		    <li class="active"><a href="modules.php?app=pr_inputmess" title="<?php echo $pr_langpackage->pr_inputmess;?>"><?php echo $pr_langpackage->pr_inputmess;?></a></li>
		    <li><a href="modules.php?app=pr_reqcheck" title="<?php echo $pr_langpackage->pr_reqcheck;?>"><?php echo $pr_langpackage->pr_reqcheck;?></a></li>
        </ul>
    </div>
	<div class="rs_head"><?php echo $pr_langpackage->pr_send_set;?></div>
<form id="form1" name="form1" method="post" action="do.php?act=pr_inputmess" onsubmit="return check_form();">

<table cellpadding="0" cellspacing="0" border="0" class='form_table'>
  <tr>
    <th width="20%"><input type="radio" value="0" <?php echo radio_checked(0,$user_privacy[0]);?> name="input_mess" id="input_mess" /></th>
    <td width="80%"><label><?php echo $pr_langpackage->pr_send_public;?></label></td>
  </tr>
	<tr>
    <th><input type="radio" value="1" <?php echo radio_checked(1,$user_privacy[0]);?>  name="input_mess" id="input_mess" /></th><td><label><?php echo $pr_langpackage->pr_send_pals;?></label></td>
	</tr>
	<tr><th><input type="radio" value="2" <?php echo radio_checked(2,$user_privacy[0]);?>  name="input_mess" id="input_mess"></th>
	<td><label><?php echo $pr_langpackage->pr_send_pri;?></label></td>
  </tr>
  <tr><td></td>
	<td><input type="submit" name="Submit" class="regular-btn" value="<?php echo $pr_langpackage->pr_button_action;?>" /><input type="button" name="Submit2" class="regular-btn" onclick="location.href='modules.php?app=pr_inputmess'" value="<?php echo $pr_langpackage->pr_button_cancel;?>" /></td>
  </tr>  <tr><td colspan="2" height="20"></td></tr>

</table>
</form>
</body>
</html>
