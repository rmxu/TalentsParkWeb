<?php
	require("session_check.php");
	require("../foundation/ftpl_compile.php");
	require("../foundation/fchange_exp.php");
	$is_check=check_rights("b03");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$u_langpackage=new uilp;
	$ad_langpackage=new adminmenulp;
	
	//获得方案名
	$loc=short_check(get_argg('tplAct'));
	$compile_type=short_check(get_argg('compileType'));
	if(empty($loc)){
		echo '<script type="text/javascript">alert("'.$u_langpackage->u_file_no.'");window.history.go(-1);</script>';exit;
	}
	if($compileType!=$compile_type||$loc!=$tplAct){
		$content=file_get_contents("../configuration.php");
		//配置文件静态化
		$content=change_exp($content);
		$f_ref=fopen("../configuration.php","w+");
		fwrite($f_ref,trim($content));
		fclose($f_ref);
	}

	if(get_argp('batch')){
		$c_tmp=get_argp('c_tmp');
		//批量生成模板
		function batch_comp($loc,$c_tmp){
			global $compile_type;
			if($c_tmp!=''){
				foreach($c_tmp as $value){
					tpl_engine($loc,$value,0,$compile_type);
				}
			}
		}
	}else{
		//直接应用模板
		function list_child_file($local){
			global $compile_type;
			$ref=opendir("../templates/".$local);
			while($tp_dir=readdir($ref)){
				if(!preg_match("/^\./",$tp_dir)){
					if(filetype("../templates/".$local."/".$tp_dir)=="dir"){
						list_child_file($local."/".$tp_dir);
					}
					if(filetype("../templates/".$local."/".$tp_dir)=="file"){
						global $loc;
						$show_local=$local.'/'.$tp_dir;
						$show_local=preg_replace("/$loc\//","",$show_local);
						tpl_engine($loc,$show_local,0,$compile_type);
					}
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
<script type="text/javascript" language="javascript">
	parent.Dialog.close();
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_compile_status;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $u_langpackage->u_compile_state?></h3>
            <div class="content">
<table class='list_table'>
	<tr>
		<td><?php if(get_argp('batch')){batch_comp($loc,$c_tmp);}else{list_child_file($loc);}?></td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_list_back?>' onclick='window.history.go(-1);' /></td>
	</tr>
</table>
			</div>
        </div>
	</div>
</div>
	
</body>
</html>		