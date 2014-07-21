<?php
require("session_check.php");	
	
	//语言包引入
	$u_langpackage=new uilp;
	$lp_u_make_suc=$u_langpackage->u_make_suc;
	$lp_u_make_lose=$u_langpackage->u_make_lose;
	$lp_u_cback_suc=$u_langpackage->u_cback_suc;
	$lp_u_cback_lose=$u_langpackage->u_cback_lose;
	
	$re_type=short_check(get_argg('r_type'));
	
	function site_re($from_dir,$to_dir){
		global $lp_u_make_suc;
		global $lp_u_make_lose;
		global $lp_u_cback_suc;
		global $lp_u_cback_lose;
		$ref=opendir($from_dir);
		if(!file_exists($to_dir)){
			mkdir($to_dir);
		}
		while($tp_dir=readdir($ref)){
			if(!preg_match("/^\./",$tp_dir)){
				if(filetype($from_dir.$tp_dir)=="dir"){
					if(!file_exists($to_dir.$tp_dir)){
						$is_m=mkdir($to_dir.$tp_dir."/");
						if($is_m==true){
							echo str_replace('{dir}',$to_dir.$tp_dir,$lp_u_make_suc)."<br />";
						}else{
							echo "<font color='red'>". str_replace('{dir}',$to_dir.$tp_dir,$lp_u_make_lose)."</font><br />";
						}
					}
					site_re($from_dir.$tp_dir."/",$to_dir.$tp_dir."/");
				}
				if(filetype($from_dir.$tp_dir)=="file"){
					$show_local=$from_dir.$tp_dir;
					$is_c=copy($show_local,$to_dir.$tp_dir);
					if($is_c==true){
						echo str_replace('{dir}',$tp_dir,$lp_u_cback_suc)." <br />";
					}else{
						echo "<font color='red'>". str_replace('{dir}',$tp_dir,$lp_u_cback_lose)."</font><br />";
					}
				}
			}
		}
	}
	
	switch ($re_type){
		
		case "tmp":
		$is_check=check_rights("b07");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$str=$u_langpackage->u_cback_temp;
		$from_dir="../defaultview/tpl/";
		$to_dir="../templates/default/";
		break;
		
		case "mod":
		$is_check=check_rights("b08");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$str=$u_langpackage->u_cback_model;
		$from_dir="../defaultview/models/";
		$to_dir="../models/";
		break;
		
		case "skin":
		$is_check=check_rights("b09");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$str=$u_langpackage->u_cback_skin;
		$from_dir="../defaultview/skin/";
		$to_dir="../skin/default/";
		break;
		
		case "c_com":
		$is_check=check_rights("b10");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		$str=$u_langpackage->u_cback_all;
		$from_dir="../defaultview/c_files/";
		$to_dir="../";
		break;
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript'>parent.Dialog.close();</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="infobox">
            <h3><?php echo $str;?></h3>
            <div class="content">
<table class='form-table'>
	<tr>
		<td>
			<?php echo site_re($from_dir,$to_dir);?>
		</td>
	</tr>
	<tr>
		<td><input type='button' class='regular-button' value='<?php echo $u_langpackage->u_back?>' onclick='window.history.go(-1);' /></td>
	</tr>
</table>
        	</div>
        </div>
    </div>
</div>
</body>
</html>		