<?php
require(dirname(__file__)."/../session_check.php");
$ri_langpackage=new rightlp;
$u_langpackage=new uilp;
$m_langpackage=new modulelp;
$ad_langpackage=new adminmenulp;
if(get_session('admin_group')!='superadmin'){
	echo $ri_langpackage->ri_refuse;exit;
}

//读写数据库
$dbo = new dbex;
dbtarget('w',$dbServs);
$t_frontgroup=$tablePreStr."frontgroup";

$id=get_args('id');
$value=get_args('value');
$elements=array();

if(get_args("add")){
	$sql="select * from $t_frontgroup where gid='$id'";
	$group=$dbo->getRow($sql);
	if(!$group){
		$sql="insert into $t_frontgroup(gid,name)values('$id','$value')";
		$dbo->exeUpdate($sql);
	}else{
		echo $ri_langpackage->ri_isset_id;exit;
	}
}else if(get_args('del')=='del'){
	$sql="delete from $t_frontgroup where gid='$id'";
	$dbo->exeUpdate($sql);
}else if(get_args('update')){
	$sql="update $t_frontgroup set name='$value' where gid='$id'";
	$dbo->exeUpdate($sql);
}

$sql="select * from $t_frontgroup";
$elements=$dbo->getRs($sql);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="../css/admin.css">
<script type='text/javascript' src='../../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function update_group(id_value){
	var group_value=document.getElementById("value_"+id_value).value;
	if(group_value==''){
		alert('<?php echo $ri_langpackage->ri_user_wrong;?>');
	}else{
		var update_group=new Ajax();
		update_group.getInfo("front_group_manager.php?update=1","post","app","id="+id_value+"&value="+group_value,function(c){document.getElementById("group_"+id_value).innerHTML=group_value;change_add("show",id_value);});
	}
}
function check_form(){
	var add_id=document.getElementById("add_id").value;
	var add_value=document.getElementById("add_value").value;
	if(add_id=='' || add_value==''){
		alert('<?php echo $ri_langpackage->ri_empty_info;?>');
		return false;
	}
}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="front_group_manager.php"><?php echo $ad_langpackage->ad_member_role_distribution;?></a></div>
        <hr />
<div class="infobox">
    <h3><?php echo $ri_langpackage->ri_user_manage;?></h3>
    <div class="content">
<form id="form" method="post" onsubmit='return check_form()'>
	<table class='list_table'>
		<?php
			foreach($elements as $element){
		?>
		<tr>
			<td><span id='group_<?php echo $element['gid'];?>'><?php echo $element['name'];?></span></td>
			<td>
				<span id='show1_<?php echo $element['gid'];?>'>
					<a href='javascript:change_add("show","<?php echo $element['gid'];?>")'><?php echo $u_langpackage->u_amend;?></a>&nbsp|&nbsp
					<a href='front_group.php?gid=<?php echo $element['gid'];?>'><?php echo $ri_langpackage->ri_allot;?></a>&nbsp|&nbsp
					<a href="?del=del&id=<?php echo $element['gid'];?>"><?php echo $m_langpackage->m_del;?></a>
				</span>
				<span id='show2_<?php echo $element['gid'];?>' style='display:none'>
					<?php echo $ri_langpackage->ri_user_name;?>：<input autocomplete='off' class='small-text' id='value_<?php echo $element['gid'];?>' value='<?php echo $element['name'];?>'/>&nbsp;
					<input type='button' onclick=update_group("<?php echo $element['gid'];?>") name='update' class='regular-button' value='<?php echo $u_langpackage->u_amend;?>'>&nbsp;
					<input type='reset' onclick='change_add("show","<?php echo $element['gid'];?>")' class='regular-button' value='<?php echo $u_langpackage->u_cancel;?>'>
				</span>
			</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td colspan=2>
				<span id='add1_id'><input type='button' onclick='change_add("add","id")' class='regular-button' value='<?php echo $ri_langpackage->ri_add;?>' /></span>
				<span id='add2_id' style='display:none'>
					<?php echo $ri_langpackage->ri_user_id;?>：<input class="small-text" autocomplete='off' id='add_id' name="id" value=""/> &nbsp 
					<?php echo $ri_langpackage->ri_user_name;?>：<input autocomplete='off' class="small-text" id='add_value' name="value" value=""/>&nbsp;
					<input type="submit" name="add" class='regular-button' value="<?php echo $ri_langpackage->ri_add;?>">&nbsp;
					<input onclick='change_add("add","id");' type="button" class='regular-button' value="<?php echo $u_langpackage->u_cancel;?>">
				</span>
			</td>
		</tr>
	</table>
</form>
</div>
</div>
</div>
</div>
<script type='text/javascript'>
	function change_add(name_value,id_value){
		if(document.getElementById(name_value+"1_"+id_value).style.display=='none'){
			document.getElementById(name_value+"1_"+id_value).style.display='';
			document.getElementById(name_value+"2_"+id_value).style.display='none';
		}else{
			document.getElementById(name_value+"1_"+id_value).style.display='none';
			document.getElementById(name_value+"2_"+id_value).style.display='';
		}
	}
</script>
</body>
</html>
