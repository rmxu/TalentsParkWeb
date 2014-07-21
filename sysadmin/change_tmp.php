<?php
require("session_check.php");
	$is_check=check_rights("b02");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$u_langpackage=new uilp;
	$ad_langpackage=new adminmenulp;
	//用save_tmp来判断读写操作
	if(get_argp('save_tmp')){
		if(get_magic_quotes_gpc()){
			$tmp_contents=stripslashes(get_argp('tmp_code'));
		}else{
			$tmp_contents=get_argp('tmp_code');
		}

		$tmp_contents=str_replace(array("&lt;","&gt;"),array("<",">"),$tmp_contents);
		
		$t_whole_path=get_argp('whole_path');
		
		$tmp_type=get_argp('tmp_type');

		$tmp_ref=fopen($t_whole_path,"w");
				
		fwrite($tmp_ref,$tmp_contents);
		
		fclose($tmp_ref);
		
		echo "<script type='text/javascript'>alert('$u_langpackage->u_amend_suc');window.location.href='tmp_list.php?tplAct=$tmp_type';</script>";
		
	}else{

		$tmp_path=get_argg('tmp_path');//接受路径名
		
		$ex_path=explode("/",$tmp_path);//切割路径取得所属模板
		
		$tmp_type=$ex_path[0];//赋值所属模板
		
		$last_c_time=date("Y-m-d H:i:s",filemtime("../templates/".$tmp_path));//取得文件上次修改时间
		
		$whole_path="../templates/".$tmp_path;//完整路径名
		
		$file_contents=file_get_contents($whole_path);//把文件内容读取到变量中
		
		$file_contents=str_replace(array("<",">"),array("&lt;","&gt;"),$file_contents);
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_ui_set;?></a> &gt;&gt; <a href="change_tmp.php?tmp_path=<?php echo $tmp_path;?>"><?php echo $ad_langpackage->ad_amend_temp;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $u_langpackage->u_amend_temp?></h3>
            <div class="content">
<form action="" method="post">
	<input type='hidden' value='<?php echo $tmp_type;?>' name='tmp_type' />
	<input type='hidden' value='<?php echo $whole_path;?>' name='whole_path' />
	<table class="form-table">
		<tr>
			<th width="15%"><?php echo $u_langpackage->u_belong_temp?>：</th><td><?php echo $tmp_type;?></td>	
		</tr>
		
		<tr>
			<th><?php echo $u_langpackage->u_temp_path?>：</th><td><font color=blue><?php echo "../templates/".$tmp_path;?></font>（<?php echo $u_langpackage->u_last_amend_time?>：<?php echo $last_c_time;?>）<font color=red>*</font></td>
		</tr>
		
		<tr>
			<td colspan="2">
				<textarea align="left" wrap='off' style='width:750px;height:360px;overflow:auto;scrolling:yes;font-family:Fixedsys,verdana,宋体;font-size:12px;' id='tmp_code' name='tmp_code'><?php echo $file_contents;?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<input type='submit' value='<?php echo $u_langpackage->u_save?>' class='regular-button' name="save_tmp" />&nbsp
				<input type='reset' value='<?php echo $u_langpackage->u_reset?>' class='regular-button' />&nbsp
				<input type='button' value='<?php echo $u_langpackage->u_list_back?>' class='regular-button' onclick='window.history.go(-1);' />
			</td>
		</tr>
		
	</table>
</form>
        	</div>
        </div>
    </div>
</div>
</body>
</html>		