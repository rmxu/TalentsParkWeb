<?php
	require("session_check.php");
	require("../foundation/module_group.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");
	require("../api/base_support.php");
	$is_check=check_rights("c13");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}

	//数据表定义区
	$t_table=$tablePreStr."groups";
	$t_group_type=$tablePreStr."group_type";

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

	$eq_array=array('group_id','group_type_id','group_join_type','group_type');
	$like_array=array('group_name');
	$date_array=array();
	$num_array=array("comments","subjects_num","member_count");
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);
	//设置分页
	$dbo->setPages($c_perpage,$page_num);

	//取得数据
	$group_rs=$dbo->getRs($sql);

	//分页总数
	$page_total=$dbo->totalPage;

	//加入方式
	$j_no_limit='';$j_free='';$j_check='';$j_reju='';
	if(get_argg('join_type')==''){$j_no_limit="selected";}
	if(get_argg('join_type')=='0'){$j_free="selected";}
	if(get_argg('join_type')=='1'){$j_check="selected";}
	if(get_argg('join_type')=='2'){$j_reju="selected";}

	//按字段排序
	$o_def='';$o_members='';$o_subjects='';$o_comments='';
	if(!get_argg('order_by')||get_argg('order_by')=="group_id"){$o_def="selected";}
	if(get_argg('order_by')=="member_count"){$o_members="selected";}
	if(get_argg('order_by')=="subjects_num"){$o_subjects="selected";}
	if(get_argg('order_by')=="comments"){$o_comments="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($group_rs)){
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
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
</head>
<script type='text/javascript'>
function lock_group_callback(group_id,type_value){
	if(type_value==0){
		str="<font color='red'><?php echo $m_langpackage->m_lock;?></font>";document.getElementById("unlock_button_"+group_id).style.display="";document.getElementById("lock_button_"+group_id).style.display="none";
	}else{
		str="<?php echo $m_langpackage->m_normal;?>";document.getElementById("unlock_button_"+group_id).style.display="none";document.getElementById("lock_button_"+group_id).style.display="";
	}
	window.document.getElementById("state_"+group_id).innerHTML=str;
}
function lock_group(group_id,type_value)
{
	var lock_group=new Ajax();
	lock_group.getInfo("group_lock.action.php","GET","app","group_id="+group_id+"&type_value="+type_value,function(c){lock_group_callback(group_id,type_value);});
}
</script>
<script language="JavaScript" type="text/JavaScript"> 
function checkAll(){   
	var checkany=document.getElementsByName("checkany[]");
	for(var i=0;i<checkany.length;i++){   
		if(checkany(i).checked)   
		  checkany(i).checked=false;   
		else  
		 checkany(i).checked=true;   
	}   
}
</script>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="group_list.php?order_by=group_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_gro;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET">
	<table class="form-table">
		<tr>
			<th width=50px> <?php echo $m_langpackage->m_name;?></th>
			<td width=40%><input type=text class="small-text" name='group_name' value='<?php echo get_argg('group_name');?>' />&nbsp;<font color=red>*</font></td>
			<th width=12%> <?php echo $m_langpackage->m_group_id;?></th>
			<td width=*><input type=text class="small-text" name='group_id' value='<?php echo get_argg('group_id');?>' /></td>
		</tr>
		<tr>
			<th> <?php echo $m_langpackage->m_type;?></th>
			<td>
				<input type='hidden' name='group_type' id='group_type_name' />
				<?php echo group_sort_list(api_proxy("group_sort_by_self"),get_argg('group_type_id'));?>
			</td>
			<th> <?php echo $m_langpackage->m_join_type;?></th>
			<td><select name='join_type'>
						<option value="" <?php echo $j_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
						<option value="0" <?php echo $j_free;?>><?php echo $m_langpackage->m_free_join;?></option>
						<option value="1" <?php echo $j_check;?>><?php echo $m_langpackage->m_check_join;?></option>
						<option value="2" <?php echo $j_reju;?>><?php echo $m_langpackage->m_check_rejuse;?></option>
					</select>
			</td>
		</tr>
		<tr>
			<th> <?php echo $m_langpackage->m_member_num;?></th>
			<td>
				<input type='text' name='member_count1' class="small-text" value='<?php echo get_argg('member_count1');?>' />&nbsp;~&nbsp;<input type='text' name='member_count2' class="small-text" value='<?php echo get_argg('member_count2');?>' />
			</td>
		</tr>
		<tr>
			<th> <?php echo $m_langpackage->m_sub_num;?></th>
			<td>
				<input type='text' name='subjects_num1' class="small-text" value='<?php echo get_argg('subjects_num1');?>' />&nbsp;~&nbsp;<input type='text' name='subjects_num2' class="small-text" value='<?php echo get_argg('subjects_num2');?>' />
			</td>
		</tr>
		<tr>
			<th> <?php echo $m_langpackage->m_comments;?></th>
			<td>
				<input type='text' name='comments1' class="small-text" value='<?php echo get_argg('comments1');?>' />&nbsp;~&nbsp;<input type='text' name='comments2' class="small-text" value='<?php echo get_argg('comments2');?>' />
			</td>
		</tr>
		<tr>
			<th><?php echo $m_langpackage->m_result_order;?></th>
			<td colSpan=3>
				<select name='order_by'>
					<option value="group_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></option>
					<option value="member_count" <?php echo $o_members;?>><?php echo $m_langpackage->m_member_num;?></option>
					<option value="subjects_num" <?php echo $o_subjects;?>><?php echo $m_langpackage->m_sub_num;?></option>
					<option value="comments" <?php echo $o_comments;?>><?php echo $m_langpackage->m_comments;?></option>
				</select>
      <?php echo order_sc();?>
      <?php echo perpage();?>
			</td>
		</tr>
  	<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
    <tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
	</table>
</form>
	</div>
</div>

<div class="infobox">
    <h3><?php echo $m_langpackage->m_group_list;?></h3>
    <div class="content">
	<table class='list_table <?php echo $isset_data;?>'>
		<thead><tr><th width="110px"><?php echo $m_langpackage->m_name;?></th><th style="text-align:center"><?php echo $m_langpackage->m_type;?></th><th style="text-align:center"><?php echo $m_langpackage->m_greator;?></th><th style="text-align:center"><?php echo $m_langpackage->m_great_date;?></th><th style="text-align:center"><?php echo $m_langpackage->m_member_num;?></th><th style="text-align:center"><?php echo $m_langpackage->m_sub_num;?></th><th style="text-align:center"><?php echo $m_langpackage->m_comments;?></th><th style="text-align:center"><?php echo $m_langpackage->m_state;?></th><th style="text-align:center"><?php echo $m_langpackage->m_ctrl;?></th></tr></thead>
	<?php foreach($group_rs as $rs){?>
		<tr>
			<td width="110px">
				<a href='../home.php?h=<?php echo $rs['add_userid'];?>&app=group_space&group_id=<?php echo $rs['group_id'];?>&user_id=<?php echo $rs['add_userid'];?>' target='_blank'>
					<?php echo $rs['group_name'];?>
				</a>
			</td>
			<td style="text-align:center"><?php echo $rs['group_type'];?></td>
			<td style="text-align:center"><?php echo $rs['group_creat_name'];?></td>
			<td style="text-align:center"><?php echo $rs['group_time'];?></td>
			<td style="text-align:center"><?php echo $rs['member_count'];?></td>
			<td style="text-align:center"><?php echo $rs['subjects_num'];?></td>
			<td style="text-align:center"><?php echo $rs['comments'];?></td>
			<td style="text-align:center"><span id="state_<?php echo $rs['group_id'];?>"><?php if($rs['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></span></td>
			<td style="text-align:center">
				<a href='../home.php?h=<?php echo $rs['add_userid'];?>&app=group_space&group_id=<?php echo $rs['group_id'];?>&user_id=<?php echo $rs['add_userid'];?>' target='_blank'>
					<image src="images/more.gif" title="<?php echo $m_langpackage->m_more;?>" alt="<?php echo $m_langpackage->m_more;?>" />
				</a>

				<?php $unlock="display:none";$lock=""; if($rs['is_pass']==0){$unlock="";$lock="display:none";}?>

				<span id="unlock_button_<?php echo $rs['group_id'];?>" style="<?php echo $unlock;?>"><a href="javascript:lock_group(<?php echo $rs['group_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock;?>" src="images/unlock.gif" /></a></span>

				<span id="lock_button_<?php echo $rs['group_id'];?>" style="<?php echo $lock;?>"><a href="javascript:lock_group(<?php echo $rs['group_id'];?>,0);" onClick="return confirm('<?php echo $m_langpackage->m_ask_lock;?>');"><img title="<?php echo $m_langpackage->m_lock;?>" src="images/lock.gif" /></a></span>
			</td>
		</tr>
	<?php
		}
	?>
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