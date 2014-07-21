<?php
require("session_check.php");
require("proxy/proxy.php");
$is_check=check_rights("e01");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$so_softwarelp=new softwarelp;
$er_langpackage=new errorlp;
$u_langpackage=new uilp;
$ad_langpackage=new adminmenulp;

$version_url="../docs/version.txt";
$whole_version=file_get_contents($version_url);
$version=intval(str_replace(".","",$whole_version));
$serv_url=list_substitue("software","&version=".$whole_version);
$show_error='';
$show_data='content_none';
$show_str='';
$ver_str=file_get_contents($serv_url);
if($ver_str==''){
	$show_str=$so_softwarelp->so_list_false;
}else{
	if(preg_match("/error\_\d/",$ver_str)){
		$show_str=$er_langpackage->{"er_".$ver_str};//代理程序返回错误
	}
	elseif(!preg_match("/<software>.+<\/software>/",$ver_str)){
		$show_str=$so_softwarelp->so_last_version;
	}
	else{
		$xmlDom=new DomDocument;
		$xmlDom->loadXML($ver_str);
		$xmlDom->formatOutput=true;
		$version_nodes=$xmlDom->getElementsByTagName('version');
		$date_nodes=$xmlDom->getElementsByTagName('date');
		$show_error='content_none';
		$show_data='';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript'>
	function download_action(software){
		var diag = new parent.Dialog();
		diag.Width = 300;
		diag.Height = 150;
		diag.Modal = false;
		diag.Title = <?php echo $ad_langpackage->ad_update_online;?>;
		diag.InnerHtml="<img src='images/loading.gif' style='vertical-align:middle;margin:5px;' /><?php echo $ad_langpackage->ad_update_online;?>...";
		diag.show();
		window.location.href="update_software.action.php?version="+software;
	}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_global_set;?></a> &gt;&gt; <a href="software_list.php"><?php echo $ad_langpackage->ad_update_online;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $so_softwarelp->so_update_list;?></h3>
            <div class="content">
<?php if($show_data==''){?>
	<table class="list_table">
		<thead><tr>
	    	
			<th><?php echo $so_softwarelp->so_soft_version;?></th>
			<th><?php echo $so_softwarelp->so_time;?></th>
			<th><?php echo $so_softwarelp->so_ctrl;?></th>
	        
		</tr></thead>
	<?php for($num=0;$num < $version_nodes->length;$num++){?>
		<tr>
			<td><?php echo $version_nodes->item($num)->nodeValue;?></td>
			<td><?php echo date("Y-m-d H:i:s",$date_nodes->item($num)->nodeValue);?></td>
			<td><a href='javascript:download_action("<?php echo $version_nodes->item($num)->nodeValue;?>");' onClick="return confirm('<?php echo $so_softwarelp->so_ask_update;?>');"><?php echo $so_softwarelp->so_act_update;?></a></td>
		</tr>
	<?php }?>
	</table>
<?php }?>

<table class="list_table <?php echo $show_error;?>">
	<tr>
		<td><?php echo $show_str;?></td>
	</tr>
</table>

<table class="list_table">
	<thead><tr><th><?php echo $so_softwarelp->so_tip_inf;?></th></tr></thead>
	<tr>
		<td><?php echo str_replace("{version}",$whole_version,$so_softwarelp->so_loction_version);?></td>
	</tr>
	<tr>
		<td><?php echo $so_softwarelp->so_pro_1;?></td>
	</tr>
	<tr>
		<td><?php echo $so_softwarelp->so_pro_2;?></td>
	</tr>
	<tr>
		<td><?php echo $so_softwarelp->so_pro_3;?></td>
	</tr>
</table>
	</div>
</div>
</body>
</html>