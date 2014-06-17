<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fcontent_format.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c32");
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
	$t_table=$tablePreStr."share";
	$rf_langpackage=new recaffairlp;

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage')? intval(get_argg('perpage')) : 20;

	$eq_array=array('s_id','user_id','user_name');
	$like_array=array();
	$date_array=array("add_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

	$dbo->setPages($c_perpage,$page_num);//设置分页
	$share_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//按字段排序
	$o_def='';$o_add_time='';
	if(!get_argg('order_by')||get_argg('order_by')=="shareid"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($share_rs)){
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
function del_share(s_id,u_id)
{
	var del_share=new Ajax();
	del_share.getInfo("share_del.action.php","GET","app","sid="+s_id+"&u_id="+u_id,"operate_"+s_id); 
}

function share_lock_callback(s_id,type_value){
	if(type_value==0){
		str="<font color='red'><?php echo $m_langpackage->m_lock?></font>";
		document.getElementById("unlock_button_"+s_id).style.display="";
		document.getElementById("lock_button_"+s_id).style.display="none";
	}else{
		str="<?php echo $m_langpackage->m_normal?>";
		document.getElementById("unlock_button_"+s_id).style.display="none";
		document.getElementById("lock_button_"+s_id).style.display="";
	}
	document.getElementById("state_"+s_id).innerHTML=str;
}

function share_lock(s_id,type_value)
{
	var share_lock=new Ajax();
	share_lock.getInfo("share_lock.action.php","GET","app","sid="+s_id+"&type_value="+type_value,function(c){share_lock_callback(s_id,type_value);}); 
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
function fixImage(i,w,h){
	return true;
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
		<div class="crumbs">
			<?php echo $ad_langpackage->ad_location;?>&gt;&gt;<a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="share_list.php?order_by=s_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_share;?></a>
    </div>
    <hr />
    <div class="infobox">
		  <h3><?php echo $m_langpackage->m_check_condition;?></h3>
		  <div class="content">
				<form method="get" action=""  onsubmit="return check_form()">
				<TABLE class="form-table">
				<tr>
				<th width=12%><?php echo $m_langpackage->m_author_id?></th><td width=30%><input type="text" class="small-text" name="user_id" value="<?php echo get_argg('user_id');?>"></td>
				<th><?php echo $m_langpackage->m_author_name?></th><td><input type="text" class="small-text" name="user_name" value="<?php echo get_argg('user_name');?>"></td>
				</tr>
				<tr>
				<th width=10%><?php echo $m_langpackage->m_share_id?></th><td width=*><input type="text" class="small-text" name="s_id" value="<?php echo get_argg('s_id');?>"></td>
				<th><?php echo $m_langpackage->m_issue_time?></th><td>
				<input type="text" name="add_time1" class="small-text" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time1');?>" /> ~
				<input type="text" name="add_time2" class="small-text" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time2');?>" /> (YYYY-MM-DD)
				</td>
				</tr>
				<tr><th><?php echo $m_langpackage->m_result_order?></th>
				<td colspan="3">
				<select name="order_by">
					<OPTION value="s_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
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
  <h3><?php echo $m_langpackage->m_share_list;?></h3>
  <div class="content">
<form method="post" action="share_del.action.php" id='data_list'>
  <table class='list_table <?php echo $isset_data;?>'>
    <tr>
    <thead>
      <th width="20px">&nbsp;</th>
      <th width="50%"><?php echo $m_langpackage->m_content?></th>
      <th style="text-align:center"><?php echo $m_langpackage->m_share_p?></th>
      <th width="200px"style="text-align:center"><?php echo $m_langpackage->m_time?></th>
      <th style="text-align:center"><?php echo $m_langpackage->m_share_type?></th>
      <th style="text-align:center"><?php echo $m_langpackage->m_state?></th>
      <th style="text-align:center"><?php echo $m_langpackage->m_handle?></th>
      </thead>
    </tr>
    <?php foreach($share_rs as $val){?>
    <tr>
    	<td width="5px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $val['s_id'];?>" /></td>
      <td style='text-align:left;'>
			<?php
			$order=array();
			$replace=array();
			$share_content='';
			if($val['type_id']!=5){
				$order = array("href=\"", "src=\"","src=\"../http://");
				$replace = array("href=\"../", "src=\"../","src=\"http://");
			}
			echo str_replace($order,$replace,$val['s_title']).'<br/>'.format_datetime_txt($val['add_time']);
		?>
	  	</td>
      <td style="text-align:center"><a href="../home.php?h=<?php echo $val['user_id'];?>" target="_blank"><?php echo $val['user_name']?></a></td>
      <td style="text-align:center"><?php echo $val['add_time']?></td>
      <td style="text-align:center"><?php echo $rf_langpackage->{'rf_s_type_'.$val['type_id']}?></td>
      <td style="text-align:center">
	   	 <span id="state_<?php echo $val['s_id'];?>"><?php if($val['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></span>
	  	</td>
      <td style="text-align:center">
				<div id="operate_<?php echo $val['s_id'];?>">
					<a href='javascript:del_share(<?php echo $val['s_id'];?>,<?php echo $val['user_id']?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");'>
						<img src='images/del.gif' />
					</a>
					<?php $unlock="display:none";$lock=""; if($val['is_pass']==0){$unlock="";$lock="display:none";}?>
					<span id="unlock_button_<?php echo $val['s_id'];?>" style="<?php echo $unlock;?>">
						<a href="javascript:share_lock(<?php echo $val['s_id'];?>,1);"><img title="<?php echo $m_langpackage->m_unlock?>" alt="<?php echo $m_langpackage->m_unlock?>" src="images/unlock.gif" /></a>
					</span>
					<span id="lock_button_<?php echo $val['s_id'];?>" style="<?php echo $lock;?>">
						<a href="javascript:share_lock(<?php echo $val['s_id'];?>,0);" onclick="return confirm('<?php echo $m_langpackage->m_ask_lock?>');">
							<img title="<?php echo $m_langpackage->m_lock?>" alt="<?php echo $m_langpackage->m_lock?>" src="images/lock.gif" />
						</a>
					</span>
				</div>
	  	</td>
		</tr>
    <?php }?>
    <tr><td colspan="7"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
    <input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td></tr>
  </table>
	<?php page_show($isNull,$page_num,$page_total);?>
</form>

<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data?></div>

</body>
</html>