<?php 
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c37");
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
	$t_table=$tablePreStr."mood";

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
	
	$eq_array=array('user_id','user_name');
	$like_array=array('mood');
	$date_array=array("add_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);
	$dbo->setPages($c_perpage,$page_num);//设置分页
	$mood_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数
		
	//按字段排序
	$o_def='';$o_add_time='';$o_comments='';
	if(!get_argg('order_by')||get_argg('order_by')=="mood_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}
	if(get_argg('order_by')=="comments"){$o_comments="selected";}
		
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($mood_rs)){
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
function del_mood(mood_id,uid)
{
	var del_mood=new Ajax();
	del_mood.getInfo("mood_del.action.php","GET","app","mood="+mood_id+"&uid="+uid,"operate_"+mood_id); 
}
function check_form(){
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_index;?></a> &gt;&gt; <a href="mood_list.php?order_by=mood_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_mood;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form method="get" action=""  onsubmit="return check_form()">
<TABLE class="form-table">
<tr>
<th width="80px"><?php echo $m_langpackage->m_author_id?></th><td width=30%><input type="text" class="small-text" name="user_id" value="<?php echo get_argg('user_id');?>"></td>
<th><?php echo $m_langpackage->m_author_name?></th><td><input type="text" class="small-text" name="user_name" value="<?php echo get_argg('user_name');?>"></td>
</tr>
<tr>
<th width="80px"><?php echo $m_langpackage->m_content?></th><td><input type="text" class="small-text" name="mood" AUTOCOMPLETE=off value="<?php echo get_argg('mood');?>">&nbsp;<font color=red>*</font></td>
<th><?php echo $m_langpackage->m_issue_time?></th><td>
<input type="text" AUTOCOMPLETE=off onclick='calendar(this);' class="small-text" name="add_time1" value="<?php echo get_argg('add_time1');?>" /> ~
<input type="text" AUTOCOMPLETE=off onclick='calendar(this);' class="small-text" name="add_time2" value="<?php echo get_argg('add_time2');?>" /> (YYYY-MM-DD)
</td>
</tr>
<tr><th width="80px"><?php echo $m_langpackage->m_result_order?></th>
<td colspan="3">
<select name="order_by">
	<OPTION value="mood_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
	<OPTION value='add_time' <?php echo $o_add_time;?>><?php echo $m_langpackage->m_mess_time?></OPTION>
	<OPTION value='comments' <?php echo $o_comments;?>><?php echo $m_langpackage->m_comments?></OPTION>
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
    <h3><?php echo $m_langpackage->m_mood_list;?></h3>
    <div class="content">
<form method="post" action="mood_del.action.php" id="data_list">
<table class='list_table <?php echo $isset_data;?>'>
<?php 
foreach($mood_rs as $val){
?>
<tr>
<td width="20px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $val['mood_id'];?>" /></td>
<td width="*"  style='text-align:left'>
<?php echo $m_langpackage->m_content?>： <?php echo get_face(sub_str($val['mood'],100));?> <br />
<?php echo $m_langpackage->m_author_id?>: uid-<?php echo $val['user_id'];?>&nbsp;
<?php echo $m_langpackage->m_comments?>: <?php echo $val['comments'];?>&nbsp;
<?php echo $m_langpackage->m_author_name?>: <a href="../home.php?h=<?php echo $val['user_id'];?>" target="_blank"><?php echo $val['user_name'];?></a> &nbsp
<?php echo $m_langpackage->m_issue_time?>: <?php echo $val['add_time'];?>&nbsp;
</td>
<td width="20px" style='text-align:left'>
<div id="operate_<?php echo $val['mood_id'];?>">
<a href='javascript:del_mood(<?php echo $val['mood_id'];?>,<?php echo $val['user_id'];?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");'><img src='images/del.gif' /></a>
</div>
</td>
</tr>
<?php 
}
?>
<tr>
    <td colspan="3"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
    <input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td>
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
