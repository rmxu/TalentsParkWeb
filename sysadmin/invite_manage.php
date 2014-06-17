<?php
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");
require("../foundation/fcontent_format.php");

//语言包引入
$m_langpackage=new modulelp;	
$ad_langpackage=new adminmenulp;
$bp_langpackage=new back_publiclp;
$u_langpackage=new userslp;
require("../foundation/fback_search.php");
$dbo = new dbex;
dbtarget('r',$dbServs);

//数据表定义区
$t_table = $tablePreStr."invite_code";

$del_invite=get_argg('del_invite');
if($del_invite){
	$is_check=check_rights("a17");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;exit;
	}
	$id=intval(get_argg('invite_id'));
	$sql="delete from $t_table where id=$id";
	$dbo->exeUpdate($sql);
}

$is_check=check_rights("a16");
if(!$is_check){
	echo $m_langpackage->m_no_pri;exit;
}

//当前页面参数
$page_num=trim(get_argg('page'));
$invite_rs=array();

//变量区
$c_orderby=short_check(get_argg('order_by'));
$c_ordersc=short_check(get_argg('order_sc'));
$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;

$add_time1=short_check(get_argg('add_time1'));
$add_time2=short_check(get_argg('add_time2'));

$eq_array=array('id','sendor_id','is_admin','code_txt');
$like_array=array();
$date_array=array();
$num_array=array();
$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);

if($add_time1){
	$add_time1=strtotime($add_time1);
	$sql.=" and add_time >= $add_time1 ";
}

if($add_time2){
	$add_time2=strtotime($add_time2);
	$sql.=" and add_time <= $add_time2 ";
}

//设置分页
$dbo->setPages($c_perpage,$page_num);

//取得数据
$invite_rs=$dbo->getRs($sql);

//分页总数
$page_total=$dbo->totalPage;

//显示控制
$isset_data="";
$none_data="content_none";
$isNull=0;
if(empty($invite_rs)){
	$isset_data="content_none";
	$none_data="";
	$isNull=1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $m_langpackage->m_member_list;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/calendar.js'></script>
</head>
<body>
<div id="maincontent">
	<div class="wrap">
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_global_set;?></a> &gt;&gt; <a href="blog_list.php?order_by=log_id&order_sc=desc"><?php echo $ad_langpackage->ad_invite_code_management;?></a></div>
    <hr />
    <div class="infobox">
      <h3><?php echo $m_langpackage->m_check_condition;?></h3>
      <div class="content">
				<form action="" method="GET" onSubmit="return check_form()">
				<table class="form-table">
				<tbody>
				<tr>
					<th width=12%><?php echo $u_langpackage->u_invite_code_id; ?></th><td><INPUT name="id" class="small-text" type='text' value='<?php echo get_argg('id');?>' /></td>
					<th><?php echo $u_langpackage->u_invite_code; ?></th><td><INPUT name="code_txt" class="small-text" type='text' value='<?php echo get_argg('code_txt');?>' /></td>
				</tr>
				<tr>
					<th><?php echo $u_langpackage->u_identity_formation; ?></th>
					<td>
						<select name='is_admin'>
							<option value='' selected><?php echo $u_langpackage->u_open; ?></option>
							<option value='0' <?php echo get_argg('is_admin')===0 ? 'selected':'';?>><?php echo $u_langpackage->u_ordinary_members; ?></option>
							<option value='1' <?php echo get_argg('is_admin')===1 ? 'selected':'';?>><?php echo $u_langpackage->u_administrator; ?></option>
						</select>							
					</td>
					<th><?php echo $u_langpackage->u_generation_time; ?></th>
					<td><INPUT class="small-text" type='text' AUTOCOMPLETE=off onclick='calendar(this);' name='add_time1' id='add_time1' value='<?php echo get_argg('add_time1');?>' /> ~ <INPUT type='text' class="small-text" name='add_time2' AUTOCOMPLETE=off onclick='calendar(this);' id='add_time2' value='<?php echo get_argg('add_time2');?>' /> (YYYY-MM-DD) </TD>	
				</tr>
				<tr><th><?php echo $u_langpackage->u_generated_by_uid; ?></th><td><INPUT name="sendor_id" class="small-text" type='text' value='<?php echo get_argg('sendor_id');?>' /></td></tr>
				<tr>
				<th><?php echo $m_langpackage->m_result_order;?></th>
				<TD colSpan=3>
				<?php echo order_sc();?>
				<?php echo perpage();?>
				</TD>
				</TR>
				<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
				<tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
				</tbody>
				</table>
				</form>
			</div>
		</div>
    
<div class="infobox">
	<h3><?php echo $u_langpackage->u_invite_code_list; ?></h3>
	<div class="content">
			<table class="list_table <?php echo $isset_data;?>">
				<thead><tr>
					
						<th>ID</th>
                        <th><?php echo $u_langpackage->u_invite_code; ?></th>
                        <th><?php echo $u_langpackage->u_identity_formation; ?></th>
                        <th><?php echo $u_langpackage->u_generated_by_uid; ?></th>
                        <th><?php echo $u_langpackage->u_time; ?></th>
                        <th><?php echo $u_langpackage->u_time_left; ?></th>
                        <th><?php echo $u_langpackage->u_operating; ?></th>
					
				</tr></thead>
				<?php foreach($invite_rs as $rs){?>
				<tr>
					<td><?php echo $rs['id'];?></td>
					<td><?php echo $rs['code_txt'];?></td>
					<td><?php echo $rs['is_admin'] ? $u_langpackage->u_administrator:$u_langpackage->u_ordinary_users;?></td>
					<td><?php echo $rs['sendor_id'];?></td>
					<td><?php echo date('Y-m-d H:i:s',$rs['add_time']);?></td>
					<td><?php echo leave_time($rs['add_time'],$inviteCodeLife);?></td>
					<td><a href='invite_manage.php?del_invite=1&invite_id=<?php echo $rs['id'];?>'><img src='images/del.gif' /></a></td>
				</tr>
				<?php }?>
			</table>
			<?php page_show($isNull,$page_num,$page_total);?>
			<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
		</div>
	</div>
</div>
</body>
</html>