<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/affair_set.html
 * 如果您的模型要进行修改，请修改 models/modules/users/affair_set.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;
	$pr_langpackage=new privacylp;
	$user_id=get_sess_userid();

	//表声明区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";

	$dbo=new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);

	$type_array=array();
	for($def_num=0;$def_num<=6;$def_num++){
		$type_array[]=$pr_langpackage->{'pr_type_'.$def_num};
	}

	$mypals = getPals_mine_all($dbo,$t_mypals,$user_id);

	$whole_type=",1,2,3,4,5,6,";
	$user_row = api_proxy("user_self_by_uid","hidden_pals_id,hidden_type_id",$user_id);
	$hidden_p=$user_row['hidden_pals_id'];
	$hidden_t=$user_row['hidden_type_id'];
	$hidden_pals_rs=array();
	$hidden_type_rs=array();

	if(!empty($hidden_p) && $hidden_p!=","){
		$hidden_pals_rs = api_proxy("user_self_by_uid","user_id,user_name",$hidden_p);
	}

	if(!empty($hidden_t) && $hidden_t!=","){
		$hidden_type_array=explode(",",$hidden_t);
		foreach($hidden_type_array as $value){
			if($value!=''){
				$hidden_type_rs[$value]=$pr_langpackage->{'pr_type_'.$value};
			}
		}
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script language=JavaScript src="skin/default/js/jooyea.js"></script>
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script type='text/javascript'>
	function open_div(obj_id){
		if($(obj_id).style.display=='none'){
			$(obj_id).style.display='';
		}else{
			$(obj_id).style.display='none';
		}
	}
	
	function act_hidden_callback(content){
		if(content=='success'){
			parent.Dialog.alert('<?php echo $pr_langpackage->pr_handle_suc;?>');
			window.location.reload();
		}else{
			parent.Dialog.alert(content);
		}	
	}
	
	function act_hidden(type_id,hidden_value,is_del){
		var act_hidden=new Ajax();
		act_hidden.getInfo("do.php","GET","app","act=pr_affair&type="+type_id+"&hidden_value="+hidden_value+"&is_del="+is_del,function(c){act_hidden_callback(c);});
	}
</script>
</head>

<body id="iframecontent">
<h2 class="app_user"><?php echo $u_langpackage->u_conf;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
        <li><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
        <li><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
        <li><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
        <li class="active"><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
    </ul>
</div>
<div class="rs_head"><?php echo $pr_langpackage->pr_forget_sort;?></div>

<table class="affair_table tleft" border="0" cellpadding="5" cellspacing="5">
<tr>
	<td width="50%" align="left">
		<?php echo $pr_langpackage->pr_add_sort;?>：<input class="small-btn" type='button' onclick='open_div("hidden_type");' value='<?php echo $pr_langpackage->pr_chose;?>' />
	</td>
	<td width="50%"><?php echo $pr_langpackage->pr_shield_sort;?>：</td>	
</tr>
<tr>
	<td>	
		<div id="hidden_type" style='display:none;'>
			<ul>
			<?php $l=0;?>
			<?php foreach($type_array as $val){?>
				<li><a href="javascript:act_hidden(1,<?php echo $l;?>,0);"><?php echo $val;?></a></li>
			<?php $l++;?>
			<?php }?>
			</ul>
		</div>
	</td>
	<td>
			<ul>
			<?php foreach($hidden_type_rs as $key=>$val){?>
				<li><?php echo $val;?> | <a href="javascript:act_hidden(1,<?php echo $key;?>,1);"><?php echo $pr_langpackage->pr_del;?></a></li>
			<?php }?>				
			</ul>
	</td>
</tr>
</table>
			<div class="rs_head"><?php echo $pr_langpackage->pr_forget_list;?></div>
<table class="affair_table" border="0" cellpadding="5" cellspacing="5">
	<tr>
		<td width="50%">
			<?php echo $pr_langpackage->pr_add_list;?>：<input class="small-btn" type='button' onclick='open_div("hidden_pals");' value='<?php echo $pr_langpackage->pr_chose;?>' />
		</td>
		<td width="50%">
			<?php echo $pr_langpackage->pr_shield_list;?>：
		</td>
	</tr>
	<tr>
		<td width="50%">
			<div id="hidden_pals" style='display:none;'>
					<ul>
					<?php $i=0;?>
					<?php foreach($mypals as $val){?>
						<li><a href="javascript:act_hidden(0,<?php echo $val['pals_id'];?>,0);"><?php echo $val['pals_name'];?></a></li>
					<?php $i++;?>
					<?php }?>
					</ul>
			</div>
		</td>
		<td width="50%">
				<ul>
					<?php foreach($hidden_pals_rs as $rs){?>
						<LI><?php echo $rs['user_name'];?> | <A href="javascript:act_hidden(0,<?php echo $rs['user_id'];?>,1);"><?php echo $pr_langpackage->pr_del;?></A></LI>
					<?php }?>
				</ul>
		</td>
	</tr>
</table>
</body>
</html>
