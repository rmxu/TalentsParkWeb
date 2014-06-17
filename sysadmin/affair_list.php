<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fcontent_format.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;
	$rf_langpackage=new recaffairlp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");
	$is_check=check_rights("c20");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//数据表定义区
	$t_table=$tablePreStr."recent_affair";
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

	$eq_array=array('user_id','user_name','id');
	$like_array=array();
	$date_array=array("date_time");
	$num_array=array();
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

	//设置分页
	$dbo->setPages($c_perpage,$page_num);

	//取得数据
	$affair_rs=$dbo->getRs($sql);	
	
	//分页总数
	$page_total=$dbo->totalPage;
	
	//按字段排序
	$o_def='';$o_time='';
	if(!get_argg('order_by')||get_argg('order_by')=="id"){$o_def="selected";}
	if(get_argg('order_by')=="date_time"){$o_time="selected";}	
			
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($affair_rs)){
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
function fixImage(i,w,h){
	return true;
}
function del_affair(a_id)
{
	var del_affair=new Ajax();
	del_affair.getInfo("affair_del.action.php","GET","app","aff_id="+a_id,"operate_"+a_id); 
}

function check_form()
	{
		var min_send_date=document.getElementById("date_time1").value;
		var max_send_date=document.getElementById("date_time2").value;
		var time_format=/\d{4}\-\d{2}\-\d{2}/;
		if(min_send_date){
			if(!time_format.test(min_send_date)){
				alert("<?php echo $m_langpackage->m_date_wrong;?>");
				return false;
				}
		}
		if(max_send_date){
			if(!time_format.test(max_send_date)){
				alert("<?php echo $m_langpackage->m_date_wrong;?>");
				return false;
			}
		}
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
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_index;?></a> &gt;&gt; <a href="affair_list.php?order_by=id&order_sc=desc"><?php echo $ad_langpackage->ad_manage_feed;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET" onSubmit="return check_form();">
	<TABLE class="form-table">
  <TBODY>
  <TR>
    <TH width="50px"><?php echo $m_langpackage->m_userid;?></TH>
    <TD><INPUT type='text' class="small-text" name=user_id value="<?php echo get_argg('user_id');?>"></TD>
    <TH><?php echo $m_langpackage->m_uname;?></TH>
    <TD><INPUT type='text' class="small-text" name=user_name value="<?php echo get_argg('user_name');?>"></TD></TR>
  <TR>
    <TH width="50px"><?php echo $m_langpackage->m_feed_id;?></TH>
    <TD><INPUT type='text' class="small-text" name=id value="<?php echo get_argg('id');?>"></TD>
    <TH><?php echo $m_langpackage->m_issue_time;?></TH>
    <TD>
    	<INPUT class="small-text" type='text' id="date_time1" AUTOCOMPLETE=off onclick='calendar(this);' name="date_time1" value=<?php echo get_argg('date_time1');?>> ~ <INPUT class="small-text" name="date_time2" AUTOCOMPLETE=off onclick='calendar(this);' id="date_time2" type='text' value=<?php echo get_argg('date_time2');?>> (YYYY-MM-DD) 
    </TD>    
  </TR>
  <TR>
    <TH width="50px"><?php echo $m_langpackage->m_result_order;?></TH>
    <TD colSpan=3>
    	<SELECT name=order_by> 
    		<OPTION value="id" <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></OPTION> 
    		<OPTION value="date_time" <?php echo $o_time;?>><?php echo $m_langpackage->m_issue_time;?></OPTION> 
    	</SELECT> 
      <?php echo order_sc();?>
      <?php echo perpage();?>
  </TD>
  </TR>
  <tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
  </TBODY></TABLE>
</form>
	</div>
</div>

<form method="post" action="affair_del.action.php" id="data_list">
<div class="infobox">
    <h3><?php echo $m_langpackage->m_feed_list;?></h3>
    <div class="content">
	<table class='list_table <?php echo $isset_data;?>'>
	<?php foreach($affair_rs as $rs){?>
		<tr>
      <td width="20px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['id'];?>" /></td>
		  <td valign='top' style="padding-top:3px;padding-bottom:12px;width:60px;">
				<div class='avatar'><a href="../home.php?h=<?php echo $rs["user_id"];?>" target="_blank" title="<?php echo $rf_langpackage->rf_v_home;?>"><span></span>
					<img src='../<?php echo $rs["user_ico"];?>' width=43 height=43></a>
				</div>
	    </td>
			<td valign='top' style="padding-left:6px;width:'*'">
				<a href="../home.php?h=<?php echo $rs["user_id"];?>" target="_blank" title="<?php echo $m_langpackage->m_visitor_home;?>">
					<?php echo $rs["user_name"];?>
				</a>
				<?php
					$order=array();
					$replace=array();
					$content='';
					if(!preg_match("/$m_langpackage->m_preg_link/",$rs['content'])){
						$order = array("href=\"","src=\"","src=\"../http://");
						$replace = array("href=\"../","src=\"../","src=\"http://");
					}
					$title=str_replace($order,$replace,$rs['title']);

					if($rs['mod_type']==6){
						$con=str_replace($order,$replace,get_face(sub_str($rs["content"],80)));
						$content.=$title.$con;
					}else if($rs['mod_type']==7){
						$con=str_replace($order,$replace,$rs["content"]);
						$content.=$title.'<br/>'.$con;
					}else{
						$content.=$title;
					}
					$content.='<br/>'.format_datetime_txt($rs["date_time"]);
					echo $content;
				?>
			</td>
			<td width='15px'>
				<div id="operate_<?php echo $rs['id'];?>">
					<a href="javascript:del_affair(<?php echo $rs['id'];?>);"><image src="images/del.gif" title="<?php echo $m_langpackage->m_del;?>" alt="<?php echo $m_langpackage->m_del;?>" onclick="return confirm('<?php echo $m_langpackage->m_ask_del;?>');" /></a>					
				</div>
			</td>
    
		</tr>
	<?php
		}
	?>
    <tr>
    <td colspan="4"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
    <input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td>
  </tr>
	</table>
	<?php page_show($isNull,$page_num,$page_total);?>
	<div class='guser_ide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</div>
</form>
</body>
</html>		