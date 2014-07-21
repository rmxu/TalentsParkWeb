<?php
require("session_check.php");
require("proxy/proxy.php");
$is_check=check_rights("b11");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$u_langpackage=new uilp;
$er_langpackage=new errorlp;
$ad_langpackage=new adminmenulp;

$serv_url=list_substitue("tpl");
$is_show=0;
$tpl_list=array();
$error_str='';

if($serv_content=file_get_contents($serv_url)){
	if(preg_match("/error\_\d/",$serv_content)){
		$error_str=$er_langpackage->{"er_".$serv_content};//代理程序返回错误
	}else{
		$serv_tpl_array=explode(",",$serv_content);
		foreach($serv_tpl_array as $val){
			if(!empty($val)){
				$tpl_list[]=$val;
			}
		}
		$is_show=1;
	}
}else{
	$error_str=$u_langpackage->u_get_false;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<script type='text/javascript'>
	
	function change_dir_state(dir,state){
		if(state==0){
			document.getElementById('dir_value_'+dir).value=document.getElementById('def_dir_'+dir).innerHTML;
		}else{
			document.getElementById('def_dir_'+dir).innerHTML=document.getElementById('dir_value_'+dir).value;
		}
			document.getElementById('def_dir_'+dir).style.display='';
			document.getElementById('new_dir_'+dir).style.display='none';		
	}
	
	function show_change(dir){
		document.getElementById('def_dir_'+dir).style.display='none';
		document.getElementById('new_dir_'+dir).style.display='';		
	}
	
	function download(tpl_name){
		var diag = new parent.Dialog();
		diag.Width = 300;
		diag.Height = 150;
		diag.Modal = false;
		diag.Title = "下载模板";
		diag.InnerHtml="<img src='images/loading.gif' style='vertical-align:middle;margin:5px;' />正在下载...";
		diag.show();
		var setup_dir=document.getElementById('dir_value_'+tpl_name).value;
		window.location.href='download_file.action.php?download_mod=tpl&download_name='+tpl_name+'&setup_dir='+setup_dir;
	}
	
</script>
<body>
<div id="maincontent">
    <div class="wrap">
<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="download_tpl_list.php"><?php echo $ad_langpackage->ad_download_tpl;?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $u_langpackage->u_new_list;?></h3>
<div class="content">
<?php if($is_show==1){?>
	<table class="list_table">
         <thead><tr>
           
                <th><?php echo $u_langpackage->u_tpl_name;?></th>
                <th><?php echo $u_langpackage->u_dir_pos;?></th>
                <th><?php echo $u_langpackage->u_ctrl;?></th>
            
        </tr></thead>
	<?php
	foreach($tpl_list as $rs){
	?>
		<tr>
			<td width="50%"><?php echo $rs;?></td>
			<td width="*">templates/
				<span id='def_dir_<?php echo $rs;?>'><?php echo $rs;?></span>
				<span id='new_dir_<?php echo $rs;?>' style='display:none'>
					<input type='text' id='dir_value_<?php echo $rs;?>' value='<?php echo $rs;?>' style='width:80px' />
					<input type='button' class='top_button' onclick='change_dir_state("<?php echo $rs;?>",1);' value='<?php echo $u_langpackage->u_sure;?>' />
					<input type='button' class='top_button' onclick='change_dir_state("<?php echo $rs;?>",0);' value='<?php echo $u_langpackage->u_cancel;?>' />
				</span>
			</td>
			<td width="15%">
				&nbsp;|&nbsp;
				<a href='javascript:download("<?php echo $rs;?>");' onclick='return confirm("<?php echo $u_langpackage->u_ask_tpl;?>")'><?php echo $u_langpackage->u_download;?></a> &nbsp;|&nbsp; 
				<a href='javascript:show_change("<?php echo $rs;?>");'><?php echo $u_langpackage->u_change_dir;?></a>&nbsp;|
			</td>
		</tr>
	<?php }?>
	</table>
<?php }else{?>

	<table class="list_table">
		<tr>
			<td><?php echo $error_str;?></td>
		</tr>
	</table>

<?php }?>

	<table class="list_table">
	    <thead><tr><th><?php echo $u_langpackage->u_prompt_inf;?></th></tr></thead>
	    <tr>
	        <td><?php echo $u_langpackage->u_tpl_prompt_1;?></td>
	    </tr>
	    <tr>
	        <td><?php echo $u_langpackage->u_tpl_prompt_2;?></td>
	    </tr>
	</table>
</div>
</div>
</div>
</div>
</body>
</html>