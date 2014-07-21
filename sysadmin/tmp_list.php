<?php
require("session_check.php");
	$is_check=check_rights("b01");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$u_langpackage=new uilp;
	$ad_langpackage=new adminmenulp;
	
	$loc=short_check(get_argg('tplAct'));
	
	if(empty($loc)){
		echo "<script type='text/javascript'>alert('".$u_langpackage->u_file_no."');window.history.go(-1);</script>";
	}
	
	function list_child_file($local){
		global $u_langpackage;
		$ref=opendir("../templates/".$local);
		while($tp_dir=readdir($ref)){
			if(!preg_match("/^\./",$tp_dir)){
				if(filetype("../templates/".$local."/".$tp_dir)=="dir"){
					list_child_file($local."/".$tp_dir);
				}
				if(filetype("../templates/".$local."/".$tp_dir)=="file"){
					$act_time=date("Y-m-d H:i:s",filemtime("../templates/".$local));
					global $loc;
					$show_local=$local.'/'.$tp_dir;
					$show_local=preg_replace("/$loc\//","",$show_local);
					echo '<tr><td><input type="checkbox" name="c_tmp[]" value="'.$show_local.'" /></td><td>'.$show_local.'</td><td>'.$act_time.'</td><td><a href="change_tmp.php?tmp_path='.$local.'/'.$tp_dir.'">'.$u_langpackage->u_amend.'</a></td></tr>';
				}
			}
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

ifcheck =true;
function all_select(form)
{
	for(i = 0; i < form.elements.length; i ++)
	{
		var e = form.elements[i];
		if (e.type == 'checkbox')
		{
			e.checked = ifcheck;
		}
	}
	ifcheck = (ifcheck == true) ? false : true;
}

</script>
</head>
<body>
<div id="maincontent">
	<div class="wrap">
		<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="tmp_list.php?tplAct=default"><?php echo $ad_langpackage->ad_manage_tpl;?></a></div>
		<hr />
		<div class="infobox">
			<h3><?php echo $u_langpackage->u_tempfile_list?></h3>
			<div class="content">
				<form action='action_comp.php?tplAct=<?php echo $loc;?>' method='post'>
					<table class='list_table'>
						<thead><tr>
							
					      <th width=30px><?php echo $u_langpackage->u_choice?></th>
					      <th width=100%><?php echo $u_langpackage->u_file_name?></th>
					      <th width=150px><?php echo $u_langpackage->u_amend_time?></th>
					      <th width=100px><?php echo $u_langpackage->u_admin_handle?></th>
					    
						</tr></thead>
						<?php
							list_child_file($loc);
						?>
						<tr>
							<th colspan='5'>
								<input type="button" value="<?php echo $u_langpackage->u_check?>" class="regular-button" onClick="all_select(form);" />
								<input type="submit" value="<?php echo $u_langpackage->u_compile?>" class="regular-button" name='batch' />
								<input type="button" value="<?php echo $u_langpackage->u_list_back?>" class="regular-button" onclick='javascript:history.go(-1);' />
							</th>
						</tr>
					</table>	
				</form>
				<table class="list_table">
					<thead><tr><th><?php echo $u_langpackage->u_clew_inf?></th></tr></thead>
					<tr>
						<td>
							<?php echo str_replace('{temp}',$loc,$u_langpackage->u_temp_save);?>
							<?php echo $u_langpackage->u_do_flow?> <br />
							<?php echo $u_langpackage->u_flow_1?><br />
							<?php echo $u_langpackage->u_flow_2?><br />
							<?php echo $u_langpackage->u_flow_3?><br />
							<?php echo $u_langpackage->u_flow_4?><br />
							<?php echo $u_langpackage->u_flow_5?><br />
							<?php echo $u_langpackage->u_flow_6?><br />
						</td>
					</tr>
				</table>				
			</div>
		</div>
	</div>
</div>
</body>
</html>		