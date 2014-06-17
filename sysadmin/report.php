<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c35");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	require("../foundation/fback_search.php");
	$rf_langpackage=new recaffairlp;
	$ad_langpackage=new adminmenulp;
	//数据表定义区
	$t_table=$tablePreStr."report";

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage')? intval(get_argg('perpage')) : 20;
	
	$eq_array=array('type');
	$like_array=array();
	$date_array=array();
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);	

	$dbo->setPages($c_perpage,$page_num);//设置分页
	$reqort_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//条件显示
	$l_gro='';$l_blg='';$l_pic='';$l_alb='';$l_poll='';$l_sub='';$l_sha='';$l_spc='';
	if(get_argg('type')=="1"){$l_gro="selected";}
	if(get_argg('type')=="0"){$l_blg="selected";}
	if(get_argg('type')=="3"){$l_pic="selected";}
	if(get_argg('type')=="2"){$l_alb="selected";}
	if(get_argg('type')=="4"){$l_poll="selected";}
	if(get_argg('type')=="9"){$l_sub="selected";}
	if(get_argg('type')=="8"){$l_sha="selected";}
	if(get_argg('type')=="10"){$l_spc="selected";}

	//按字段排序
	$o_def='';$o_add_time='';
	if(!get_argg('order_by')||get_argg('order_by')=="reportid"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($reqort_rs)){
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
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<title></title>
<script type='text/javascript'>
function act_report(report_id,type_id,redid,uedid)
{
	var d = new Date();
	var t = d.getTime();
	var act_report=new Ajax();
	act_report.getInfo("report_del.action.php","GET","app","rid="+report_id+"&type="+type_id+"&redid="+redid+"&uedid="+uedid+"&t="+t,"operate_"+report_id); 
}

function del_report(report_id){
	var del_report=new Ajax();
	del_report.getInfo("report_del.action.php","GET","app","rid="+report_id,"operate_"+report_id); 
}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_index;?></a> &gt;&gt; <a href="report.php?order_by=report_id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_report;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form method="get" action="">
<TABLE class="form-table">
<tr>
<th width="50px"><?php echo $m_langpackage->m_report_type?></th>
<td>
<SELECT name='type'>
<OPTION value=""><?php echo $m_langpackage->m_astrict_no?></OPTION>
<option value="0" <?php echo $l_blg;?>><?php echo $m_langpackage->m_blog?></option>
<option value="9" <?php echo $l_sub;?>><?php echo $m_langpackage->m_subject?></option>
<option value="2" <?php echo $l_alb;?>><?php echo $m_langpackage->m_album?></option>
<option value="3" <?php echo $l_pic;?>><?php echo $m_langpackage->m_photo?></option>
<OPTION value="1" <?php echo $l_gro;?>><?php echo $m_langpackage->m_group?></OPTION>
<option value="4" <?php echo $l_poll;?>><?php echo $m_langpackage->m_poll?></option>
<option value="8" <?php echo $l_sha;?>><?php echo $m_langpackage->m_share?></option>
<OPTION value="10" <?php echo $l_spc;?>><?php echo $m_langpackage->m_space?></OPTION>
</SELECT>
</td>
</tr>
<tr>
  <th><?php echo $m_langpackage->m_result_order?></th>
  <td colspan="3">
  <select name="order_by">
    <OPTION value="report_id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order?></OPTION>
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
    <h3><?php echo $m_langpackage->m_report_list;?></h3>
    <div class="content">
<form method="post" action="">
<table class='list_table <?php echo $isset_data;?>'>
    <tr>
      <td width='25%'><?php echo $m_langpackage->m_content?></td>
      <td width='*'><?php echo $m_langpackage->m_report_reason?></td>
      <td><?php echo $m_langpackage->m_rep_num?></td>
      <td><?php echo $m_langpackage->m_informer?></td>
      <td><?php echo $m_langpackage->m_time?></td>
      <td><?php echo $m_langpackage->m_type?></td>
      <td width='15%'><?php echo $m_langpackage->m_handle?></td>
    </tr>
    <?php foreach($reqort_rs as $val){?>
    <tr>
      <td style='text-align:left;'><?php echo $val['content']?></td>
      <td><?php echo $val['reason']?></td>
      <td><?php echo $val['rep_num']?></td>
      <td><a href="../home.php?h=<?php echo $val['user_id'];?>" target="_blank"><?php echo $val['user_name']?></a></td>
      <td><?php echo $val['add_time']?></td>
      <td><?php echo $rf_langpackage->{'rf_s_type_'.$val['type']}?></td>
      <td>
      <div id="operate_<?php echo $val['report_id'];?>">
	      <a onclick="return confirm('<?php echo $m_langpackage->m_handle_con?>');" href="javascript:del_report(<?php echo $val['report_id']?>);"><?php echo $m_langpackage->m_del_report?></a><br />
	      <a onclick="return confirm('<?php echo $m_langpackage->m_handle_con?>');" href="javascript:act_report(<?php echo $val['report_id']?>,<?php echo $val['type']?>,<?php echo $val['reported_id']?>,<?php echo $val['user_id']?>);"><?php $lock=""; if($val['type']=='10' or $val['type']=='1'){$lock=$m_langpackage->m_lock;}?> <?php echo str_replace('{type}',$lock.$rf_langpackage->{'rf_s_type_'.$val['type']},$m_langpackage->m_lock_type)?></a></td>
    	</div>
    </tr>
    <?php }?>
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
