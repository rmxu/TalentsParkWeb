<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/mypals/pals_manager_sort.html
 * 如果您的模型要进行修改，请修改 models/modules/mypals/pals_manager_sort.php
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
	require("api/base_support.php");

	//引入语言包
	$mp_langpackage=new mypalslp;
	$b_langpackage=new bloglp;

   //变量定义
   $user_id=get_sess_userid();   
   $pals_sort_rs = api_proxy("pals_sort","*",$user_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<SCRIPT language=JavaScript src="skin/default/js/jooyea.js"></SCRIPT>
<script type="text/javascript">
function save_sort(sort_id,text_id){
	var sort_value=document.getElementById(text_id).value;
	if(trim(sort_value)==''){
		parent.Dialog.alert('<?php echo $mp_langpackage->mp_sort_wrong;?>');
	}else{
		var save_sort=new Ajax();
		save_sort.getInfo("do.php?act=pals_sort_change&id="+sort_id,"post","app","name="+sort_value,function(c){$('info_'+sort_id).style.display="";$('info_'+sort_id+'_edit').style.display="none";$('show_info_'+sort_id).innerHTML=sort_value;});
	}
}

function exit_sort(info_id,show_id,text_id){
	document.getElementById(info_id).style.display="";
	document.getElementById(info_id+"_edit").style.display="none";
}

function edit_sort(info_id){
	document.getElementById(info_id).style.display="none";
	document.getElementById(info_id+"_edit").style.display="";
}

function show_add(){
	document.getElementById("show_add_button").style.display="none";
	document.getElementById("add_area").style.display="";
}

function exit_add(){
	document.getElementById("add_area").style.display="none";
	document.getElementById("show_add_button").style.display="";
	document.getElementById("new_sort").value="";
}

function check_form(){
	var new_sort=document.getElementById("new_sort").value;
	if(trim(new_sort)==''){
		parent.Dialog.alert('<?php echo $mp_langpackage->mp_sort_wrong;?>');
		return false;
	}else{
		return true;
	}
}

</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=mypals_search"><?php echo $mp_langpackage->mp_add;?></a></div>
    <h2 class="app_friend"><?php echo $mp_langpackage->mp_mypals;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li><a href="modules.php?app=mypals" title="<?php echo $mp_langpackage->mp_list;?>"><?php echo $mp_langpackage->mp_list;?></a></li>
            <li><a href="modules.php?app=mypals_request" title="<?php echo $mp_langpackage->mp_req;?>"><?php echo $mp_langpackage->mp_req;?></a></li>
            <li><a href="modules.php?app=mypals_invite" title="<?php echo $mp_langpackage->mp_invite;?>"><?php echo $mp_langpackage->mp_invite;?></a></li>
            <li class="active"><a href="modules.php?app=mypals_sort" title="<?php echo $mp_langpackage->mp_m_sort;?>"><?php echo $mp_langpackage->mp_m_sort;?></a></li>
        </ul>
    </div> 
    <div class="rs_head"><a href='modules.php?app=mypals&sort_id=0'><?php echo $mp_langpackage->mp_no_sort;?></a>（<?php echo $mp_langpackage->mp_def_sort;?>）</div>
	<table class="form_table" id='sort_table' cellspacing="0">
		<tr>
		<?php foreach($pals_sort_rs as $rs){?>
			
		<tr id="info_<?php echo $rs['id'];?>">
			<td>
					<div id="show_info_<?php echo $rs['id'];?>">
						<a href='modules.php?app=mypals&sort_id=<?php echo $rs["id"];?>'><?php echo $rs['name'];?></a>
					</div>
			</td>
			<td>
					<a  href=javascript:edit_sort("info_<?php echo $rs['id'];?>");><?php echo $b_langpackage->b_edit;?></a>&nbsp &nbsp
					<a href='do.php?act=pals_sort_del&id=<?php echo $rs["id"];?>' onclick='return confirm("<?php echo $b_langpackage->b_a_del;?>")'><?php echo $b_langpackage->b_del;?></a>
			</td>
		</tr>
		
		<tr id="info_<?php echo $rs['id'];?>_edit" style="display:none;">
				<td><input class="small-text" type='text' id="change_sort_<?php echo $rs['id'];?>" value="<?php echo $rs['name'];?>" maxlength="8">
				</td>
				<td>
					<a href="javascript:void(0);"  onclick=save_sort("<?php echo $rs['id'];?>","change_sort_<?php echo $rs['id'];?>"); ><?php echo $b_langpackage->b_button_save;?></a>&nbsp &nbsp
				    <a href="javascript:void(0);"  onclick=exit_sort("info_<?php echo $rs['id'];?>","show_info_<?php echo $rs['id'];?>","change_sort_<?php echo $rs['id'];?>"); ><?php echo $b_langpackage->b_button_cancel;?></a>
				</td>

		</tr>
		
		<?php }?>
	</table>	
    <table class="form_table" cellspacing="0">
    	<tr>
			<td>
				<input type='button' id='show_add_button' class="regular-btn" style='display:""; font-size:12px;' value='<?php echo $mp_langpackage->mp_add_sort;?>' maxlength="8" onclick='show_add();' /></td><td>
				<div id='add_area' style='display:none'>
					<form action='do.php?act=pals_sort_add' method='post' onsubmit='return check_form();'>
						<input class="small-text" type='text' name='new_sort' id='new_sort' />
						<input type='submit' value='<?php echo $mp_langpackage->mp_t_sort;?>' class='small-btn' />&nbsp
						<input type='button' value='<?php echo $b_langpackage->b_button_cancel;?>' class='small-btn' onclick='exit_add();' />
					</form>
				</div>
			</td>
		</tr>
</table>						
</body>
</html>