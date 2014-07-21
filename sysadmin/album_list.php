<?php
	require("session_check.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");
	$is_check=check_rights("c22");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//数据表定义区
	$t_table=$tablePreStr."album";

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

	$eq_array=array('album_id','user_id','user_name');
	$like_array=array('album_name');
	$date_array=array("add_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

	$dbo->setPages($c_perpage,$page_num);//设置分页
	$album_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//按字段排序
	$o_def='';$o_add_time='';$o_hits='';
	if(!get_argg('order_by')||get_argg('order_by')=="album_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}
	if(get_argg('order_by')=="photo_num"){$o_hits="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($album_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
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
function del_album(album_id,user_id)
{
	var del_album=new Ajax();
	del_album.getInfo("album_del.action.php","GET","app","aid="+album_id+"&uid="+user_id,"operate_"+album_id);
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

function lock_member_callback(album_id,type_value){
	if(type_value==0){
		str="<font color='red'><?php echo $m_langpackage->m_lock?></font>";
		document.getElementById("unlock_button_"+album_id).style.display="";
		document.getElementById("lock_button_"+album_id).style.display="none";
	}else{
		str="<?php echo $m_langpackage->m_normal?>";
		document.getElementById("unlock_button_"+album_id).style.display="none";
		document.getElementById("lock_button_"+album_id).style.display="";
	}
	document.getElementById("state_"+album_id).innerHTML=str;
}

function lock_album(album_id,type_value)
{
	var lock_album=new Ajax();
	lock_album.getInfo("album_lock.action.php","GET","app","album_id="+album_id+"&type_value="+type_value,function(c){lock_member_callback(album_id,type_value);});
}
</script>
<script language="JavaScript" type="text/JavaScript"> 
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="album_list.php?order_by=album_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_album;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form method="get" action=""  onsubmit="return check_form()">
<TABLE class="form-table">
<tr>
<th width=12%><?php echo $m_langpackage->m_album_name?></th>
<td width=30%><input type="text" class="small-text" name="album_name" value="<?php echo get_argg('album_name');?>" />&nbsp;<font color=red>*</font></td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_album_id?></th>
<td>
<input type="text" class="small-text" name="album_id" value="<?php echo get_argg('album_id');?>" />
</td>
<th><?php echo $m_langpackage->m_creat_time?></th>
<td colspan="3">
<input type="text" class="small-text" name="add_time1" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time1');?>" /> ~
<input type="text" class="small-text" name="add_time2" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time2');?>" />
(YYYY-MM-DD)
</td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_author_id?></th>
<td><input type="text" class="small-text" name="user_id" value="<?php echo get_argg('user_id');?>" /></td>
<th><?php echo $m_langpackage->m_author_name?></th>
<td><input type="text" class="small-text" name="user_name" value="<?php echo get_argg('user_name');?>" /></td>
</tr>

<tr>
<tr><th><?php echo $m_langpackage->m_result_order?></th>
<td colspan="3">
<select name="order_by">
	<OPTION value="album_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
	<OPTION value='add_time' <?php echo $o_add_time;?>><?php echo $m_langpackage->m_creat_time?></OPTION>
  <OPTION value='photo_num' <?php echo $o_hits;?>><?php echo $m_langpackage->m_pho_num?></OPTION>
</select>
<?php echo order_sc();?>
<?php echo perpage();?>
</td>
</tr>
<tr><td colspan=2><?php echo $m_langpackage->m_red?></td></tr>
<tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
</table>
</form>
	</div>
</div>

<div class="infobox">
    <h3><?php echo $m_langpackage->m_alb_list;?></h3>
    <div class="content">
<form method="post" action="album_del.action.php" id='data_list'>
<table class='list_table <?php echo $isset_data;?>'>
<?php
$i=0;
foreach($album_rs as $val){
	if($i%2==0){
		if($i>0){ echo "</tr>"; }
		echo "<tr>";
	}
?>
<td width="10px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $val['album_id'];?>" /></td>
<td width="15%" align="center">
<a href="photo_list.php?albumid=<?php echo $val['album_id'];?>"><img src="../<?php echo $val['album_skin'];?>" width="100" ></a></td>
<td width="20%" style='text-align:left'>
 <?php echo $m_langpackage->m_album_name?>：<?php echo $val['album_name'];?>
<br/>
<?php echo $m_langpackage->m_author_name?>: <a href="../home.php?h=<?php echo $val['user_id'];?>" target="_blank"><?php echo $val['user_name'];?></a>
<br/>
<?php echo $m_langpackage->m_time?>: <?php echo $val['add_time'];?>
<br/>
<a href="comment_list.php?idtype=album_comment&album_id=<?php echo $val['album_id'];?>"><?php echo $m_langpackage->m_admin_com?></a>
<span id="operate_<?php echo $val['album_id'];?>">
<a href='javascript:del_album(<?php echo $val['album_id'];?>,<?php echo $val['user_id'];?>);' title="<?php echo $m_langpackage->m_del?>" alt="<?php echo $m_langpackage->m_del?>" onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");'><img src='images/del.gif' /></a>
<?php $unlock="display:none";$lock=""; if($val['is_pass']==0){$unlock="";$lock="display:none";}?>
<span id="unlock_button_<?php echo $val['album_id'];?>" style="<?php echo $unlock;?>"><a href="javascript:lock_album(<?php echo $val['album_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock?>" alt="<?php echo $m_langpackage->m_unlock?>" src="images/unlock.gif" /></a></span><span id="lock_button_<?php echo $val['album_id'];?>" style="<?php echo $lock;?>"><a href="javascript:lock_album(<?php echo $val['album_id'];?>,0);" onclick="return confirm('<?php echo $m_langpackage->m_ask_lock?>');"><img title="<?php echo $m_langpackage->m_lock?>" alt="<?php echo $m_langpackage->m_lock?>" src="images/lock.gif" /></a></span>
</span>
</td>
<td>
<span id="state_<?php echo $val['album_id'];?>"><?php if($val['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></span>
</td>
<?php
	$i++;
}
?>
</tr>
<tr>
	<td colspan="8">
		<input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
		<input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" />
	</td>
</tr>
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
