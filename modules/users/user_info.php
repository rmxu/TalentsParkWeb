<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_info.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_info.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
 //引入模块公共方法文件
 require("foundation/fgrade.php");
 require("foundation/module_users.php");
 require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;

	//变量获得
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$show_type=intval(get_argg('single'));
	$is_finish=intval(get_argg('is_finish'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	$user_row = api_proxy("user_self_by_uid","*",$userid);

	//性别预定义
	$woman_c=$user_row['user_sex'] ? "checked=checked":"";
	$man_c=$user_row['user_sex'] ? "":"checked=checked";
	
	//婚姻预定义
	$sec_c="";
	$mer_c="";
	$n_mer_c="";
	if($user_row['user_marry']==0){
		$sec_c="selected=selected";
	}
	if($user_row['user_marry']==1){
		$mer_c="selected=selected";
	}
	if($user_row['user_marry']==2){
		$n_mer_c="selected=selected";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script src="servtools/area.js" type="text/javascript"></script>
</head>
<script type="text/javascript">
	function check_form(){
		document.getElementById('birth_province').value=document.getElementById('s1').value;
		document.getElementById('birth_city').value=document.getElementById('s2').value;
		document.getElementById('reside_province').value=document.getElementById('r1').value;
		document.getElementById('reside_city').value=document.getElementById('r2').value;
	
		var qq=document.form.qq.value;
		if(qq){
			if(!qq.match("^[0-9]*[1-9][0-9]*$") || qq<10001){
			parent.Dialog.alert("<?php echo $u_langpackage->u_qq_err;?>");
			return false;
			}
		}
		var mood_text=trim(document.form.name.value);
		if(mood_text==''){
			parent.Dialog.alert("<?php echo $u_langpackage->u_no_name;?>");
			return false;
		}
	}
</script>
<body id="iframecontent">
<?php if($url_uid == $ses_uid){?>
<div class="create_button">
	<a target="frame_content" href="modules.php?app=user_info&is_finish=1"><?php echo $u_langpackage->u_perfect_info;?></a>
</div>
<?php }?>
<h2 class="app_user"><?php echo $u_langpackage->u_profile;?></h2>
<?php if(!$show_type){?>
<?php if(!$is_finish){?>
<div class="tabs">
	<ul class="menu">
	  <li class="active"><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
	  <li><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
	  <li><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
	  <li><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
	  <li><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
	</ul>
</div>
<div class="rs_head"><?php echo $u_langpackage->u_fill;?></div>
<?php }?>
		<form name="form" method="post" action="do.php?act=user_info&is_finish=<?php echo $is_finish;?>" onsubmit="return check_form();">
			<table class="form_table" border="0">
				<tr>
					<th><?php echo $u_langpackage->u_name;?></th>
					<td><?php echo $user_row['user_name'];?> <span title="<?php echo count_level($user_row['integral']);?>"><?php echo grade($user_row['integral']);?></span>&nbsp;&nbsp;<span class="gray">(<font color='red'><?php echo $user_row['integral'];?></font><?php echo $u_langpackage->u_integral;?>)</span></td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_sex;?></th>
					<td>
							<?php echo ($user_row['user_sex']==0)?$u_langpackage->u_wen:$u_langpackage->u_man;?>
					</td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_marr;?></th>
					<td>
						<select id="marry" name="marry">
							<option value="0" <?php echo $sec_c;?>><?php echo $u_langpackage->u_sec;?></option>
							<option value="1" <?php echo $mer_c;?>><?php echo $u_langpackage->u_marr_n;?></option>
							<option value="2" <?php echo $n_mer_c;?>><?php echo $u_langpackage->u_marr_y;?></option>
						</select>
					</td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_bird;?></th>
					<td>
						<?php echo get_birth_date($user_row['birth_year'],$user_row['birth_month'],$user_row['birth_day']);?>
					</td>
				</tr>
				<tr>
					<th><?php echo $u_langpackage->u_bld;?></th>
					<td>
						<?php echo get_blood($user_row['user_blood']);?>
					</td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_birc;?></th>

					<td>
						<div id="birth"><select name='s1' id="s1" onchange="document.getElementById('birth_province').value=this.value;"><option><?php echo $u_langpackage->u_select;?></option></select>
							<input type='hidden' name='birth_province' id='birth_province' value='<?php echo $user_row["birth_province"];?>' />
							<select name='s2' id="s2" onchange="document.getElementById('birth_city').value=this.value;"><option><?php echo $u_langpackage->u_select;?></option></select>
							<input type='hidden' name='birth_city' id='birth_city' value='<?php echo $user_row["birth_city"];?>' />
						  <script type="text/javascript">
								setup();
								document.getElementById('s1').value='<?php echo $user_row["birth_province"];?>';
								change(1);
								document.getElementById('s2').value='<?php echo $user_row["birth_city"];?>';
							</script>

						</div>
					</td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_res;?></th>
					<td>
						<div id="reside">
							<select name='r1' id="r1" ><option><?php echo $u_langpackage->u_select;?></option></select>
							<input type='hidden' name='reside_province' id='reside_province' value='<?php echo $user_row["reside_province"];?>' />
							<select name='r2' id="r2" ><option><?php echo $u_langpackage->u_select;?></option></select>
							<input type='hidden' name='reside_city' id='reside_city' value='<?php echo $user_row["reside_city"];?>' />
						  <script type="text/javascript">
								setup2();
								document.getElementById('r1').value='<?php echo $user_row['reside_province'];?>';
								change2(1);
								document.getElementById('r2').value='<?php echo $user_row['reside_city'];?>';
							</script>

						</div>
					</td>
				</tr>

				<tr>
					<th>QQ</th>
					<td><input class="small-text" type="text" id="qq" name="qq" value="<?php echo $user_row['user_qq'];?>" maxlength="16" autocomplete='off'/></td>
				</tr>

				<tr>
					<th><?php echo $u_langpackage->u_reg_time;?></th>
					<td><?php echo $user_row['user_add_time'];?></td>
				</tr>

				<tr>
					<td></td>
					<td>
						<input type="submit" name="profilesubmit2" value="<?php echo $u_langpackage->u_b_con;?>" class="regular-btn" />
						<input type="reset" name="Submit" class="regular-btn" value="<?php echo $u_langpackage->u_b_can;?>" />
					</td>
				</tr>
			</table>
</form>
	<?php }?>
	<?php if($show_type){?>
		<table class="form_table">
      <tr><th><?php echo $u_langpackage->u_name;?>：</th><td><?php echo $user_row['user_name'];?></td></tr>
			<tr><th><?php echo $u_langpackage->u_sex;?>：</th><td><?php echo $man_c ? $u_wen : $u_man;?></td></tr>
			<tr><th><?php echo $u_langpackage->u_marr;?>：</th><td><?php echo $mer_c ? $u_marr_n : ($n_mer_c ? $u_marr_y : $u_sec);?></td></tr>
			<tr><th><?php echo $u_langpackage->u_bird;?>：</th><td><?php echo $user_row["birth_year"]&&$user_row["birth_month"]&&$user_row["birth_day"]?$user_row["birth_year"].$u_langpackage->u_year.$user_row["birth_month"].$u_langpackage->u_month.$user_row["birth_day"].$u_langpackage->u_day:$u_langpackage->u_set;?></td></tr>
			<tr><th><?php echo $u_langpackage->u_bld;?>：</th><td><?php echo $user_row['user_blood'] ? $user_row['user_blood'] : $u_langpackage->u_sec;?></td></tr>
			<tr><th><?php echo $u_langpackage->u_birc;?>：</th><td><?php echo $user_row["birth_province"]?($user_row["birth_province"]==$user_row["birth_city"]?$user_row["birth_province"]:$user_row["birth_province"].$user_row["birth_city"]):$u_langpackage->u_set;?></td></tr>
			<tr><th><?php echo $u_langpackage->u_res;?>：</th><td><?php echo $user_row["reside_province"]?($user_row["reside_province"]==$user_row["reside_city"]?$user_row["reside_province"]:$user_row["reside_province"].$user_row["reside_city"]):$u_langpackage->u_set;?></td></tr>
			<tr><th>QQ：</th><td><?php echo $user_row['user_qq']?$user_row['user_qq']:$u_langpackage->u_set;?></td>
		</table>
	<?php }?>
</body>
</html>
