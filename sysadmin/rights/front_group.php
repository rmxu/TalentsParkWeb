<?php
require(dirname(__file__)."/../session_check.php");
require_once(dirname(__file__)."/../../foundation/cxmloperator.class.php");
$ri_langpackage=new rightlp;
$ad_langpackage=new adminmenulp;
if(get_session('admin_group')!='superadmin'){
	echo $ri_langpackage->ri_refuse;exit;
}

$dbo = new dbex;
$t_frontgroup=$tablePreStr."frontgroup";
$gid=get_args('gid');

if(get_args('action')){
	dbtarget('w',$dbServs);
	$sql="update $t_frontgroup set rights='' where gid='$gid'";
	if(!is_null(get_args('rights')))$sql="update $t_frontgroup set rights='".implode(",",get_args('rights'))."' where gid='$gid'";
	$dbo->exeUpdate($sql);
	echo '<script type="text/javascript">window.location.href="front_group_manager.php";</script>';	
}

dbtarget('r',$dbServs);
$sql="select * from $t_frontgroup where gid='$gid'";
$groups=$dbo->getRow($sql);
$group=$groups['rights'];
$rights=explode(",",$group);
$rights=array_flip($rights);

$dom=new DOMDocument();
$dom->load("resources/front_resources.xml");
$classes=$dom->getElementsByTagName("group");

$dom_plugin=new DOMDocument();
$dom_plugin->load($webRoot."plugins/front_resources.xml");
$classes_plugin=$dom_plugin->getElementsByTagName("group");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<link rel="stylesheet" type="text/css" media="all" href="../css/admin.css">
<script type='text/javascript'>
function select_rights(obj){
	var group=document.getElementsByTagName('input');
	for(i=0;i<group.length;i++){
		if(group[i].type=='checkbox'&&group[i].group_id==obj.group_id)group[i].checked=obj.checked;
	}
}
</script>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_rights_manage;?></a> &gt;&gt; <a href="front_group.php?gid=<?php echo $gid;?>"><?php echo $ad_langpackage->ad_front_role_rights;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $ri_langpackage->ri_allot;?></h3>
            <div class="content">
<form action="" method="post">
	<table class='list_table'>
		<tr><th><?php echo $ri_langpackage->ri_local_user;?>：<?php echo $groups['name'];?></th></tr>
<?php
$group_num=0;
foreach($classes as $class){
	$group_num++;
	echo "<tr><td>".$class->getAttributeNode('value')->value."&nbsp<input type='checkbox' group_id='group_".$group_num."' onclick='select_rights(this)' />".$ri_langpackage->ri_all_select."</td></tr><tr><td>";
	$items=$class->getElementsByTagName("resource");
	for($i = 0; $i < $items->length;$i++){
	$is_checked="";
	if(isset($rights[$items->item($i)->getAttributeNode('id')->value])){
		$is_checked="checked=checked";
	}
?>
	<li class='rights'><input type="checkbox" group_id="<?php echo "group_".$group_num;?>" name="rights[<?php echo $items->item($i)->getAttributeNode('id')->value;?>]"  value="<?php echo $items->item($i)->getAttributeNode('id')->value;?>"  <?php echo $is_checked;?> /><?php echo $items->item($i)->getAttributeNode('value')->value;?></li>
<?php }?>
	</td></tr>
	<tr><td class="dotted_line">&nbsp;</td></tr>
<?php }
foreach($classes_plugin as $class){
	$group_num++;
	echo "<tr><td>".$class->getAttributeNode('value')->value."&nbsp<input type='checkbox' group_id='group_".$group_num."' onclick='select_rights(this)' />".$ri_langpackage->ri_all_select."</td></tr><tr><td>";
	$items=$class->getElementsByTagName("resource");
	for($i = 0; $i < $items->length;$i++){
	$is_checked="";
	if(isset($rights[$items->item($i)->getAttributeNode('id')->value])){
		$is_checked="checked=checked";
	}
?>
	<li class='rights'><input type="checkbox" group_id="<?php echo "group_".$group_num;?>" name="rights[<?php echo $items->item($i)->getAttributeNode('id')->value;?>]"  value="<?php echo $items->item($i)->getAttributeNode('id')->value;?>"  <?php echo $is_checked;?> /><?php echo $items->item($i)->getAttributeNode('value')->value;?></li>
<?php }?>
	</td></tr>
	<tr><td class="dotted_line">&nbsp;</td></tr>
<?php }
?>
	<tr><td><input type="submit" name="action" class='regular-button' value="<?php echo $ri_langpackage->ri_allot;?>"/></td></tr>
	</table>
</form>
        	</div>
        </div>
    </div>
</div>
</body>
</html>
