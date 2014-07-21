<?php
require(dirname(__file__)."/../session_check.php");
require("../../foundation/fpages_bar.php");
require("../../foundation/fsqlseletiem_set.php");
require("../../foundation/fback_search.php");

$ri_langpackage=new rightlp;
$l_langpackage=new loginlp;
$p_langpackage=new pwlp;
$m_langpackage=new modulelp;
$u_langpackage=new uilp;
$ad_langpackage=new adminmenulp;

if(get_session('admin_group')!='superadmin'){
	echo $ri_langpackage->ri_refuse;exit;
}

//数据库读写
$dbo = new dbex;
dbtarget('w',$dbServs);

$user_group=get_argg('user_group');

//数据表定义
$t_frontgroup=$tablePreStr."frontgroup";
$t_users=$tablePreStr."users";

$sql="select gid,name from $t_frontgroup";
$groups=$dbo->getRs($sql);

$options="<option value=\"\" selected>".$ri_langpackage->ri_choose."</option>";
$group_array=array();

if($groups){
	foreach($groups as $group){
		$selected='';
		if($user_group==$group['gid']){
			$selected='selected';
		}
		$options.='<option value="'.$group['gid'].'" '.$selected.'>'.$group['name'].'</option>';
		$group_array[$group['gid']] = $group['name'];
	}
}

if(get_args("op")=='upd'){
	$group=get_args('group');
	$user_id=intval(get_args('user_id'));
	$sql="update $t_users set user_group='$group' where user_id='$user_id'";
	$dbo->exeUpdate($sql);
}

	//当前页面参数
	$page_num=trim(get_argg('page'));
	$sql='';
	$eq_array=array('user_id','user_group');
	$like_array=array("user_name");
	$date_array=array();
	$num_array=array();
	$sql=spell_sql($t_users,$eq_array,$like_array,$date_array,$num_array,'','','');	

	//设置分页
	$dbo->setPages(20,$page_num);

	//取得数据
	$member_rs=$dbo->getRs($sql);

	//分页总数
	$page_total=$dbo->totalPage;
	
	//显示控制
	$isNull=0;
	$isset_data="";
	$none_data="content_none";
	if(empty($member_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="../css/admin.css">
<script type='text/javascript' src='../../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function user_manager(user_id)
{
	var group=document.getElementById("group_"+user_id).value;
	var user_manager=new Ajax();
	user_manager.getInfo("user_manager.php?op=upd&user_id="+user_id+"&group="+group,"post","app","",function(c){window.location.reload();});
}

function cancel_sort(show_1,show_2,hidden_1,hidden_2){
	document.getElementById("add_value").value="";
	document.getElementById(show_1).style.display="";
	document.getElementById(show_2).style.display="none";
	document.getElementById(hidden_1).style.display="none";
	document.getElementById(hidden_2).style.display="none";
	}

function change_sort(show_1,show_2,show_3,hidden_1,hidden_2,hidden_3){
	document.getElementById(show_1).style.display="none";
	document.getElementById(show_2).style.display="none";
	document.getElementById(show_3).style.display="none";
	document.getElementById(hidden_1).style.display="";
	document.getElementById(hidden_2).style.display="";
	document.getElementById(hidden_3).style.display="";
	}

function cancel_change(show_1,show_2,show_3,hidden_1,hidden_2,hidden_3){
	document.getElementById(show_1).style.display="";
	document.getElementById(show_2).style.display="";
	document.getElementById(show_3).style.display="";
	document.getElementById(hidden_1).style.display="none";
	document.getElementById(hidden_2).style.display="none";
	document.getElementById(hidden_3).style.display="none";
	}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="user_manager.php"><?php echo $ad_langpackage->ad_member_role_management;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
							<form action="" method="GET" name='form'>
								<table class="form-table">
								  <tr>
								    <th width="90"><?php echo $m_langpackage->m_userid;?></th>
								    <td><input type="text" class="small-text" name='user_id' value="<?php echo get_argg('user_id');?>"></td>
								    <th><?php echo $m_langpackage->m_uname;?></th>
								    <td><INPUT type='text' class="small-text" name='user_name' value='<?php echo get_argg('user_name');?>'>&nbsp;<font color=red>*</font></td>
								  </tr>
								  <tr>
								    <th><?php echo $ri_langpackage->ri_users;?></th>
								    <td>
								    	<select name='user_group'>
								    		<?php echo $options;?>
								    	</select>
								    </td>
								  </tr>
								  <tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
								  <tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
								</table>
							</form>
            </div>
         </div>
        
<div class="infobox">
    <h3><?php echo $ri_langpackage->ri_users_list;?></h3>
    <div class="content">

<table class='list_table <?php echo $isset_data;?>'>
  <tr>
  	<thead>
    <th width="33%"><?php echo $p_langpackage->p_name;?></th>
    <th><?php echo $ri_langpackage->ri_users;?></th>
    <th width="130" style="text-align:center"><?php echo $m_langpackage->m_ctrl;?></th>
    </thead>
  </tr>
<?php
foreach($member_rs as $rs){
?>
	<tr>
		<td>
			<div id="show_num_<?php echo $rs['user_id'];?>"><?php echo $rs['user_name'];?></div>
			<div id="order_by_<?php echo $rs['user_id'];?>" style="display:none">
			<input type="text" class="small-text" id="input_num_<?php echo $rs['user_id'];?>" maxlength="15" value="<?php echo $rs['user_name'];?>" />
			</div>
		</td>
		<td>
		<div id="show_title_<?php echo $rs['user_id'];?>"><?php echo isset($group_array[$rs['user_group']])?$group_array[$rs['user_group']]:$ri_langpackage->ri_choose;?></div>
		<div id="title_<?php echo $rs['user_id'];?>" style="display:none">
			<select name="group" id="group_<?php echo $rs['user_id'];?>"><?php echo $options;?></select>
		</div>
		</td>
		<td style="text-align:center">
		<div id="button_<?php echo $rs['user_id'];?>">
			<a href="javascript:change_sort('show_num_<?php echo $rs['user_id'];?>','show_title_<?php echo $rs['user_id'];?>','button_<?php echo $rs['user_id'];?>','order_by_<?php echo $rs['user_id'];?>','title_<?php echo $rs['user_id'];?>','action_<?php echo $rs['user_id'];?>');SelectItem(document.getElementById('group_<?php echo $rs['user_id'];?>'), '<?php echo isset($group_array[$rs['user_group']])?$group_array[$rs['user_group']]:'';?>');"><?php echo $u_langpackage->u_amend?></a>
		</div>
		<div id="action_<?php echo $rs['user_id'];?>" style="display:none">
			<input type='button' onclick='user_manager("<?php echo $rs['user_id'];?>")' class='regular-button' value='<?php echo $u_langpackage->u_amend?>' />
			<input type='button' onclick=cancel_change('show_num_<?php echo $rs['user_id'];?>','show_title_<?php echo $rs['user_id'];?>','button_<?php echo $rs['user_id'];?>','order_by_<?php echo $rs['user_id'];?>','title_<?php echo $rs['user_id'];?>','action_<?php echo $rs['user_id'];?>') class='regular-button' value='<?php echo $u_langpackage->u_cancel?>' />
		</div>
		</td>
	</tr>
<?php 
}
?>
</table>
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</div>
<script type='text/javascript'>
function update(name,group)
{
	document.getElementById('form').innerHTML='<label><?php echo $p_langpackage->p_name;?>：<input type="text" name="name"  readonly="readonly"  id="name" value="'+name+'" /></label><label><?php echo $ri_langpackage->ri_users;?>：<select name="group" id="group"><?php echo $options;?></select></label><input type="submit" name="submit" class="regular-button" id="button" value="<?php echo $u_langpackage->u_amend;?>" />';
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
	var p_value=document.getElementById('password').value;
	var rep_value=document.getElementById('repassword').value;
	var group_value=document.getElementById('group').value;
	if(trim(p_value).length<6||trim(rep_value).length<6){
		alert('<?php echo $ri_langpackage->ri_pass_wrong;?>');return false;
	}
	if(group_value==''){
		alert('<?php echo $ri_langpackage->ri_empty_wrong;?>');return false;
	}
}

</script>
</body>
</html>