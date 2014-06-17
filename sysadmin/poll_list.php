<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c10");
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
	$t_table=$tablePreStr."poll";

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//变量区
	$c_expiration=short_check(get_argg('expiration'));
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage')? intval(get_argg('perpage')) : 20;
	
	$eq_array=array('p_id','user_id','username','sex','noreply');
	$like_array=array('subject');
	$date_array=array("dateline");
	$num_array=array('comments','voternum','credit');
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,'','');
	
	if($c_expiration!=''){
		if($c_expiration==1){
			$sql.=" and expiration <= NOW() ";
		}
		if($c_expiration==2){
			$sql.=" and expiration > NOW() ";
		}
	}
	
	if(!empty($c_orderby)){
		$sql.=" order by $c_orderby ";
	}

	$sql.=" $c_ordersc ";	
	$dbo->setPages($c_perpage,$page_num);	
	$poll_rs=$dbo->getRs($sql);	
	$page_total=$dbo->totalPage;
	
	//评论限制
	$com_no_limit='';$com_whole='';$com_pals='';
	if(get_argg('noreply')==''){$com_no_limit='selected';}
	if(get_argg('noreply')=='0'){$com_whole='selected';}
	if(get_argg('noreply')=='1'){$com_pals='selected';}
	
	//投票限制
	$s_no_limit='';$s_woman='';$s_man='';
	if(get_argg('sex')=='2'){$s_no_limit='selected';}
	if(get_argg('sex')=='0'){$s_woman='selected';}
	if(get_argg('sex')=='1'){$s_man='selected';}
	
	//过期限制
	$date_no_limit='';$date_over='';$date_normal='';
	if(get_argg('expiration')==''){$date_no_limit='selected';}
	if(get_argg('expiration')=='1'){$date_over='selected';}
	if(get_argg('expiration')=='2'){$date_normal='selected';}

	//按字段排序
	$o_def='';$o_dateline='';$o_voternum='';$o_comments='';$o_credit='';
	if(!get_argg('order_by')||get_argg('order_by')=="p_id"){$o_def="selected";}
	if(get_argg('order_by')=="dateline"){$o_dateline="selected";}
	if(get_argg('order_by')=="voternum"){$o_voternum="selected";}
	if(get_argg('order_by')=="comments"){$o_comments="selected";}
	if(get_argg('order_by')=="credit"){$o_credit="selected";}		
	
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($poll_rs)){
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
function del_poll(poll_id,sendor_id)
{
	var del_poll=new Ajax();
	del_poll.getInfo("poll_del.action.php","GET","app","poll_id="+poll_id+"&sendor_id="+sendor_id,"operate_"+poll_id); 
}

function lock_poll_callback(pid,type_value){
	if(type_value==0){
	str="<font color='red'><?php echo $m_langpackage->m_lock;?></font>";document.getElementById("unlock_button_"+pid).style.display="";document.getElementById("lock_button_"+pid).style.display="none";
	}else{
	str="<?php echo $m_langpackage->m_normal;?>";document.getElementById("unlock_button_"+pid).style.display="none";document.getElementById("lock_button_"+pid).style.display="";
	}
	window.document.getElementById("state_"+pid).innerHTML=str;
}

function lock_poll(pid,type_value)
{
	var lock_poll=new Ajax();
	lock_poll.getInfo("poll_lock.action.php","GET","app","pid="+pid+"&type_value="+type_value,function(c){lock_poll_callback(pid,type_value);}); 
}	

function check_form(){
	var min_date=document.getElementById("dateline1").value;
	var max_date=document.getElementById("dateline2").value;
	var time_format=/\d{4}\-\d{2}\-\d{2}/;
	
	if(min_date){
		if(!time_format.test(min_date)){
			alert("<?php echo $m_langpackage->m_date_wrong;?>");
			return false;
			}
	}
	
	if(max_date){
		if(!time_format.test(max_date)){
			alert("<?php echo $m_langpackage->m_date_wrong;?>");
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="poll_list.php?order_by=p_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_poll;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET" onsubmit='return check_form();'>
<table class="form-table">
<tbody><tr><th width="100px"><?php echo $m_langpackage->m_author_id;?></th><td><input class="small-text" name="uid" type="text" value=<?php echo get_argg('uid');?>></td>
<th><?php echo $m_langpackage->m_author_name;?></th><td><input class="small-text" name="username" type="text" value=<?php echo get_argg('username');?>></td>
</tr>
<tr><th><?php echo $m_langpackage->m_title;?></th><td><input class="small-text" name="subject" type="text" value=<?php echo get_argg('subject');?>>&nbsp;<font color='red'>*</font></td>
<th><?php echo $m_langpackage->m_poll_id;?></th><td><input name="pid" class="small-text" type="text" value='<?php echo get_argg('pid');?>'></td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_comments_limit;?></th><td>
<select name="noreply">
<option value="" <?php echo $com_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
<option value="0" <?php echo $com_whole;?>><?php echo $m_langpackage->m_public;?></option>
<option value="1" <?php echo $com_pals;?>><?php echo $m_langpackage->m_only;?></option>
</select>
</td>
<th><?php echo $m_langpackage->m_poll_limit;?></th><td>
<select name="sex">
<option value="" <?php echo $s_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
<option value="1" <?php echo $s_man;?>><?php echo $m_langpackage->m_man;?></option>
<option value="0" <?php echo $s_woman;?>><?php echo $m_langpackage->m_woman;?></option>
</select>
</td>
</tr>
<tr>
<th><?php echo $m_langpackage->m_over;?></th>
<td>
<select name="expiration">
<option value="" <?php echo $date_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
<option value="1" <?php echo $date_over;?>><?php echo $m_langpackage->m_over_date;?></option>
<option value="2" <?php echo $date_normal;?>><?php echo $m_langpackage->m_no_over;?></option>
</select>
</td>
<th><?php echo $m_langpackage->m_send_date;?></th><td>
<input name="dateline1" AUTOCOMPLETE=off onclick='calendar(this);' id="dateline1" class="small-text" type="text" value='<?php echo get_argg('dateline1');?>'> ~
<input name="dateline2" AUTOCOMPLETE=off onclick='calendar(this);' id="dateline2" class="small-text" type="text" value='<?php echo get_argg('dateline2');?>'> (YYYY-MM-DD)
</td>
</tr>
<tr><th><?php echo $m_langpackage->m_award_inter;?></th><td colspan="3">
<input name="credit1" class="small-text" type="text" value='<?php echo get_argg('credit1');?>'> ~
<input name="credit2" class="small-text" type="text" value='<?php echo get_argg('credit2');?>'>
</td></tr>
<tr><th><?php echo $m_langpackage->m_poll_num;?></th><td colspan="3">
<input name="voternum1" class="small-text" type="text" value='<?php echo get_argg('voternum1');?>'> ~
<input name="voternum2" class="small-text" type="text" value='<?php echo get_argg('voternum2');?>'>
</td></tr>
<tr><th><?php echo $m_langpackage->m_comments;?></th><td colspan="3">
<input name="comments1" class="small-text" type="text" value='<?php echo get_argg('comments1');?>'> ~
<input name="comments2" class="small-text" type="text" value='<?php echo get_argg('comments2');?>'>
</td></tr>

<tr><th><?php echo $m_langpackage->m_result_order;?></th>
<td colspan="3">
<select name="order_by">
	<option value="p_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></option>
	<option value="dateline" <?php echo $o_dateline;?>><?php echo $m_langpackage->m_send_date;?></option>
	<option value="voternum" <?php echo $o_voternum;?>><?php echo $m_langpackage->m_poll_num;?></option>
	<option value="comments" <?php echo $o_comments;?>><?php echo $m_langpackage->m_comments;?></option>
	<option value="credit" <?php echo $o_credit;?>><?php echo $m_langpackage->m_award_inter;?></option>
</select>
<?php echo order_sc();?>
<?php echo perpage();?>
</td>
</tr>
<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
<tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
</tbody></table>
</form>
	</div>
</div>

<form method="post" action="poll_del.action.php" id="data_list">
<div class="infobox">
    <h3><?php echo $m_langpackage->m_poll_list;?></h3>
    <div class="content">
	<table class='list_table <?php echo $isset_data;?>'>
		<thead><tr>
        <th width="20px">&nbsp;</th><th><?php echo $m_langpackage->m_title;?></th><th><?php echo $m_langpackage->m_sendor;?></th><th style="text-align:center"><?php echo $m_langpackage->m_send_date;?></th><th style="text-align:center"><?php echo $m_langpackage->m_poll_num;?></th><th><?php echo $m_langpackage->m_comments;?></th><th><?php echo $m_langpackage->m_state;?></th><th style="text-align:center"><?php echo $m_langpackage->m_ctrl;?></th>
        </tr></thead>
	<?php foreach($poll_rs as $rs){?>
		<tr>
        	<td width="20px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['p_id'];?>" /></td>
			<td>
			<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=poll&p_id=<?php echo $rs['p_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'><?php echo $rs['subject'];?>
            </a>
			</td>
			<td><?php echo $rs['username'];?></td>
			<td style="text-align:center"><?php echo $rs['dateline'];?></td>
			<td style="text-align:center"><?php echo $rs['voternum'];?></td>
			<td><?php echo $rs['comments'];?></td>
			<td><span id="state_<?php echo $rs['p_id'];?>"><?php if($rs['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></span></td>
			<td align="center">
				<div id="operate_<?php echo $rs['p_id'];?>">
				<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=poll&p_id=<?php echo $rs['p_id'];?>&user_id=<?php echo $rs['user_id'];?>' target='_blank'>
					<image src="images/more.gif" title="<?php echo $m_langpackage->m_more;?>" alt="<?php echo $m_langpackage->m_more;?>" />
				</a>
				<a href='javascript:del_poll(<?php echo $rs["p_id"];?>,<?php echo $rs["user_id"];?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del;?>");' title='<?php echo $m_langpackage->m_del;?>'><img src='images/del.gif' /></a>
				
				<?php $unlock="display:none";$lock=""; if($rs['is_pass']==0){$unlock="";$lock="display:none";}?>
				
				<span id="unlock_button_<?php echo $rs['p_id'];?>" style="<?php echo $unlock;?>"><a href="javascript:lock_poll(<?php echo $rs['p_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock;?>" src="images/unlock.gif" /></a></span>
				
				<span id="lock_button_<?php echo $rs['p_id'];?>" style="<?php echo $lock;?>"><a href="javascript:lock_poll(<?php echo $rs['p_id'];?>,0);" onClick="return confirm('<?php echo $m_langpackage->m_ask_lock;?>?');"><img title="<?php echo $m_langpackage->m_lock;?>" src="images/lock.gif" /></a></span>				
				</div>
			</td>
		</tr>
	<?php
		}
	?>
    <tr>
    <td colspan="8"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
    <input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td>
  </tr>
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