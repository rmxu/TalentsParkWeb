<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/msgscrip/creator.html
 * 如果您的模型要进行修改，请修改 models/modules/msgscrip/creator.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$m_langpackage=new msglp;
	
	//引入公共模块
	require("foundation/module_mypals.php");
	require("foundation/module_users.php");
	require("api/base_support.php");
	
	//变量获得
	$user_id=get_sess_userid();

	//数据表定义区
	$t_users = $tablePreStr."users";
	$t_mypals = $tablePreStr."pals_mine";

	$dbo=new dbex;
	//读写分离定义方法
	dbtarget('r',$dbServs);

	if(get_argg("2id")==""){
		$user_rs = getPals_mine_all($dbo,$t_mypals,$user_id);
	}
	
	$reTitle="";
	if(get_argg("rt")!=""){
		$reTitle=$m_langpackage->m_res."：".short_check(get_argg("rt"));
	}
	$have_2id="";
	$to_user_name="";
	if(get_argg("2id")!=""){
		$to_user_id=intval(get_argg("2id"));
		$to_id="<input name='2id' value='".get_argg("2id")."' type=hidden>";
		$id_confirm="";
		$id_noconfirm="content_none";
		$have_2id="msToId";
		$to_user_name=get_hodler_name($to_user_id);
	}else{
		$to_id="";
		$id_confirm="content_none";
		$id_noconfirm="";
		$no_2id="msToId";
	}
	
	if(get_argg("nw")=="1"){
		$b_can="";
		$b_bak="content_none";
	}else{
		$b_can="content_none";
		$b_bak="";
	}
	$i=0;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<script language="JavaScript">
function unitinfocheck()
{
	oldContent = document.getElementById('msContent').value;
	if(document.form1.msToId.value=="")
	{
		parent.Dialog.alert("<?php echo $m_langpackage->m_no_one;?>");
		return (false);
	}
		var msTitle=trim(document.getElementById("msTitle").value);
		if(msTitle==''){
		parent.Dialog.alert("<?php echo $m_langpackage->m_no_tit;?>");
		return (false);
	}
	var msContent=trim(document.getElementById("msContent").value);
	if(msContent==''){
		parent.Dialog.alert("<?php echo $m_langpackage->m_no_cont;?>");
		return (false);
	}
}

function trim(str){
	return str.replace(/(^\s*)|(\s*$)|(　*)/g , "");
}

function topen(){
	document.getElementById("sc_nav").focus();
	document.getElementById("sc_nav").className="displayblock";
}
function tclose(){
	document.getElementById("sc_nav").className="displaynone";
}
function navArray(num,id_val) {
	var navArray=document.getElementById("sc_nav").getElementsByTagName("li");
	var objChecked = document.getElementsByName ("checked");
	for(var i=0;i<navArray.length;i++){
		if(num == i ){
		  navArray[num].className = "checked";
			document.getElementById("msToId").value = id_val;
		  document.getElementById("newsType").innerHTML=navArray[num].innerHTML;
		  document.getElementById("sc_nav").className="displaynone";
		}else{
		  navArray[i].className = "";
		}
	}
}
function isMaxLen(o){
	var nMaxLen=o.getAttribute? parseInt(o.getAttribute("maxlength")):"";  
	if(o.getAttribute && o.value.length>nMaxLen){  
		o.value=o.value.substring(0,nMaxLen)  
	}
}
</script>
<body id="iframecontent">
    <div class="create_button"><a href="modules.php?app=msg_creator"><?php echo $m_langpackage->m_creat;?></a></div>
    <h2 class="app_msgscrip"><?php echo $m_langpackage->m_title;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:void(0)" hidefocus="true"><?php echo $m_langpackage->m_creat;?></a></li>
        </ul>
    </div>
    <form name="form1" onSubmit="return unitinfocheck();" method="post" action="do.php?act=msg_crt">
	 <table class='form_table'>
		  <?php echo $to_id;?>
          <tr>
			<th><?php echo $m_langpackage->m_to_user;?>：</th>
			<td class="<?php echo $id_confirm;?>"><input class="med-text" type="text" name="<?php echo $have_2id;?>" autocomplete='off' value='<?php echo $to_user_name;?>'  disabled="disabled"  /></td>
			<td class="<?php echo $id_noconfirm;?>" style="position:relative">
				<input name="msToId" id="<?php echo $no_2id;?>" onclick="topen();" onblur="tclose()" type="hidden" value="" />
				<div id="sc_nav" onblur="setTimeout('tclose()',200)">
					<div id="newsType" onclick="topen();"><?php echo $m_langpackage->m_cho;?></div>
					<ul class="tt">
						<?php foreach($user_rs as $val){?>
						<li class="checked" onClick="navArray('<?php echo $i;?>','<?php echo $val['pals_id'];?>')"><a href="javascript:void(0)"><?php echo $val['pals_name'];?></a></li>
						<?php $i++;?>
						<?php }?>
					</ul>
				</div>
			</td>
			</tr>
			<tr>
				<th><?php echo $m_langpackage->m_tit;?>：</th>
				<td><input type="text" class="med-text" name="msTitle" id="msTitle" autocomplete='off' value='<?php echo $reTitle;?>' maxlength="30" /></td>
			</tr><tr><td colspan="2" height="5"></td></tr>
			<tr>
				<th valign="top"><?php echo $m_langpackage->m_cont;?>：</th>
				 <td><textarea maxlength="160" class="med-textarea" name="msContent" id="msContent" onKeyUp="return isMaxLen(this)"></textarea></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
           		 <td class="<?php echo $b_can;?>">
           		    <input class="regular-btn" type="submit" value="<?php echo $m_langpackage->m_b_con;?>" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type=hidden name="nw" value='<?php echo get_argg("nw");?>' />
		   		    <input class="regular-btn" type="button" value="<?php echo $m_langpackage->m_b_can;?>" onClick="history.go(-1)" />
				 </td>
				 <td class="<?php echo $b_bak;?>">
				 	<input class="regular-btn" type="submit" value="<?php echo $m_langpackage->m_b_con;?>" />&nbsp;&nbsp;&nbsp;&nbsp;
				 	<input class="regular-btn" type="button" value="<?php echo $m_langpackage->m_b_bak;?>" onClick="location.href='modules.php?app=msg_minbox'" />
				 </td>
		    </tr>
		</table>
       </form>
	<div id="clickLayer" onclick="setTimeout('tclose()',200)"></div>
</body>
</html>