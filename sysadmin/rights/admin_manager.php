<?php
require(dirname(__file__)."/../session_check.php");
$ri_langpackage=new rightlp;
$l_langpackage=new loginlp;
$p_langpackage=new pwlp;
$m_langpackage=new modulelp;
$u_langpackage=new uilp;
$ad_langpackage=new adminmenulp;
if(get_session('admin_group')!='superadmin'){
	echo $ri_langpackage->ri_refuse;exit;
}
$dbo = new dbex;
dbtarget('w',$dbServs);
$t_backgroup=$tablePreStr."backgroup";
$sql="select gid,name from $t_backgroup";
$groups=$dbo->getRs($sql);
$options="<option>".$ri_langpackage->ri_choose."</option>";
$group_array=array();
$t_admin=$tablePreStr."admin";
$dbo = new dbex;
dbtarget('r',$dbServs);
if($groups)
{
	foreach($groups as $group)
	{
		$options.='<option value="'.$group['gid'].'">'.$group['name'].'</option>';
		$group_array[$group['gid']]=$group['name'];
	}
}

if(get_args("op")=='del')
{
	$id=get_args('id');
	dbtarget('r',$dbServs);	
	$sql="delete from $t_admin where admin_id='$id' and admin_group!='superadmin'";
	$dbo->exeUpdate($sql);
}
else if(get_args("op")=='upd')
{

	$password=get_args('password');
	$repassword=get_args('repassword');
	$group=get_args('group');
	$admin_id=get_args('admin_id');

	dbtarget('w',$dbServs);	
	if($password!='' && $password==$repassword)
	{
		$password=md5($password);
		$sql="update $t_admin set admin_password='$password',admin_group='$group' where admin_id='$admin_id'";
	}else
	{
		$sql="update $t_admin set admin_group='$group' where admin_id='$admin_id'";
	}
	$dbo->exeUpdate($sql);
}
else if(get_args('op')=='lock')
{
	$id=get_args('id');
	dbtarget('w',$dbServs);
	$sql="update  $t_admin set is_pass=if(is_pass=1,0,1) where admin_id=$id";
	$dbo->exeUpdate($sql);
}
else if(get_args('op')=='add')
{
	$name=get_args('name');
	$password=md5(get_args('password'));
	$repassword=md5(get_args('repassword'));
	$group=get_args('group');
	if($name && $password && $group)
	{
		if($password==$repassword)
		{
			dbtarget('r',$dbServs);	
			$sql="select * from $t_admin where admin_name='$name'";	
			$admin=$dbo->getRow($sql);
			if(!$admin)
			{
				dbtarget('w',$dbServs);	
				$sql="insert into $t_admin (admin_name,admin_password,active_time,admin_group) values('$name','$password',now(),'$group')";
				$dbo->exeUpdate($sql);
			}
			else{
				echo $ri_langpackage->ri_isset_user;
			}
		}
		else{
			  echo "<script>alert('".$p_langpackage->p_differ."')</script>";
		}
	}
}
	$sql="select * from $t_admin";
	$admins=$dbo->getRs($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="../css/admin.css">
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="admin_manager.php"><?php echo $ad_langpackage->ad_manage_users;?></a></div>
        <hr />
<div class="infobox">
    <h3><?php echo $ri_langpackage->ri_add_manager;?></h3>
    <div class="content">
<table class='list_table'>
  <tr>
  	<thead>
    <th width="150"><?php echo $p_langpackage->p_name;?></th>
    <th><?php echo $ri_langpackage->ri_users;?></th>
    <th width="50"><?php echo $m_langpackage->m_state;?></th>
    <th width="110" style=" text-align:center"><?php echo $m_langpackage->m_ctrl;?></th>
    </thead>
  </tr>
	<?php
	foreach($admins as $rs)
	{
		if($rs['admin_group']!='superadmin'){
	?>		
			<tr>
				<td><span id='admin_<?php echo $rs['admin_id'];?>'><?php echo $rs['admin_name'];?></span></td>
				<td><span id='show_1_<?php echo $rs['admin_id'];?>'><?php echo isset($group_array[$rs['admin_group']]) ? $group_array[$rs['admin_group']]:$ri_langpackage->ri_choose;?></span>
						<span style='display:none;' id='show_2_<?php echo $rs['admin_id'];?>'>
						<?php echo $l_langpackage->l_pw;?>：<input type="password" class="small-text" name="password" id="password_<?php echo $rs['admin_id'];?>" />
						<?php echo $p_langpackage->p_new_pw_repeat;?>：<input type="password" id="repassword_<?php echo $rs['admin_id'];?>" class="small-text" name="repassword" />
						<?php echo $ri_langpackage->ri_users?>：<select name="group" id="group_<?php echo $rs['admin_id'];?>"><?php echo $options;?></select>
						</span>
				</td>
				<td>
						<?php if($rs['is_pass']==1)echo $m_langpackage->m_normal;
									else echo '<font color=red>'.$m_langpackage->m_lock.'</font>';
						?>
				</td>
				<td>
					<span id='show_3_<?php echo $rs['admin_id'];?>'>
						<a href='?op=del&id=<?php echo $rs['admin_id'];?>'><?php echo $m_langpackage->m_del;?></a>&nbsp;&nbsp;
						<a href='javascript:void(0)' onclick='change("<?php echo $rs['admin_id'];?>",1);SelectItem(document.getElementById("group_<?php echo $rs['admin_id'];?>"),"<?php echo isset($group_array[$rs['admin_group']])?$group_array[$rs['admin_group']]:'';?>");'><?php echo $u_langpackage->u_amend;?></a>&nbsp;&nbsp;
						<?php if($rs['is_pass']==1)	echo "<a href='?op=lock&id=".$rs['admin_id']."'>".$m_langpackage->m_lock."</a>";
									else echo "<a href='?op=lock&id=".$rs['admin_id']."'>".$m_langpackage->m_unlock."</a>";
						?>
					</span>
					<span style='display:none;' id='show_4_<?php echo $rs['admin_id'];?>'>
						<input type='button' name='add' onclick='update_admin("<?php echo $rs['admin_id'];?>");' class='regular-button' value='<?php echo $u_langpackage->u_amend;?>'>&nbsp;
						<input onclick='change("<?php echo $rs['admin_id'];?>",0);' type='button' class='regular-button' value='<?php echo $u_langpackage->u_cancel;?>'>
					</span>
				</td>
			</tr>
	<?php	
		}
	}
	?>
</table>
		<form id="form1" name="form1" method="post" action="">
		<div id="form" class='form-table' style='text-align:left'>
				<span id='add1_id'><input type='button' onclick='change_add("add","id")' class='regular-button' value='<?php echo $ri_langpackage->ri_add;?>' /></span>
				<span id="add2_id" style='display:none'>
					<?php echo $p_langpackage->p_name;?>：<input type="text" class="small-text" name="name" id="name" autocomplete='off' />
					<?php echo $l_langpackage->l_pw;?>：<input type="password" class="small-text" name="password" id="add_password" />
					<?php echo $p_langpackage->p_new_pw_repeat;?>：<input type="password" class="small-text" name="repassword" id="add_repassword" />
					<?php echo $ri_langpackage->ri_users;?>：<select name="group" id="add_group"><?php echo $options;?></select>
					<br />
					<input type="hidden" value="add" name="op"/>
					<input type="submit" onclick='return check_form()' class='regular-button' name="submit" id="button" value="<?php echo $ri_langpackage->ri_add;?>" />&nbsp;
					<input onclick='change_add("add","id");' type="button" class='regular-button' value="<?php echo $u_langpackage->u_cancel;?>">
				</span>
				</div>
	</form>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript' src='../../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function update(name,group)
{
	document.getElementById('form').innerHTML='<label><?php echo $p_langpackage->p_name;?>：<input type="text" name="name"  readonly="readonly"  id="name" value="'+name+'" /></label><label><?php echo $l_langpackage->l_pw;?>：<input type="password" name="password" id="password" /></label><label><?php echo $p_langpackage->p_new_pw_repeat;?>：<input type="password" name="repassword" id="repassword" /></label><label><?php echo $ri_langpackage->ri_users;?>：<select name="group" id="group"><?php echo $options ?></select></label><input type="hidden" value="upd" name="op"/><input type="submit" name="submit" class="regular-button" id="button" value="<?php echo $u_langpackage->u_amend;?>" />';
	SelectItem(document.getElementById('group'), group);
}
function SelectItem(objSelect, objItemText)
{
	var isExit = false;
	for (var i = 0; i < objSelect.options.length; i++)
	{
		if (objSelect.options[i].value == objItemText || objSelect.options[i].text == objItemText)
		{
			objSelect.options[i].selected = true;
			break;
		}
	}
}
function trim(str){
	return str.replace(/(^\s*)|(\s*$)|(　*)/g , "");
}
function check_form(){
	var p_value=document.getElementById('add_password').value;
	var rep_value=document.getElementById('add_repassword').value;
	var group_value=document.getElementById('add_group').value;
	if(trim(p_value).length<6||trim(rep_value).length<6){
		alert('<?php echo $ri_langpackage->ri_pass_wrong;?>');return false;
	}
	if(group_value==''){
		alert('<?php echo $ri_langpackage->ri_empty_wrong;?>');return false;
	}
}

function change_add(name_value,id_value){
	if(document.getElementById(name_value+"1_"+id_value).style.display=='none'){
		document.getElementById(name_value+"1_"+id_value).style.display='';
		document.getElementById(name_value+"2_"+id_value).style.display='none';
	}else{
		document.getElementById(name_value+"1_"+id_value).style.display='none';
		document.getElementById(name_value+"2_"+id_value).style.display='';
	}
}
function change(admin_id,chanage)
{
	if(chanage)
	{
		document.getElementById('show_1_'+admin_id).style.display='none';
		document.getElementById('show_2_'+admin_id).style.display='';
		document.getElementById('show_3_'+admin_id).style.display='none';
		document.getElementById('show_4_'+admin_id).style.display='';
	}
	else
	{
		document.getElementById('show_1_'+admin_id).style.display='';
		document.getElementById('show_2_'+admin_id).style.display='none';
		document.getElementById('show_3_'+admin_id).style.display='';
		document.getElementById('show_4_'+admin_id).style.display='none';
	}
}
function update_admin(admin_id)
{
	var password=document.getElementById("password_"+admin_id).value;
	var repassword=document.getElementById("repassword_"+admin_id).value;
	var group=document.getElementById("group_"+admin_id).value;
	if(password != '' && repassword != '')
	{
		if(password.length < 6 || repassword.length < 6)
		{
			alert('<?php echo $ri_langpackage->ri_pass_wrong;?>');return;
		}
	}
	var admin_manager=new Ajax();
	admin_manager.getInfo("admin_manager.php?op=upd&admin_id="+admin_id+"&password="+password+"&repassword="+repassword+"&group="+group,"post","app","",function(c){window.location.reload();});
}
</script>
</body>
</html>
