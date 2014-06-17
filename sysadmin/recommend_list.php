<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	$is_check=check_rights("c04");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	require("../foundation/fback_search.php");
	
	//表定义区
	$t_table=$tablePreStr."recommend";

	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage')? intval(get_argg('perpage')) : 20;

	$eq_array=array('is_pass','user_sex','user_id','rec_class');
	$like_array=array('user_name');
	$date_array=array("add_time");
	$num_array=array('guest_num');
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

	//取得数据
	$dbo->setPages($c_perpage,$page_num);
	$recommend_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;

	//用户状态
	$s_no_limit='';$s_lock='';$s_normal='';
	if(get_argg('u_state')==''){$s_no_limit="selected";}
	if(get_argg('u_state')=='0'){$s_lock="selected";}
	if(get_argg('u_state')=='1'){$s_normal="selected";}

	//用户性别
	$sex_no_limit='';$sex_women='';$sex_man='';
	if(get_argg('u_sex')==''){$sex_no_limit="selected";}
	if(get_argg('u_sex')=='0'){$sex_women="selected";}
	if(get_argg('u_sex')=='1'){$sex_man="selected";}

	//按字段排序
	$o_def='';$o_guest_num='';
	if(get_argg('order_by')==''||get_argg('order_by')=="rec_class"){$o_def="selected";}
	if(get_argg('order_by')=="guest_num"){$o_guest_num="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($recommend_rs)){
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
<script type='text/javascript'>
function cancel_recommend(recom_id,user_id){
	var cancel_recommend=new Ajax();
	cancel_recommend.getInfo("recommend_del.action.php","GET","app","recom_id="+recom_id+"&uid="+user_id,function(c){if(c!='')alert(c);window.location.reload();}); 
}

function recommend_update(recom_id,recclass){
	var recommend_update=new Ajax();
	recommend_update.getInfo("recommend_upd.action.php","GET","app","recclass="+recclass+"&recom_id="+recom_id,function(c){if(c!='')alert(c);window.location.reload();}); 
}

function rec_order(recom_id){
	var order_value=document.getElementById("order_"+recom_id).value;
	if(isNaN(order_value)){
		alert('<?php echo $m_langpackage->m_num_no;?>');
	}else{
	var rec_order=new Ajax();
	rec_order.getInfo("recommend_upd.action.php","GET","app","order_value="+order_value+"&recom_id="+recom_id,function(c){if(c!='')alert(c);window.location.reload();});
	}
}
	</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="recommend_list.php?order_by=rec_order&order_sc=asc"><?php echo $ad_langpackage->ad_member_recommend_management;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET" name='form'>
  <table class="form-table">
    <tbody>
      <tr>
        <th width=108><?php echo $m_langpackage->m_userid;?></th>
        <td width=30%><input type='text' class="small-text" name='user_id' value='<?php echo get_argg('user_id');?>'></td>
        <th width=12%><?php echo $m_langpackage->m_uname;?></th>
        <td width=*><input type='text' class="small-text" name=user_name value='<?php echo get_argg('user_name');?>'>&nbsp;<font color=red>*</font></td>
      </tr>
      <tr>
        <th><?php echo $m_langpackage->m_state;?></th>
        <td><select name='is_pass'>
          <option value="" <?php echo $s_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
          <option value="1" <?php echo $s_normal;?>><?php echo $m_langpackage->m_normal;?></option>
          <option value="0" <?php echo $s_lock;?>><?php echo $m_langpackage->m_lock;?></option>
        </select></td>
        <th><?php echo $m_langpackage->m_sex;?></th>
        <td><select name="user_sex">
          <option value='' <?php echo $sex_no_limit;?>><?php echo $m_langpackage->m_astrict_no;?></option>
          <option value='0' <?php echo $sex_women;?>><?php echo $m_langpackage->m_woman;?></option>
          <option value='1' <?php echo $sex_man;?>><?php echo $m_langpackage->m_man;?></option>
        </select></td>
      </tr>
      <tr>
        <th><?php echo $m_langpackage->m_guest;?></th>
        <td><input type='text' class="small-text" name="guest_num1" value='<?php echo get_argg('guest_num1');?>'>
          ~
          <input class="small-text" type='text' name='guest_num2' value='<?php echo get_argg('guest_num2');?>'></td>
      </tr>
      <tr>
        <th><?php echo $m_langpackage->m_result_order;?></th>
        <td colspan=3><select name='order_by'>
          <option value='rec_order' <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></option>
          <option value='guest_num' <?php echo $o_guest_num;?>><?php echo $m_langpackage->m_guest;?></option>
        </select>
      <?php echo order_sc();?>
      <?php echo perpage();?>
          </td>
      </tr>
      <tr>
        <td colspan=2><?php echo $m_langpackage->m_red;?></td>
        <tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
      </tr>
    </tbody>
  </table>
</form>
	</div>
</div>

<div class="infobox">
    <h3><?php echo $m_langpackage->m_member_list;?></h3>
    <div class="content">
<form action='recommend_upd.action.php' method='post'>
<table class='list_table <?php echo $isset_data;?>'>
	<thead><tr>
    
    <th><?php echo $m_langpackage->m_uname;?></th>
    <th><?php echo $m_langpackage->m_ico;?></th>
	  <th><?php echo $m_langpackage->m_sex;?></th>
	  <th><?php echo $m_langpackage->m_guest;?></th>
	  <th><?php echo $m_langpackage->m_state;?></th>
	  <th><?php echo $m_langpackage->m_recom_value;?></th>
	  <th><?php echo $m_langpackage->m_order;?></th>
	  <th width='30%'><?php echo $m_langpackage->m_ctrl;?></th>
    
  </tr></thead>
	<?php
	foreach($recommend_rs as $rs){
	?>
	<tr>
		<td><?php echo $rs['user_name'];?></td>
		<td>
			<a href="../home.php?h=<?php echo $rs['user_id'];?>" target="_blank"><span></span><img src=../<?php echo $rs['user_ico'];?> height='43' width='43' /></a>
		</td>
		<td><?php if($rs['user_sex']==1) echo $m_langpackage->m_man;else echo $m_langpackage->m_woman;?></td>
		<td><?php echo $rs['guest_num'];?></td>
		<td><?php if($rs['is_pass']==1)echo $m_langpackage->m_normal;else echo "<font color='red'>$m_langpackage->m_lock</font>";?></td>
		<td><?php if($rs['rec_class']==1) echo $m_langpackage->m_top; else echo $m_langpackage->m_recom;?></td>
		<td><input style='width:30px' type='text' value='<?php echo $rs['rec_order']?>' name="order_num[<?php echo $rs['recommend_id'];?>]" id='order_<?php echo $rs['recommend_id'];?>' /></td>
		<td>
			<?php if($rs['rec_class'] == 0){?>
			<a href="javascript:recommend_update(<?php echo $rs['recommend_id'];?>,1)";><?php echo $m_langpackage->m_top_recom;?></a> | 
			<?php }else{?>
			<a href="javascript:recommend_update(<?php echo $rs['recommend_id'];?>,0)";><?php echo $m_langpackage->m_cancel_recom;?></a> | 
			<a href="show_ico_cut.php?user_id=<?php echo $rs['user_id'];?>";><?php echo $m_langpackage->m_index_pic;?></a> |
			<?php }?>
			<a href='javascript:rec_order(<?php echo $rs['recommend_id'];?>);'><?php echo $m_langpackage->m_app_order;?></a> | 
			<a href="javascript:cancel_recommend(<?php echo $rs['recommend_id'];?>,<?php echo $rs['user_id'];?>)";><?php echo $m_langpackage->m_cancel;?></a>
		</td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td colspan=8 style='text-align:right;padding-right:100px'><input type='submit' name='is_batch' class='regular-button' value='<?php echo $m_langpackage->m_batch_order;?>' /></td>
	</tr>
	</table>
<?php page_show($isNull,$page_num,$page_total);?>
</form>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</body>
</html>