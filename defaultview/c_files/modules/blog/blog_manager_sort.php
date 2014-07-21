<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/blog/blog_manager_sort.html
 * 如果您的模型要进行修改，请修改 models/modules/blog/blog_manager_sort.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("foundation/module_blog.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;

  //变量定义
  $user_id=get_sess_userid();

  //数据表定义  
  $t_blog_sort=$tablePreStr."blog_sort";
  $blog_sort_rs=api_proxy("blog_sort_by_uid",$user_id);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script type="text/javascript">
function save_sort_callback(sort_value,sort_id){
	document.getElementById('info_'+sort_id).style.display="";
	document.getElementById('info_'+sort_id+'_edit').style.display="none";
	document.getElementById('show_info_'+sort_id).innerHTML=sort_value;
}

function save_sort(sort_id,text_id){
	var sort_value=document.getElementById(text_id).value;
	var save_sort=new Ajax();
	save_sort.getInfo("do.php?act=blog_sort_change&id="+sort_id,"post","app","name="+sort_value,function(c){save_sort_callback(sort_value,sort_id);}); 
}

function exit_sort(info_id,show_id,text_id){
	document.getElementById(info_id).style.display="";
	document.getElementById(info_id+"_edit").style.display="none";
}

function edit_sort(info_id){
	document.getElementById(info_id).style.display="none";
	document.getElementById(info_id+"_edit").style.display="";
}

function del_sort(sort_id){
	var del_sort=new Ajax();
	del_sort.getInfo("do.php","get","app","act=blog_sort_del&id="+sort_id,function(c){document.getElementById("show_info_"+sort_id).style.display='none';document.getElementById("show_ctrl_"+sort_id).style.display='none';}); 
}
</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="javascript:window.history.go(-1);"><?php echo $b_langpackage->b_re_last;?></a></div>
    <h2 class="app_blog"><?php echo $b_langpackage->b_blog;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:modules.php?app=blog_manager_sort;" hidefocus="true"><?php echo $b_langpackage->b_manage_sort;?></a></li>
        </ul>
    </div>
	<table width="90%" class="form_table" cellspacing="0">
		<tr>
			<td colspan='2'><?php echo $b_langpackage->b_def_sort;?></td>
		</tr>
		<?php foreach($blog_sort_rs as $rs){?>
			<tr id="info_<?php echo $rs['id'];?>">
				<td>
					<div id="show_info_<?php echo $rs['id'];?>" class="log_sort_bg">
						<?php echo $rs['name'];?>
					</div>
				</td>
				<td>
					<div id='show_ctrl_<?php echo $rs['id'];?>'>
						<a class="log_edit_link" href=javascript:edit_sort("info_<?php echo $rs['id'];?>");><?php echo $b_langpackage->b_edit;?></a>&nbsp &nbsp
						<a class="log_del_link" href='javascript:del_sort(<?php echo $rs["id"];?>)' onclick='return confirm("<?php echo $b_langpackage->b_a_del;?>")'><?php echo $b_langpackage->b_del;?></a>
					</div>
				</td>
			</tr>
			<tr id="info_<?php echo $rs['id'];?>_edit" style="display:none;">
				<td class="td_a">
					<input type='text' class="small-text" id="change_sort_<?php echo $rs['id'];?>" value="<?php echo $rs['name'];?>" maxlength="20">
				</td>
				<td class="td_b">
					<input type='button' class='small-btn' value='<?php echo $b_langpackage->b_button_save;?>' onclick=save_sort("<?php echo $rs['id'];?>","change_sort_<?php echo $rs['id'];?>"); />&nbsp &nbsp
					<input type='button' class='small-btn' value='<?php echo $b_langpackage->b_button_cancel;?>' onclick=exit_sort("info_<?php echo $rs['id'];?>","show_info_<?php echo $rs['id'];?>","change_sort_<?php echo $rs['id'];?>");  />
				</td>
			</tr>
		<?php }?>
	</table>
</body>
</html>