<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/mypals/pals_invite.html
 * 如果您的模型要进行修改，请修改 models/modules/mypals/pals_invite.php
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
	require("foundation/fcontent_format.php");
	
	//引入公共方法
	require("foundation/fdnurl_aget.php");
	
	//引入语言包
	$mp_langpackage=new mypalslp;
	
	//变量区
	$user_id=get_sess_userid();
	$user_invite_url=get_uinvite_url($user_id);
	$user_home_url=get_uhome_url($user_id);
		
	if($inviteCode){
  	$user_info=api_proxy('user_self_by_uid','integral',$user_id);
  	$intg=$user_info['integral'];
		$t_invite_code=$tablePreStr."invite_code";
		$dbo=new dbex;
		dbtarget('r',$dbServs);
		if(get_argg('invite_code')==1){
			require("servtools/rand_code/produce_rand.php");
			$code_value=code_exists();
			if($code_value===false){
				echo $mp_langpackage->mp_invite_code_error;
				exit;
			}else{
				$mp_c_ic = $mp_langpackage->mp_congratulations_invite_code;
				echo $mp_c_ic.$code_value;
				exit;
			}
		}else if(get_argg('del_code')==1){
			$id_array=array();
			$id=intval(get_argg('id'));
			$id_array=get_argp('attach');
			$id_array=$id ? $id : $id_array;
			foreach($id_array as $val){
				$val=intval($val);
				$sql="delete from $t_invite_code where id=$val and sendor_id=$user_id";
				$dbo->exeUpdate($sql);
			}
		}
		$sql="select * from $t_invite_code where sendor_id=$user_id and is_admin=0 order by id desc";
		$invite_rs=$dbo->getRs($sql);		
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script>
<SCRIPT type='text/javascript' src="servtools/ajax_client/ajax.js"></SCRIPT>

<script type='text/javascript'>
function copy(obj, aoConfig) {
	if(obj.type != "hidden" && obj.value != "") {
		obj.focus();
	}
		obj.select();
	if(copyToClipboard(obj.value)) {
		parent.Dialog.alert(aoConfig.sMsg);
	}
}

function copyToClipboard(value){
	if(window.clipboardData) { 
		window.clipboardData.clearData(); 
		window.clipboardData.setData("Text", value); 
	}else if(navigator.userAgent.indexOf("Opera") != -1) {
		window.location = value;
	}else if(window.netscape){
		try{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect"); 
		}
		catch (e) {
			parent.Dialog.alert(aoConfig.fMsg);
			return false;
		}
    var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
    if (!clip) return; 
    var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable); 
    if (!trans) return; 
    trans.addDataFlavor('text/unicode'); 
    var str = new Object(); 
    var len = new Object(); 
    var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString); 
    var copytext = value; 
    str.data = copytext; 
    trans.setTransferData("text/unicode",str,copytext.length*2); 
    var clipid = Components.interfaces.nsIClipboard; 
    if (!clip)
        return false; 
    clip.setData(trans,null,clipid.kGlobalClipboard); 
	}
		return true;
}
	
function select_item(type_value){
	var mail_array=document.getElementsByName('attach[]');
	var num=mail_array.length;
	for(array_length=0;array_length<num;array_length++){
		if(type_value==1){
			mail_array[array_length].checked='checked';
		}else if(type_value==0){
			mail_array[array_length].checked='';
		}
	}
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
		parent.Dialog.alert('请选择要输出的验证码');
	}else{
		parent.Dialog.confirm('是否删除？',function(){document.forms[0].submit();});
	}
}	
		
function produce_invite_code(){
	var ajax_invite_code=new Ajax();
	ajax_invite_code.getInfo("modules.php?app=mypals_invite&invite_code=1","GET","app","",function(c){show_invite_code(c);});
}

function show_invite_code(invite_code){
	var diag = new Dialog();
	diag.Top="50%";
	diag.Left="50%";
	diag.Width = 300;
	diag.Title = "生成邀请码";
	diag.InnerHtml= '<div style="text-align:left">'+invite_code+'</div>';
	diag.OKEvent = function(){window.location.href="modules.php?app=mypals_invite";diag.close();};
	diag.show();
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
          <li class="active"><a href="modules.php?app=mypals_invite" title="<?php echo $mp_langpackage->mp_invite;?>"><?php echo $mp_langpackage->mp_invite;?></a></li>
          <li><a href="modules.php?app=mypals_sort" title="<?php echo $mp_langpackage->mp_m_sort;?>"><?php echo $mp_langpackage->mp_m_sort;?></a></li>
      </ul>
  </div>
  
<?php if(!$inviteCode){?>
		<dl class="invite_info">
			<dt><strong><?php echo $mp_langpackage->mp_invite_more;?><?php echo $siteName;?>！</strong></dt>
			<dd><?php echo $mp_langpackage->mp_instant_ct_link;?></dd>
			<dd><a href="<?php echo $user_home_url;?>" target="_blank"><?php echo $user_home_url;?></a></dd>
			<dd><?php echo $mp_langpackage->mp_instant_ct_send;?></dd>
			<dd><strong><?php echo $mp_langpackage->mp_brief_invitation;?>:</strong></dd>
			<dd><textarea class="textarea" id="codeTxt" style="height:280px; overflow-x:hidden">
Hi，<?php echo $mp_langpackage->mp_i;?><?php echo filt_word(get_sess_username());?>，

<?php echo $siteName;?><?php echo $mp_langpackage->mp_invite_you;?>

<?php echo $mp_langpackage->mp_contact_me;?>

<?php echo $mp_langpackage->mp_invited_postscript;?>

<?php echo $mp_langpackage->mp_links_invite;?>
<?php echo $user_invite_url;?>

<?php echo $mp_langpackage->mp_if_you_have;?><?php echo $siteName;?><?php echo $mp_langpackage->mp_view_personal_homepage;?>
<?php echo $user_home_url;?>
			</textarea>
			</dd>
			<input type=button id="copy_start" value="<?php echo $mp_langpackage->mp_ctrlc_start;?>"></dd>
		</dl>
<?php }?>

<?php if($inviteCode){?>
<?php if($invite_rs){?>
<div class="rs_head">
    <span>
    	<?php echo $mp_langpackage->mp_select;?>：<a href="javascript:select_item(1);"><?php echo $mp_langpackage->mp_all;?></a> -
      <a href="javascript:select_item(0);"><?php echo $mp_langpackage->mp_cancel;?></a>
    </span>
    <span><a href="javascript:document.forms[0].onsubmit();"><?php echo $mp_langpackage->mp_delete;?></a></span>
</div>
<form action='modules.php?app=mypals_invite&del_code=1' method='post' onsubmit="check_form()">
<table class="msg_inbox">
	<tr>
        <td width="25"></td>
        <td>链接地址</td>
        <td>剩余时间</td>
        <td>操作</td>
    </tr>
	<?php foreach($invite_rs as $rs){?>
	<tr>
    <td width="25"><input name="attach[]" type="checkbox" value="<?php echo $rs['id'];?>" /></td>
		<td><input type='text' onclick='copy(this,{sMsg : "<?php echo $mp_langpackage->mp_ctrlc_success;?>", fMsg : "<?php echo $mp_langpackage->mp_ctrlc_failure;?>"})' onmouseover='this.style.background = "#ffffcc"' onmouseout='this.style.background = "#ffffff"' id='inviteTxt_<?php echo $rs['id'];?>' class='med-text' value="<?php echo $siteDomain;?>modules.php?app=user_reg&invite_code=<?php echo $rs['code_txt'];?>" /></td>
		<td><?php echo leave_time($rs['add_time'],$inviteCodeLife);?></td>
		<td><a href='modules.php?app=mypals_invite&del_code=1&id=<?php echo $rs['id'];?>'><img src="skin/<?php echo $skinUrl;?>/images/del.png" /></a></td>
	</tr>
	<?php }?>
</table>
</form>
<div class="rs_head">
    <span>
    	<?php echo $mp_langpackage->mp_select;?>：<a href="javascript:select_item(1);"><?php echo $mp_langpackage->mp_all;?></a> -
      <a href="javascript:select_item(0);"><?php echo $mp_langpackage->mp_cancel;?></a>
    </span>
    <span><a href="javascript:document.forms[0].onsubmit();"><?php echo $mp_langpackage->mp_delete;?></a></span>
</div>
<?php }?>
<input class="invite-btn" type='button' onclick=produce_invite_code(); value='<?php echo $mp_langpackage->mp_generate_invite;?>' />
<p class="gray"><?php echo $mp_langpackage->mp_invite_cost;?><?php echo $inviteCodeValue;?><?php echo $mp_langpackage->mp_integral;?></p>
<div class="rs_head"><?php echo $mp_langpackage->mp_surplus_integral;?>: <?php echo $intg;?></div>
<p class="tleft gray"><?php echo $mp_langpackage->mp_invitation_code_period;?><?php echo $inviteCodeLife;?><?php echo $mp_langpackage->mp_in_hours;?></p>
<?php }?>

<script type="text/javascript">
	if(document.getElementById("copy_start")){
		var copyStart=document.getElementById("copy_start");
		copyStart.onclick=function() {
			copy(document.getElementById("codeTxt"), {sMsg : "<?php echo $mp_langpackage->mp_ctrlc_success;?>", fMsg : "<?php echo $mp_langpackage->mp_ctrlc_failure;?>"});
		}
		copyStart.onmouseover = function(){
			this.style.background = "#ffffcc"
		}
		copyStart.onmouseout = function(){
			this.style.background = "#efefef"
		}
	}
</script>

</body>
</html>