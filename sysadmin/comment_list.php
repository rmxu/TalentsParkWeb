<?php
	require("session_check.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	require("../foundation/fback_search.php");
	$is_check=check_rights("c28");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	$com_type_select=array(
	"blog_comment" => $m_langpackage->m_blog,
	"group_subject_comment" => $m_langpackage->m_subject,
	"album_comment" => $m_langpackage->m_album,
	"photo_comment" => $m_langpackage->m_photo,
	"poll_comment" => $m_langpackage->m_poll,
	"share_comment" => $m_langpackage->m_share,
	"mood_comment" => $m_langpackage->m_mood,
	);

	$com_type=array(
	"photo_comment"=>"photo_id",
	"mood_comment"=>"mood_id",
	"share_comment"=>"s_id",
	"poll_comment"=>"p_id",
	"group_subject_comment"=>"subject_id",
	"album_comment"=>"album_id",
	"blog_comment"=>"log_id",
	);

	$idtype = get_argg('idtype');
	$com_table_str = $idtype ? $idtype : "blog_comment";

	$t_table=$tablePreStr.$com_table_str;
	$con_id=$com_type[$com_table_str];

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

	$eq_array=array('host_id','visitor_id','visitor_name',$con_id);
	$like_array=array('content');
	$date_array=array("add_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);
	$dbo->setPages($c_perpage,$page_num);//设置分页
	$com_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//按字段排序
	$o_def='';$o_add_time='';
	if(get_argg('order_by')==''||get_argg('order_by')=="com_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($com_rs)){
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
function del_com(comment_id,idtype,v_id,main_id)
{
	var del_com=new Ajax();
	del_com.getInfo("com_del.action.php","GET","app","cid="+comment_id+"&type="+idtype+"&v_id="+v_id+"&main_id="+main_id,"operate_"+comment_id);
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
</script>
<body>
<div id="maincontent">
	<div class="wrap">
  	<div class="crumbs">
  		<?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_index;?></a> &gt;&gt; <a href="comment_list.php?order_by=comment_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_com;?></a>
  	</div>
    <hr />
    <div class="infobox">
    	<h3><?php echo $m_langpackage->m_check_condition;?></h3>
      <div class="content">
				<form method="get" action="" onsubmit="return check_form()">
					<TABLE class="form-table">
						<tr>
							<th width="80px"><?php echo $m_langpackage->m_com_type?></th>
							<td width=25%>
							<select name="idtype">
							<?php foreach($com_type_select as $key => $rs){
								$selected=($com_table_str==$key) ? "selected":"";?>
								<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $rs;?></option>
							<?php }?>
							</select>
							</td>
							<th><span style="text-align:left"><?php echo $m_langpackage->m_com_by_id?></span></th>
							<td width=*><input type="text" class="small-text" name="<?php echo $con_id;?>" value="<?php echo get_argg("$con_id");?>"></td>
						</tr>
						<tr>
							<th><?php echo $m_langpackage->m_com_uid;?></th><td><input type="text" class="small-text" name="visitor_id" value="<?php echo get_argg('visitor_id');?>"></td>
							<th><?php echo $m_langpackage->m_com_name;?></th><td><input type="text" class="small-text" name="visitor_name" value="<?php echo get_argg('visitor_name');?>"></td>
						</tr>
						<tr>
							<th><?php echo $m_langpackage->m_com_by_uid;?></th>
							<td><input type="text" class="small-text" name="host_id" value="<?php echo get_argg('host_id');?>"></td>
							<th><?php echo $m_langpackage->m_issue_time;?></th>
							<td>
								<input type="text" name="add_time1" class="small-text" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time1');?>" /> ~
								<input type="text" name="add_time2" class="small-text" AUTOCOMPLETE=off onclick='calendar(this);' value="<?php echo get_argg('add_time2');?>" /> (YYYY-MM-DD)
							</td>
						</tr>
						<tr>
							<th><?php echo $m_langpackage->m_content?></th>
							<td><input type="text" class="small-text" name="content" value="<?php echo get_argg('content');?>">&nbsp;<font color=red>*</font></td>
						</tr>
						<tr>
							<th><?php echo $m_langpackage->m_result_order?></th>
							<td colspan="3">
							<select name="order_by">
								<OPTION value="comment_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
								<OPTION value='add_time' <?php echo $o_add_time;?>><?php echo $m_langpackage->m_com_time?></OPTION>
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
		  <h3><?php echo $m_langpackage->m_com_list;?></h3>
		  <div class="content">
				<form method="post" action="">
					<table class='list_table <?php echo $isset_data;?>'>
					<?php foreach($com_rs as $val){?>
						<tr>
							<td width="90%" style='text-align:left'><?php echo $val['visitor_name'];?>： <?php echo get_face(sub_str($val['content'],80));?><br />
							<?php echo $m_langpackage->m_com_by_id?>: <?php echo $con_id;?>-<?php echo $val[$con_id];?>
							<?php echo $m_langpackage->m_com_by_uid?>: <a href="../home.php?h=<?php echo $val['host_id'];?>" target="_blank">user_id-<?php echo $val['host_id'];?></a>
							<?php echo $val['add_time'];?>
							</td>
							<td width="*" style='text-align:right'>
								<div id="operate_<?php echo $val['comment_id'];?>">
									<a href='javascript:del_com(<?php echo $val['comment_id'];?>,"<?php echo $com_table_str;?>",<?php echo $val['visitor_id'];?>,<?php echo $val[$con_id];?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del?>");'><img src='images/del.gif' /></a>
								</div>
							</td>
						</tr>
					<?php }?>
					</table>
					<?php page_show($isNull,$page_num,$page_total);?>
				</form>
				<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
			</div>
		</div>
	</div>
</body>
</html>
