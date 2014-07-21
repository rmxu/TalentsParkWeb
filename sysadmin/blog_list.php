<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;	
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");
	$is_check=check_rights("c15");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//数据表定义区
	$t_table=$tablePreStr."blog";
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
		
	$eq_array=array('log_id','user_id','user_name','blog_limit');
	$like_array=array('log_title','log_content');
	$date_array=array("add_time");
	$num_array=array("comments","hits");
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);
	
	//设置分页
	$dbo->setPages($c_perpage,$page_num);
	
	//取得数据
	$blog_rs=$dbo->getRs($sql);
	
	//分页总数
	$page_total=$dbo->totalPage;
		
	//访问权限
	$l_no_limit='';$l_public='';$l_pals='';$l_pri='';
	if(get_argg('limit')==''){$l_no_limit="selected";}
	if(get_argg('limit')=="0"){$l_public="selected";}
	if(get_argg('limit')=="1"){$l_pals="selected";}
	if(get_argg('limit')=="2"){$l_pri="selected";}
	
	//按字段排序
	$o_def='';$o_add_time='';$o_hits='';$o_comments='';
	if(get_argg('order_by')==''||get_argg('order_by')=="log_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}
	if(get_argg('order_by')=="hits"){$o_hits="selected";}
	if(get_argg('order_by')=="comments"){$o_comments="selected";}
		
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($blog_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
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
function del_blog(blog_id,sendor_id)
{
	var del_blog=new Ajax();
	del_blog.getInfo("blog_del.action.php","GET","app","blog_id="+blog_id+"&sendor_id="+sendor_id,"operate_"+blog_id); 
}
	
	function check_form()
	{
		var min_date_line=document.getElementById("add_time1").value;
		var max_date_line=document.getElementById("add_time2").value;
		var time_format=/\d{4}\-\d{2}\-\d{2}/;
		if(min_date_line){
			if(!time_format.test(min_date_line)){
				alert("<?php echo $m_langpackage->m_date_wrong;?>");
				return false;
				}
		}
		if(max_date_line){
			if(!time_format.test(max_date_line)){
				alert("<?php echo $m_langpackage->m_date_wrong;?>");
				return false;
				}
			}
	}
	
	function lock_blog_callback(blog_id,type_value){
		if(type_value==0){
		str="<font color='red'><?php echo $m_langpackage->m_lock;?></font>";document.getElementById("unlock_button_"+blog_id).style.display="";document.getElementById("lock_button_"+blog_id).style.display="none";
		}else{
		str="<?php echo $m_langpackage->m_normal;?>";document.getElementById("unlock_button_"+blog_id).style.display="none";document.getElementById("lock_button_"+blog_id).style.display="";
		}
		document.getElementById("state_"+blog_id).innerHTML=str;	
	}
	
	function lock_blog(blog_id,type_value)
{
	var lock_blog=new Ajax();
	lock_blog.getInfo("blog_lock.action.php","GET","app","blog_id="+blog_id+"&type_value="+type_value,function(c){lock_blog_callback(blog_id,type_value);}); 
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
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="blog_list.php?order_by=log_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_blog;?></a></div>
    <hr />
    <div class="infobox">
      <h3><?php echo $m_langpackage->m_check_condition;?></h3>
      <div class="content">
				<form action="" method="GET" onSubmit="return check_form()">
				<TABLE class="form-table">
				<TR>
				<th width=12%><?php echo $m_langpackage->m_author_id;?></th>
				<TD width=30%><INPUT name="user_id" class="small-text" type='text' value='<?php echo get_argg('user_id');?>' /></TD>
				<th width=12%><?php echo $m_langpackage->m_author_name;?></th>
				<TD width=*><INPUT name="user_name" class="small-text" type='text' value='<?php echo get_argg('user_name');?>' /></TD></TR>
				<TR>
				<th><?php echo $m_langpackage->m_title;?><font color=red>*</font></th>
				<TD><INPUT name="log_title" class="small-text" type='text' value='<?php echo get_argg('log_title');?>' /></TD>
				<th><?php echo $m_langpackage->m_content;?><font color='red'>*</font></th>
				<TD><INPUT name="log_content" class="small-text" type='text' value='<?php echo get_argg('log_content');?>' /></TD></TR>
				<TR>
				<th><?php echo $m_langpackage->m_public_pro;?></th>
				<TD>
				<SELECT name="blog_limit">
				<OPTION value="" <?php echo $l_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></OPTION>
				<OPTION value="0" <?php echo $l_public;?>><?php echo $m_langpackage->m_public;?></OPTION>
				<OPTION value="1" <?php echo $l_pals;?>><?php echo $m_langpackage->m_only;?></OPTION> 
				<OPTION value="2" <?php echo $l_pri;?>><?php echo $m_langpackage->m_only_self;?></OPTION> 
				</SELECT></TD>
				<th><?php echo $m_langpackage->m_send_date;?></th>
				<TD>
				<INPUT class="small-text" type='text' AUTOCOMPLETE=off onclick='calendar(this);' name='add_time1' id='add_time1' value='<?php echo get_argg('add_time1');?>' /> ~ <INPUT type='text' class="small-text" name='add_time2' AUTOCOMPLETE=off onclick='calendar(this);' id='add_time2' value='<?php echo get_argg('add_time2');?>' /> (YYYY-MM-DD) </TD>	
				</TR>
				<TR>
				<th><?php echo $m_langpackage->m_blog_id;?></th>
				<TD colSpan=3><INPUT name='log_id' class="small-text" type='text' value='<?php echo get_argg('log_id');?>' /> </TD></TR>
				<TR>
				<th><?php echo $m_langpackage->m_scanf_num;?></th>
				<TD colSpan=3>
				<INPUT class="small-text" name='hits1' type='text' value='<?php echo get_argg('hits1');?>'  /> ~ <INPUT class="small-text" type='text' name='hits2' value='<?php echo get_argg('hits2');?>' /> </TD></TR>
				<TR>
				<th><?php echo $m_langpackage->m_reply_num;?></th>
				<TD colSpan=3>
				<INPUT class="small-text" type='text' name='comments1' value='<?php echo get_argg('comments1');?>' /> ~ <INPUT class="small-text" type='text' name='comments2' value='<?php echo get_argg('comments2');?>' /> </TD></TR>
				
				<TR>
				<th><?php echo $m_langpackage->m_result_order;?></th>
				<TD colSpan=3>
				<SELECT name='order_by'> 
				<OPTION value="log_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></OPTION>
				<OPTION value='add_time' <?php echo $o_add_time;?>><?php echo $m_langpackage->m_send_date;?></OPTION> 
				<OPTION value='hits' <?php echo $o_hits;?>><?php echo $m_langpackage->m_scanf_num;?></OPTION> 
				<OPTION value='comments' <?php echo $o_comments;?>><?php echo $m_langpackage->m_reply_num;?></OPTION>
				</SELECT>
				<?php echo order_sc();?>
				<?php echo perpage();?>
				</TD>
				</TR>
				<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
				<tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
				</TABLE>
				</form>
			</div>
		</div>

<form method="post" action="blog_del.action.php" id='data_list'>
<div class="infobox">
	<h3><?php echo $m_langpackage->m_blog_list;?></h3>
	<div class="content">
		<table class='list_table <?php echo $isset_data;?>'>
			<thead><tr>
					<th width="20px">&nbsp;</th>
					<th width='35%'> <?php echo $m_langpackage->m_title;?> </th><th style="text-align:center"> <?php echo $m_langpackage->m_author_name;?> </th><th style="text-align:center"> <?php echo $m_langpackage->m_send_date;?> </th><th style="text-align:center"> <?php echo $m_langpackage->m_reply_num;?> </th><th style="text-align:center"> <?php echo $m_langpackage->m_scanf_num;?> </th><th style="text-align:center"> <?php echo $m_langpackage->m_state;?> </th><th width='100px' style="text-align:center"> <?php echo $m_langpackage->m_ctrl;?> </th>
        
			</tr></thead>
	<?php 
			foreach($blog_rs as $rs){
	?>
			<tr>
        <td><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['log_id'];?>" /></td>
				<td>
					<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=blog&id=<?php echo $rs['log_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'>
						<?php echo $rs['log_title'];?>
					</a>
				</td>
				<td style="text-align:center"><?php echo $rs['user_name'];?></td>
				<td style="text-align:center"><?php echo $rs['add_time'];?></td>
				<td style="text-align:center"><?php echo $rs['comments'];?></td>
				<td style="text-align:center"><?php echo $rs['hits'];?></td>
				<td style="text-align:center"><span id="state_<?php echo $rs['log_id'];?>"><?php if($rs['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";;?></span></td>
				<td style="text-align:center">
					<div id="operate_<?php echo $rs['log_id'];?>">
						<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=blog&id=<?php echo $rs['log_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'><image src="images/more.gif" title="<?php echo $m_langpackage->m_more;?>" alt="<?php echo $m_langpackage->m_more;?>" /></a>
						<a href='javascript:del_blog(<?php echo $rs["log_id"];?>,<?php echo $rs["user_id"];?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del;?>");' title='<?php echo $m_langpackage->m_del;?>'><img src='images/del.gif' /></a>
					<?php $unlock="display:none";$lock=""; if($rs['is_pass']==0){$unlock="";$lock="display:none";}?>
				
				<span id="unlock_button_<?php echo $rs['log_id'];?>" style="<?php echo $unlock;?>"><a href="javascript:lock_blog(<?php echo $rs['log_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock;?>" alt="<?php echo $m_langpackage->m_unlock;?>" src="images/unlock.gif" /></a></span>
				<span id="lock_button_<?php echo $rs['log_id'];?>" style="<?php echo $lock;?>"><a href="javascript:lock_blog(<?php echo $rs['log_id'];?>,0);" onClick="return confirm('<?php echo $m_langpackage->m_ask_lock;?>');"><img title="<?php echo $m_langpackage->m_lock;?>" alt="<?php echo $m_langpackage->m_lock;?>" src="images/lock.gif" /></a></span>
					
					</div>
				</td>
			</tr>
	<?php 
			}
			?>
      <tr><td colspan="8"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" /><input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td></tr>
		</table>
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</div>
</form>
</body>
</html>