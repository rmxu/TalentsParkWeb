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
	
	$serve_checked='';
	$debug_checked='';
	if($compileType=='debug'){
		$debug_checked='checked=checked';
	}else{
		$serve_checked='checked=checked';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript'>
	function compile_action(tp_dir){
		var diag = new parent.Dialog();
		diag.Width = 300;
		diag.Height = 150;
		diag.Modal = false;
		diag.Title = "<?php echo $u_langpackage->u_compling;?>";
		diag.InnerHtml="<img src='images/loading.gif' style='vertical-align:middle;margin:5px;' /><?php echo $u_langpackage->u_compling;?>";
		diag.show();
		var compile_radio=document.getElementsByName('tpl_compile_type');
		for(i=0;i < compile_radio.length;i++){
			if(compile_radio[i].checked==true){
				var compile_type=compile_radio[i].value;
			}
		}
		window.location.href="action_comp.php?tplAct="+tp_dir+"&compileType="+compile_type;
	}
	
	function change_compile(tp_dir){
		var is_compile=confirm("<?php echo $u_langpackage->u_comp_info;?>");
		if(is_compile==true){
			if(confirm("<?php echo $u_langpackage->u_admin_con;?>")){
				compile_action(tp_dir);
			}
		}
	}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="manage_template.php"><?php echo $ad_langpackage->ad_manage_tpl;?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $u_langpackage->u_temp_admin;?></h3>
<div class="content">
	<table class='list_table'>
		<thead><tr>
            
                <th><?php echo $u_langpackage->u_temp_list;?></th>
                <th><?php echo $u_langpackage->u_amend_time;?></th>
                <th><?php echo $u_langpackage->u_currently_apply;?></th>
                <th><?php echo $u_langpackage->u_user_skin;?></th>
                <th width='250px' style='text-align:center'><?php echo $u_langpackage->u_admin_handle;?></th>
            
		</tr></thead>
		<?php
			$ref=opendir("../templates");
			while($tp_dir=readdir($ref)){
				if(!preg_match("/^\./",$tp_dir)){
					$act_time=date("Y-m-d H:i:s",filemtime("../templates/".$tp_dir));
					$selected="";
					$skin_select="";
					if($tplAct==$tp_dir){
						$selected="√";
					}
					if(file_exists("../skin/".$tp_dir)){
						$skin_def=opendir("../skin/".$tp_dir);
						while($skin_dir=readdir($skin_def)){
							if(!preg_match("/^\./",$skin_dir)){
								if($tp_dir."/".$skin_dir==$skinUrl){
									$skin_select=$skin_dir;
								}
							}
						}
					}
					echo '<tr><td>'.$tp_dir.'</td> <td>'.$act_time.'</td> <td>'.$selected.'</td>';
					echo '
<td>'.$skin_select.'</td>';
					echo '<td style=text-align:center>&nbsp&nbsp<a href="javascript:compile_action(\''.$tp_dir.'\');" onclick=\'return confirm("'.$u_langpackage->u_admin_con.'");\'>'.$u_langpackage->u_app_temp.'</a>&nbsp&nbsp<a href="tmp_list.php?tplAct='.$tp_dir.'">'.$u_langpackage->u_admin_temp.'</a>&nbsp&nbsp';if($selected=="√"){echo '<a href="manage_skin.php">'.$u_langpackage->u_choose_skin.'</a>&nbsp&nbsp';} echo '</td></tr>';
				}
			}
		?>
		<tr><td colspan="5"><?php echo $u_langpackage->u_comp_type1;?>&nbsp;<input type='radio' value='serve' name='tpl_compile_type' <?php echo $serve_checked;?> /> &nbsp; <?php echo $u_langpackage->u_comp_type2;?>&nbsp;<input type='radio' value='debug' name='tpl_compile_type' <?php echo $debug_checked;?> /> &nbsp;<br /> <input type='button' class='regular-button' value='<?php echo $u_langpackage->u_open;?>' onclick=change_compile("<?php echo $tplAct;?>") /></td></tr>
</table>

<table class="list_table">
	<thead><tr><th><?php echo $u_langpackage->u_prompt_inf?></th></tr></thead>
	<tr>
		<td>
		<?php echo $u_langpackage->u_prompt_1;?><br />
		<?php echo str_replace('{default}',$tplAct,$u_langpackage->u_prompt_2);?><br />
		<?php echo $u_langpackage->u_prompt_3;?><br />
		<?php echo $u_langpackage->u_prompt_4;?><br />
		<?php echo $u_langpackage->u_prompt_5;?><br />
		<?php echo $u_langpackage->u_prompt_6;?><br />
		</td>
	</tr>
</table>
</div>
</div>
</div>
</div>
</body>
</html>