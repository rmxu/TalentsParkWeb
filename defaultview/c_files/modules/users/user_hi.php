<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_hi.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_hi.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入模块公共方法文件 
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fpages_bar.php");
	
	$to_user_id=get_sess_userid();
	$hi_rs=array();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//引入语言包
	$u_langpackage=new userslp;
	$hi_langpackage=new hilp;
	
	//数据表定义区
	$t_hi = $tablePreStr."hi";
	
	//定义读取操作
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$dbo->setPages(20,$page_num);//设置分页
	$sql="select * from $t_hi where to_user_id=$to_user_id order by add_time desc";
	$hi_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数
	$isNull=0;
	$none_data='content_none';
	if(empty($hi_rs)){
		$none_data='';
		$isNull=1;
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script language=JavaScript src="skin/default/js/jooyea.js"></script>
<script language='javascript'>
function del_hi(hi_id)
{
	 var del_hi=new Ajax();
	 del_hi.getInfo("do.php","get","app","act=user_del_hi&hi_id="+hi_id,function(c){$('hi_'+hi_id).style.display='none';}); 
}

function check_form(){
	var mail_array=document.getElementsByName('attach[]');
	var num=mail_array.length;
	var check_num=0;
	for(array_length=0;array_length<num;array_length++){
		if(mail_array[array_length].checked==true){
			check_num++;
		}
	}
	if(check_num==0){
		parent.Dialog.alert('<?php echo $hi_langpackage->hi_wrong;?>');
	}else{
		parent.Dialog.confirm('<?php echo $hi_langpackage->hi_del_con;?>',function(){document.forms[0].submit();});
	}
}
</script>
<body>
<body id="iframecontent">
<h2 class="app_user"><?php echo $hi_langpackage->hi_my_hi;?></h2>
<div class="line"></div>
<?php if($hi_rs){?>
<form action='do.php?act=user_del_hi' method='post' onsubmit='check_form()'>
<div class="rs_head <?php echo $show_data;?>">
	<?php echo $hi_langpackage->hi_select;?>：<a href="javascript:select_attach(1);"><?php echo $hi_langpackage->hi_all;?></a> - 
    <a href="javascript:select_attach(0);"><?php echo $u_langpackage->u_b_can;?></a>&nbsp;
    <a href="javascript:document.forms[0].onsubmit();"><?php echo $hi_langpackage->hi_del;?></a>
</div>
<table class="msg_inbox">
   <?php foreach($hi_rs as $val){?>
    <tr class="loop_tr" id="hi_<?php echo $val['hi_id'];?>">
    	<td width="25" align="center"><input name="attach[]" type="checkbox" value="<?php echo $val['hi_id'];?>" /></td>
    	<td width="30">
		  <div class="avatar"><a href="home.php?h=<?php echo $val['from_user_id'];?>" target="_blank"><img src="<?php echo $val['from_user_ico'];?>"/></a></div>
      </td>
        <td width="70"><img src="skin/<?php echo $skinUrl;?>/images/pokeact_<?php echo $val['hi'];?>.gif"></img></td>
		<td><a href='home.php?h=<?php echo $val['from_user_id'];?>' target='_blank'><?php echo $val['from_user_name'];?></a><?php echo show_hi_type($val['hi']);?></td>
		<td><?php echo $val['add_time'];?></td>
		<td><a href='javascript:void(0)' onclick='parent.hi_action(<?php echo $val['from_user_id'];?>);'><?php echo $hi_langpackage->hi_restore;?></a></td>
		<td><a href='javascript:del_hi(<?php echo $val['hi_id'];?>);' title="<?php echo $hi_langpackage->hi_del;?>" alt="<?php echo $hi_langpackage->hi_del;?>" onclick='return confirm("<?php echo $hi_langpackage->hi_del_con;?>");'><img src='skin/<?php echo $skinUrl;?>/images/del.png' /></a></td>
  <?php }?>
  </table>
<div class="rs_head <?php echo $show_data;?>">
	<?php echo $hi_langpackage->hi_select;?>：<a href="javascript:select_attach(1);"><?php echo $hi_langpackage->hi_all;?></a> - 
    <a href="javascript:select_attach(0);"><?php echo $u_langpackage->u_b_can;?></a>&nbsp;
    <a href="javascript:document.forms[0].onsubmit();"><?php echo $hi_langpackage->hi_del;?></a>
</div>
</form>
<?php }?>
<div class="guide_info <?php echo $none_data;?>">
	<?php echo $hi_langpackage->hi_data_none;?>
</div>
  <?php echo page_show($isNull,$page_num,$page_total);?>
</body>
</html>
