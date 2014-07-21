<?php
require("session_check.php");
require("../foundation/fchange_exp.php");
$is_check=check_rights("a03");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$li_langpackage=new limitlp;
$ad_langpackage=new adminmenulp;

if(get_argp('action')){
	$is_check=check_rights("a04");
	if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
	}
	$url="../configuration.php";
	$content=file_get_contents($url);
	$content=change_exp($content,1);
	$f_ref=fopen($url,"w+");
	$num=fwrite($f_ref,$content);
	if($num > 0){
		echo '<script type="text/javascript">alert("'.$li_langpackage->li_update_suc.'");window.history.go(-1);</script>';
	}
}

//限制访问的时间段
if(is_string($limit_guest_time)){
	$guest_time_str=$limit_guest_time;
}else{
	$guest_time_str=join("\n",$limit_guest_time);
}

//限制交互时间段
if(is_string($limit_action_time)){
	$action_time_str=$limit_action_time;
}else{
	$action_time_str=join("\n",$limit_action_time);
}

//限制访问的ip列表
if(is_string($limit_ip_list)){
	$ip_list_str=$limit_ip_list;
}else{
	$ip_list_str=join("\n",$limit_ip_list);
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_global_set;?></a> &gt;&gt; <a href="guest_limit.php"><?php echo $ad_langpackage->ad_guest_limit;?></a></div>
        <hr />
<form action="" method="post">
<div class="infobox">
<h3><?php echo $li_langpackage->li_refuse_ip;?></h3>
		<table class="form-table">
			<tr>
				<td><textarea rows='5' name='limit_ip_list' class="regular-textarea" value='<?php echo $ip_list_str;?>'><?php echo $ip_list_str;?></textarea></td>
			</tr>
			<tr>
				<td><?php echo $li_langpackage->li_refuse_ip_info;?></td>
			</tr>
		</table>
</div>

<div class="infobox">
<h3><?php echo $li_langpackage->li_refuse_time;?></h3>
		<table class="form-table">
			<tr>
				<td><textarea rows='5' name='limit_guest_time' class="regular-textarea" value='<?php echo $guest_time_str;?>'><?php echo $guest_time_str;?></textarea></td>
			</tr>
			<tr>
				<td><?php echo $li_langpackage->li_refuse_time_info;?>
</td>
			</tr>
		</table>
</div>

<div class="infobox">
<h3><?php echo $li_langpackage->li_refuse_action;?></h3>
		<table class="form-table">
			<tr>
				<td><textarea rows='5' name='limit_action_time' class="regular-textarea" value='<?php echo $action_time_str;?>'><?php echo $action_time_str;?></textarea></td>
			</tr>
			<tr>
				<td><?php echo $li_langpackage->li_refuse_action_info;?></td>
			</tr>
		</table>
</div>
<input type='submit' name='action' value='<?php echo $li_langpackage->li_submit;?>' class="regular-button" />
</form>
</body>
</html>		