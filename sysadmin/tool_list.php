<?php
require("session_check.php");
$is_check=check_rights("f01");
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
if(empty($tool_item->item(0)->nodeValue)){
	$tool_item=array();
	$show_error='';
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_tools;?></a> &gt;&gt; <a href="tool_list.php"><?php echo $ad_langpackage->ad_tools_list;?></a></div>
        <hr />
<?php
foreach($tool_item as $rs){
?>
<div class="infobox">
    <h3><?php echo $rs->getElementsByTagName('toolName')->item(0)->nodeValue;?></h3>
    <div class="content">
        <table class="form-table">
            <tr>
                <td><?php echo $rs->getElementsByTagName('explain')->item(0)->nodeValue;?></td>
            </tr>
            <tr>
                <td><input type='button' class='regular-button' style='width:70px' value='<?php echo $t_langpackage->t_onclick_act?>' onclick='if(confirm("<?php echo str_replace("{tool}",$rs->getElementsByTagName('toolName')->item(0)->nodeValue,$t_langpackage->t_ask_action);?>")){window.location.href="front_end.php?app=<?php echo $rs->getElementsByTagName('contentIndex')->item(0)->nodeValue;?>";}' /></td>
            </tr>
        </table>
	</div>
</div>
<?php }?>
<div class='<?php echo $show_error;?>'>
<div class="container">
	<div class="rs_head"><?php echo $t_langpackage->t_list?></div>
</div>
<table class="list_table">
	<tr>
		<td><?php echo $show_str;?></td>
	</tr>
</table>
</div>

<div class="infobox">
    <h3><?php echo $t_langpackage->t_tip_inf;?></h3>
    <div class="content">
        <table class="list_table">
            <tr>
                <td><?php echo $t_langpackage->t_tool_pro_1;?></td>
            </tr>
            <tr>
                <td><?php echo $t_langpackage->t_tool_pro_2;?></td>
            </tr>
            <tr>
                <td><?php echo $t_langpackage->t_tool_pro_3;?></td>
            </tr>
            <tr>
                <td><?php echo $t_langpackage->t_tool_pro_4;?></td>
            </tr>
        </table>
	</div>
</div>

</body>
</html>