<?php
require("session_check.php");
	$is_check=check_rights("b06");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$u_langpackage=new uilp;
	$ad_langpackage=new adminmenulp;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $u_langpackage->u_UI_cback?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript'>
function worning_tip(UI_type){
	if(confirm("<?php echo $u_langpackage->u_worning?>")){
		if(confirm("<?php echo $u_langpackage->u_re_worning?>")){
			var diag = new parent.Dialog();
			diag.Width = 300;
			diag.Height = 150;
			diag.Modal = false;
			diag.Title = "UI恢复";
			diag.InnerHtml="<img src='images/loading.gif' style='vertical-align:middle;margin:5px;' />正在恢复...";
			diag.show();
			window.location.href="ui_restore.action.php?r_type="+UI_type;
		}
	}
}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="ui_restore.php"><?php echo $ad_langpackage->ad_manage_skin;?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $u_langpackage->u_cback_temp;?></h3>
<div class="content">
<table class="form-table">
	<tr>
		<td><?php echo $u_langpackage->u_cback_temp_say?></td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_cback_temp?>' onclick='javascript:worning_tip("tmp");' /></td>
	</tr>
</table>
</div>
</div>

<div class="infobox">
<h3><?php echo $u_langpackage->u_cback_model;?></h3>
<div class="content">
<table>
	<tr>
		<td><?php echo $u_langpackage->u_cback_model_say?>
		</td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_cback_model?>' onclick='javascript:worning_tip("mod");' /></td>
	</tr>
</table>
</div>
</div>

<div class="infobox">
<h3><?php echo $u_langpackage->u_cback_skin;?></h3>
<div class="content">
<table>
	<tr>
		<td><?php echo $u_langpackage->u_cback_skin_say?>
		</td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_cback_skin?>' onclick='javascript:worning_tip("skin");' /></td>	
	</tr>			
</table>
</div>
</div>

<div class="infobox">
<h3><?php echo $u_langpackage->u_cback_all;?></h3>
<div class="content">
<table>
	<tr>
		<td><?php echo $u_langpackage->u_cback_all_say?>
		</td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_cback_all?>' onclick='javascript:worning_tip("c_com");' /></td>
	</tr>	
</table>
</div>
</div>

</div>
</div>
</body>
</html>		