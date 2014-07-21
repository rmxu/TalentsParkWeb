<?php
require("session_check.php");
require("../foundation/fchange_exp.php");
$is_check=check_rights("b04");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$u_langpackage=new uilp;
$ad_langpackage=new adminmenulp;

$skin=get_argg("skinUrl");
if($skin && $skinUrl!=$skin){
	$is_check=check_rights("b05");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	$url="../configuration.php";
	$content=file_get_contents($url);
	$content=change_exp($content);
	$f_ref=fopen($url,"w+");
	$num=fwrite($f_ref,$content);
	fclose($f_ref);
	echo '<script>window.location.href="manage_skin.php";</script>';
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
<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="manage_skin.php"><?php echo $ad_langpackage->ad_manage_skin;?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $u_langpackage->u_skin_admin;?></h3>
<div class="content">
	<table class='list_table'>
        <thead><tr>
            
                <th><?php echo $u_langpackage->u_skin_plan;?></th>
                <th style="text-align:center"><?php echo $u_langpackage->u_currently_apply;?></th>
                <th width="100px" style="text-align:center"><?php echo $u_langpackage->u_admin_handle;?></th>
            
        </tr></thead>
		<?php
		if(file_exists("../skin/".$tplAct)){
			$ref=opendir("../skin/".$tplAct);
			while($skin_dir=readdir($ref)){
				if(!preg_match("/^\./",$skin_dir)&&$skin_dir!='js'&&$skin_dir!='home'){
					$selected="";
					if($skinUrl==$tplAct.'/'.$skin_dir){
						$selected="√";
					}
						echo '<tr><td>'.$skin_dir.'</td>  <td style=text-align:center>'.$selected.'</td>';
						echo '<td width="100px"  style="text-align:center">&nbsp&nbsp<a href="manage_skin.php?skinUrl='.$tplAct."/".$skin_dir.'">'.$u_langpackage->u_app_skin.'</a>&nbsp&nbsp</td></tr>';
				}
			}
		}
		?>
	</table>	

<table class="list_table">
	<thead><tr><th><?php echo $u_langpackage->u_prompt_inf;?></th></tr></thead>
	<tr>
		<td>
		<?php echo str_replace("{template}",$tplAct,$u_langpackage->u_skin_1);?><br />
		<?php echo $u_langpackage->u_skin_2?><br />
		<?php echo $u_langpackage->u_skin_3?><br />
		<?php echo $u_langpackage->u_skin_4?><br />
		<?php echo $u_langpackage->u_skin_5?><br />
		</td>
	</tr>
</table>
</div>
</div>
</div>
</div>
</body>
</html>		