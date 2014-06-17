<?php
	require("session_check.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c25");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");

	//数据表定义区
	$t_table=$tablePreStr."photo";

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

	$eq_array=array('user_id','album_id','photo_id');
	$like_array=array('photo_information');
	$date_array=array("add_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

	$dbo->setPages($c_perpage,$page_num);//设置分页
	$photo_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//按字段排序
	$o_def='';$o_add_time='';$o_hits='';
	if(!get_argg('order_by')||get_argg('order_by')=="album_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($photo_rs)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<title></title>
</head>
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function del_pht(photo_id,album_id,user_id)
{
	var del_pht=new Ajax();
	del_pht.getInfo("photo_del.action.php","GET","app","pid="+photo_id+"&aid="+album_id+"&uid="+user_id,"operate_"+photo_id);
}
function check_form()
{
	var dateline1=document.getElementById("add_time1").value;
	var dateline2=document.getElementById("add_time2").value;
	var time_format=/\d{4}\-\d{2}\-\d{2}/;
	if(dateline1){
		if(!time_format.test(dateline1)){
			alert("<?php echo $m_langpackage->m_date_wrong?>");
			return false;
		}
	}
	if(dateline2){
		if(!time_format.test(dateline2)){
			alert("<?php echo $m_langpackage->m_date_wrong?>");
			return false;
		}
	}
}

function photo_lock_callback(photo_id,type_value){
	if(type_value==0){
		str="<font color='red'><?php echo $m_langpackage->m_lock?></font>";
		document.getElementById("unlock_button_"+photo_id).style.display="";
		document.getElementById("lock_button_"+photo_id).style.display="none";
	}else{
		str="<?php echo $m_langpackage->m_normal?>";
		document.getElementById("unlock_button_"+photo_id).style.display="none";
		document.getElementById("lock_button_"+photo_id).style.display="";
	}
	window.document.getElementById("state_"+photo_id).innerHTML=str;
}

function photo_lock(photo_id,type_value)
{
	var photo_lock=new Ajax();
	photo_lock.getInfo("photo_lock.action.php","GET","app","photo_id="+photo_id+"&type_value="+type_value,function(c){photo_lock_callback(photo_id,type_value);});
}

function checkAll(obj){
	var form_obj=document.getElementById(obj);
	var input_obj=form_obj.getElementsByTagName('input');
	for(i=0;i<input_obj.length;i++){
		if(input_obj[i].type=='checkbox'){
			if(input_obj[i].checked==true){
				input_obj[i].checked='';
			}else{
				input_obj[i].checked='checked';
			}
		}
	}
}
</script>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="photo_list.php?order_by=photo_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_photo;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form method="get" action=""  onsubmit="return check_form()">
<TABLE class="form-table">
<tr>
<th width=12%><?php echo $m_langpackage->m_alb_id?></th><td width=30%><input type="text" class="small-text" name="album_id" value="<?php echo get_argg('album_id');?>" /></td>
<th width=10%><?php echo $m_langpackage->m_author_id?></th><td width=*><input type="text" class="small-text" name="user_id" value="<?php echo get_argg('user_id');?>" /></td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_pho_id?></th><td><input type="text" class="small-text" name="photo_id" value="<?php echo get_argg('photo_id');?>" /></td>
<th><?php echo $m_langpackage->m_upd_time?></th>
<td>
<input type="text" class="small-text" name="add_time1" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time1');?>" /> ~
<input type="text" class="small-text" name="add_time2" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time2');?>" /> (格式为: YYYY-MM-DD)
</td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_pho_inf?></th><td><input type="text" class="small-text" name="photo_information" value="<?php echo get_argg('photo_information');?>" />&nbsp;<font color=red>*</font></td>
</tr>

<tr><th><?php echo $m_langpackage->m_result_order?></th>
<td colspan="3">
<select name="order_by">
	<OPTION value="photo_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
	<OPTION value='add_time' <?php echo $o_add_time;?>><?php echo $m_langpackage->m_creat_time?></OPTION>
</select>
<?php echo order_sc();?>
<?php echo perpage();?>
</td>
</tr>
<tr><td colspan=2><?php echo $m_langpackage->m_red?></td></tr>
<tr><td colspan=2><INPUT name="searchsubmit" class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
</table>
</form>
	</div>
</div>

<div class="infobox">
    <h3><?php echo $m_langpackage->m_pho_list;?></h3>
    <div class="content">
<form method="post" action="photo_del.action.php" id='data_list'>
<table border="0" class='list_table <?php echo $isset_data;?>'>
<?php
$i=0;
foreach($photo_rs as $val){
	if($i%2==0){
		if($i>0){ echo "</tr>"; }
		echo "<tr>";
	}
?>
<td width="20px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $val['photo_id'];?>" /></td>
<td width="120px" align="center">
<a href="../<?php echo $val['photo_src'];?>" target="_blank"><img src="../<?php echo $val['photo_src'];?>" width="100" /></a>
</td>
<td style='text-align:left'>
<?php echo $m_langpackage->m_alb_id?>：album_id-<?php echo $val['album_id'];?>
<br />
<?php echo $m_langpackage->m_author_id?>：<a href="../home.php?h=<?php echo $val['user_id'];?>" target="_blank">user_id-<?php echo $val['user_id'];?></a>
<br />
<?php echo $m_langpackage->m_pho_inf?>：<?php echo $val['photo_information'];?>
<br />
<?php echo $m_langpackage->m_time?>: <?php echo $val['add_time'];?><br>
<a href="comment_list.php?idtype=photo_comment&photo_id=<?php echo $val['photo_id'];?>"><?php echo $m_langpackage->m_com_admin?></a>
<span id="state_<?php echo $val['photo_id'];?>"><?php if($val['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></span>
<span id="operate_<?php echo $val['photo_id'];?>">
<a href='javascript:del_pht(<?php echo $val['photo_id'];?>,<?php echo $val['album_id'];?>,<?php echo $val['user_id']?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");'><img src='images/del.gif' /></a>
<?php $unlock="display:none";$lock=""; if($val['is_pass']==0){$unlock="";$lock="display:none";}?>
<span id="unlock_button_<?php echo $val['photo_id'];?>" style="<?php echo $unlock;?>"><a href="javascript:photo_lock(<?php echo $val['photo_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock?>" alt="<?php echo $m_langpackage->m_unlock?>" src="images/unlock.gif" /></a></span><span id="lock_button_<?php echo $val['photo_id'];?>" style="<?php echo $lock;?>"><a href="javascript:photo_lock(<?php echo $val['photo_id'];?>,0);" onclick="return confirm('<?php echo $m_langpackage->m_ask_lock?>');"><img title="<?php echo $m_langpackage->m_lock?>" alt="<?php echo $m_langpackage->m_lock?>" src="images/lock.gif" /></a></span>
</span>
</td>
<?php
	$i++;
}
?>
</tr>
<tr><td colspan="6"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
<input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td></tr>
</table>
<?php page_show($isNull,$page_num,$page_total);?>
</form>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data?></div>
</div>
</div>
</div>
</div>
</body>
</html>
