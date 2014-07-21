<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c18");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}

	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");
	
	//表定义区
	$t_subject=$tablePreStr."group_subject";
	$t_group=$tablePreStr."groups";
	$t_table=$t_subject.",".$t_group;
	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));	
	
	//变量区	
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
		
	$eq_array=array('user_id','group_id','subject_id');
	$like_array=array("title","user_name");
	$date_array=array("add_time");
	$num_array=array("comments","hits");
	$join_cond="group_id";
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc,$join_cond);
	
	//设置分页
	$dbo->setPages($c_perpage,$page_num);
	
	//取得数据
	$subject_rs=$dbo->getRs($sql);
	
	//分页总数
	$page_total=$dbo->totalPage;
	
	//按字段排序
	$o_def='';$o_s_date='';$o_hits='';$o_comments='';
	if(!get_argg('order_by')||get_argg('order_by')=="subject_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_s_date="selected";}
	if(get_argg('order_by')=="hits"){$o_hits="selected";}
	if(get_argg('order_by')=="comments"){$o_comments="selected";}
		
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($subject_rs)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
	
function check_form(){
	var min_send_date=document.getElementById("add_time1").value;
	var max_send_date=document.getElementById("add_time2").value;
	var time_format=/\d{4}\-\d{2}\-\d{2}/;
	if(min_send_date){
		if(!time_format.test(min_send_date)){
			alert("<?php echo $m_langpackage->m_date_wrong?>");
			return false;
		}
	}
	
	if(max_send_date){
		if(!time_format.test(max_send_date)){
			alert("<?php echo $m_langpackage->m_date_wrong?>");
			return false;
		}
	}
}

function del_subject(subject_id,group_id,sendor_id)
{
	var del_subject=new Ajax();
	del_subject.getInfo("subject_del.action.php","GET","app","subject_id="+subject_id+"&group_id="+group_id+"&sendor_id="+sendor_id,"operate_"+subject_id); 
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="subject_list.php?order_by=subject_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_sub;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET" onsubmit='return check_form()'>
<TABLE class="form-table">
  <TBODY>
  <TR>
    <th width="80px"><?php echo $m_langpackage->m_group_id?></th>
    <TD><INPUT type='text' class="small-text" name='group_id' value='<?php echo get_argg('group_id');?>'></TD>
    <th><?php echo $m_langpackage->m_title?><font color=red>*</font></th>
    <TD><INPUT type='text' class="small-text" name='title' value='<?php echo get_argg('title');?>'></TD></TR>
  <TR>
    <th><?php echo $m_langpackage->m_author_id?></th>
    <TD><INPUT type='text' class="small-text" name='user_id' value='<?php echo get_argg('user_id');?>'></TD>
    <th><?php echo $m_langpackage->m_author_name?></th>
    <TD><INPUT type='text' class="small-text" name='user_name' value='<?php echo get_argg('user_name');?>'></TD></TR>
  <TR>
    <th><?php echo $m_langpackage->m_sub_id?></th>
    <TD><INPUT type='text' class="small-text" name='subject_id' value='<?php echo get_argg('subject_id');?>'> </TD>
    <th><?php echo $m_langpackage->m_issue_time?></th>
    <TD>
    	<INPUT type='text' class="small-text" name='add_time1' AUTOCOMPLETE=off onclick='calendar(this);' id='add_time1' value='<?php echo get_argg('add_time1');?>'> ~ <INPUT type='text' class="small-text" name='add_time2' id='add_time2' AUTOCOMPLETE=off onclick='calendar(this);' value='<?php echo get_argg('add_time2');?>'> (YYYY-MM-DD) </TD>    
  </TR>

  <TR>
    <th><?php echo $m_langpackage->m_see_num?></th>
    <TD colSpan=3>
    	<INPUT type='text' class="small-text" name='hits1' value='<?php echo get_argg('hits1');?>'> ~ <INPUT type='text' class="small-text" name='hits2' value='<?php echo get_argg('hits2');?>'> </TD></TR>
  <TR>
    <th><?php echo $m_langpackage->m_reply_num?></th>
    <TD colSpan=3>
    	<INPUT type='text' class="small-text" name='comments1' value='<?php echo get_argg('comments1');?>'> ~ <INPUT type='text' class="small-text" name='comments2' value='<?php echo get_argg('comments2');?>'> </TD></TR>
  <TR>
<tr><th><?php echo $m_langpackage->m_result_order?></th>
<td colspan="3">
<select name="order_by">
	<OPTION value="subject_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
    <OPTION value="add_time" <?php echo $o_s_date;?>><?php echo $m_langpackage->m_issue_time?></OPTION> 
    <OPTION value="hits" <?php echo $o_hits;?>><?php echo $m_langpackage->m_see_num?></OPTION> 
    <OPTION value="comments" <?php echo $o_comments;?>><?php echo $m_langpackage->m_reply_num?></OPTION>
</SELECT> 
      <?php echo order_sc();?>
      <?php echo perpage();?>
	</TD>
	</TR>
<tr><td colspan=2><?php echo $m_langpackage->m_red?></td></tr>
<tr><td colspan=2><INPUT name="searchsubmit" class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
	</TBODY>
	</TABLE>
</form>
	</div>
</div>

<form method="post" action="subject_del.action.php" id='data_list'>
<div class="infobox">
    <h3><?php echo $m_langpackage->m_sub_list;?></h3>
    <div class="content">
<table class='list_table <?php echo $isset_data;?>'>
	<thead><tr>
    	<th width="20px">&nbsp;</th>
		<th> <?php echo $m_langpackage->m_title?> </th><th width="130" style="text-align:center"> <?php echo $m_langpackage->m_author?> </th>
		<th width="50" style="text-align:center"> <?php echo $m_langpackage->m_reply?> </th><th width="50" style="text-align:center"> <?php echo $m_langpackage->m_see?> </th>
		<th  width="300" style="text-align:center"> <?php echo $m_langpackage->m_issue_time?> </th><th width="50" style="text-align:center"> <?php echo $m_langpackage->m_handle?> </th>
        
	</tr></thead>
	<?php 
	foreach($subject_rs as $rs){
		?>
	<tr>
    	<td width="5px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['subject_id'];?>" /></td>
		<td style='text-align:left'>
        [ 
			<a href='../home.php?h=<?php echo $rs['add_userid'];?>&app=group_space&group_id=<?php echo $rs['group_id'];?>&user_id=<?php echo $rs['add_userid'];?>' target='_blank'>
				<?php echo $rs['group_name'];?> 
			</a>
			] &nbsp
			<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=group_sub_show&group_id=<?php echo $rs['group_id'];?>&subject_id=<?php echo $rs['subject_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'> 
				<?php echo $rs['title'];?>
			</a>
		</td>
		<td style="text-align:center"><?php echo $rs['user_name'];?></td>
		<td style="text-align:center"><?php echo $rs['comments'];?></td>
		<td style="text-align:center"><?php echo $rs['hits'];?></td>
		<td style="text-align:center"><?php echo $rs['add_time'];?></td>
		<td style="text-align:center">
			<div id="operate_<?php echo $rs['subject_id'];?>">
				<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=group_sub_show&group_id=<?php echo $rs['group_id'];?>&subject_id=<?php echo $rs['subject_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'> 
					<image src="images/more.gif" title="<?php echo $m_langpackage->m_see_particular?>" alt="<?php echo $m_langpackage->m_see_particular?>" />
				</a>
				<a href='javascript:del_subject(<?php echo $rs['subject_id'];?>,<?php echo $rs['group_id'];?>,<?php echo $rs['user_id'];?>)' onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");' title='<?php echo $m_langpackage->m_del?>'><img src='images/del.gif' /></a>
			</div>
		</td>
	</tr>
	<?php 
		}
	?>	
    <tr>
    	<td colspan="7">
    		<input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
    		<input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" />
    	</td>
    </tr>
</table><br />
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data?></div>
</div>
</div>
</div>
</div>
</form>
</body>
</html>