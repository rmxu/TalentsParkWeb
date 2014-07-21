<?php
require("session_check.php");
$is_check=check_rights("f03");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$t_langpackage=new toollp;
$ad_langpackage=new adminmenulp;

$xmlDom=new DomDocument;
$xmlDom->load('toolsBox/tool.xml');
$tool_item=$xmlDom->getElementsByTagName('tool_item');
$show_str='';
$show_error='content_none';
$show_data='';
if(empty($tool_item->item(0)->nodeValue)){
	$tool_item=array();
	$show_error='';
	$show_data='content_none';
	$show_str=$t_langpackage->t_none_tool;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_tools;?></a> &gt;&gt; <a href="manage_tool.php"><?php echo $ad_langpackage->ad_tools_manage;?></a></div>
        <hr />
<div class="infobox">
    <h3><?php echo $t_langpackage->t_tool_manage;?></h3>
    <div class="content">
<table class="list_table <?php echo $show_data;?>">
	<thead><tr>
    
		<th><?php echo $t_langpackage->t_name;?></th>
		<th width="100" style=" text-align:center"><?php echo $t_langpackage->t_code_num;?></th>
		<th width="100" style=" text-align:center"><?php echo $t_langpackage->t_time;?></th>
		<th width="100" style=" text-align:center"><?php echo $t_langpackage->t_author;?></th>
		<th width="100" style=" text-align:center"><?php echo $t_langpackage->t_ctrl;?></th>
    
	</tr></thead>
<?php
foreach($tool_item as $rs){
?>
	<tr>
		<td><?php echo $rs->getElementsByTagName('toolName')->item(0)->nodeValue;?></td>
		<td style=" text-align:center"><?php echo $rs->getAttribute("id");?></td>
		<td style=" text-align:center"><?php echo $rs->getElementsByTagName('date')->item(0)->nodeValue;?></td>
		<td style=" text-align:center"><?php echo $rs->getElementsByTagName('author')->item(0)->nodeValue;?></td>
		<td style=" text-align:center"><a href='unload_tool.php?id=<?php echo $rs->getAttribute("id");?>' onclick='return confirm("<?php echo $t_langpackage->t_ask_unset;?>")'><?php echo $t_langpackage->t_unset;?></a></td>
	</tr>
<?php }?>
</table>

<table class="list_table <?php echo $show_error;?>">
	<tr>
		<td><?php echo $show_str;?></td>
	</tr>
</table>
</div>
</div>

<div class="infobox">
    <h3><?php echo $t_langpackage->t_tip_inf;?></h3>
    <div class="content">
<table class="list_table">
	<tr>
		<td><?php echo $t_langpackage->t_manage_pro_1;?></td>
	</tr>
	<tr>
		<td><?php echo $t_langpackage->t_manage_pro_2;?></td>
	</tr>	
	<tr>
		<td><?php echo $t_langpackage->t_manage_pro_3;?></td>
	</tr>	
</table>
	</div>
</div>
</body>
</html>