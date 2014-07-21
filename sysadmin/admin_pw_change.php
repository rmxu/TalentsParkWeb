<?php
	require("session_check.php");

	//语言包引入
	$p_langpackage=new pwlp;
	$u_langpackage=new userslp;
	$ad_langpackage=new adminmenulp;
	
	//变量获得
	$admin_id = get_session('admin_id');
	$name = short_check(get_argp('name'));
	$formerly_pw = short_check(get_argp('formerly_pw'));
	$new_pw = short_check(get_argp('new_pw'));
	$new_pw_repeat = short_check(get_argp('new_pw_repeat'));

	//数据表定义区
	$t_admin = $tablePreStr."admin";
	if(get_argp('action')){
		if($new_pw == $new_pw_repeat){
			$dbo = new dbex;
			//读写分离定义函数
			dbtarget('r',$dbServs);
	
			$sql = "select admin_password from $t_admin where admin_id = $admin_id";
			$user_row = $dbo->getRow($sql);
			$formerly_pw=md5($formerly_pw);
			if($user_row['admin_password']==$formerly_pw){
				$new_pw = md5($new_pw);
				//读写分离定义函数
				dbtarget('w',$dbServs);
	
				$sql = "update $t_admin set admin_password ='$new_pw',admin_name='$name' where admin_id = $admin_id;";
				if($dbo ->exeUpdate($sql)){
					set_sess_admin($name);
					echo "<script type='text/javascript'>alert('$u_langpackage->u_pw_chg_suc');</script>";
				}
			}else{
				echo "<script type='text/javascript'>alert('$u_langpackage->u_pw_err');</script>";
			}
		}else{
			echo "<script type='text/javascript'>alert('$u_langpackage->pw2_err');</script>";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script type="text/javascript">
function check_form()
{
	if(document.getElementById('new_pw').value=="")
	{
		alert('<?php echo $p_langpackage->p_null?>');
		return false;
	}
	if(document.getElementById('name').value=="")
	{
		alert('<?php echo $p_langpackage->p_null?>');
		return false;
	}
	if(document.getElementById('new_pw').value!=document.getElementById('new_pw_repeat').value)
	{
		alert('<?php echo $p_langpackage->p_differ?>');
		return false;
	}
}
</script>
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="admin_pw_change.php"><?php echo $ad_langpackage->ad_modify_admin_password;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $p_langpackage->p_amend_pw;?></h3>
            <div class="content">
<form id="form1" name="form1" method="post" action="" onsubmit="return check_form();">
<table class='form-table'>
  <tr>
    <td><div align="right"><?php echo $p_langpackage->p_name?>：</div></td>
    <td>
      <label>
        <input type="text" name="name" id="name" class="regular-text" value="<?php echo get_sess_admin();?>" />
      </label></td>
  </tr>
  <tr>
    <td><div align="right"><?php echo $p_langpackage->p_formerly_pw?>：</div></td>
    <td>
      <label>
        <input type="password" name="formerly_pw" value="" class="regular-text" />
      </label></td>
  </tr>
  <tr>
    <td><div align="right"><?php echo $p_langpackage->p_new_pw?>：</div></td>
    <td><label>
      <input type="password" name="new_pw" id="new_pw" value="" class="regular-text" />
    </label></td>
  </tr>
  <tr>
    <td><div align="right"><?php echo $p_langpackage->p_new_pw_repeat?>：</div></td>
    <td><label>
      <input type="password" name="new_pw_repeat" id="new_pw_repeat" value="" class="regular-text" />
    </label></td>
  </tr>
  <tr>
    <td>
			<div align="right">
				<input type="submit" class="regular-button" name="action" value="<?php echo $p_langpackage->p_refer?>" />
      </div></td>
       <td></td>
  </tr>
</table>
</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
