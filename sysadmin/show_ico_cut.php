<?php
	require("session_check.php");
	require("../foundation/module_album.php");
	require("../api/base_support.php");
	$is_check=check_rights("c06");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	//语言包引入
	$f_langpackage=new foundationlp;
	$m_langpackage=new modulelp;
	$a_langpackage=new albumlp;
	$ad_langpackage=new adminmenulp;
	$user_id=intval(get_argg('user_id'));

	//表定义区
	$t_users=$tablePreStr."users";
	$t_album=$tablePreStr."album";
	$t_recommend=$tablePreStr."recommend";

	$dbo = new dbex;
	dbtarget('r',$dbServs);

	$sql="select user_name , user_ico , show_ico from $t_recommend where user_id=$user_id";
	$user_row=$dbo->getRow($sql);

	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $m_langpackage->m_member_list;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
</head>
<body>
<div id="maincontent">
  <div class="wrap">
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="show_ico_cut.php?user_id=<?php echo $user_id;?>"><?php echo $ad_langpackage->ad_top_slide;?></a></div>
    <hr />
    <div class="infobox">
    	<h3><?php echo $f_langpackage->f_index_pic;?></h3>
      <div class="content">
				<table class='form-table' style='vertical-align:top'>
					<tr>
						<td style='text-align:center'><img src="../<?php echo $user_row['user_ico'];?>" width='45px' /></td>
						<td width="150px">
							<?php echo $user_row['user_name'];?><?php echo $f_langpackage->f_index_list?>
						</td>
						<td style="width:*">
							<?php album_name($album_rs,0);?>
						</td>
					</tr>
					<tr><td class="dotted_line" colspan="10" style="line-height:100%;">&nbsp</td></tr>
					<script type='text/javascript'>
					var album_select=document.getElementById("album_name");
					album_select.onchange=list_album_photos;
					function list_album_photos(){//获取接受返回信息层
						var album_select_val=document.getElementById("album_name").value;
						var photos_list=document.getElementById("photos_list");
						if(album_select_val==""){
							return false;
						}else{
							var get_album=new Ajax();
							get_album.getInfo("../modules.php","GET","app","app=user_ico_select&type=index_pic&user_id=<?php echo $user_id;?>&album_id="+album_select_val,"photos_list");
							photos_list.innerHTML="数据加载中,请稍侯...";
						}
					}
					</script>
					<tr><td colspan=4><div id='photos_list'></div></td></tr>
					<tr><td colspan=4><font color='red'>*</font><strong><?php echo $f_langpackage->f_select_pic?></strong></td></tr>
					<tr><td width="90px"><?php echo $f_langpackage->f_now_index?></td><td colspan=3><img src="../<?php echo $user_row['show_ico'];?>" /></td></tr>
				</table>
			</div>
		</div>
	</div>
</div>
</body>
</html>