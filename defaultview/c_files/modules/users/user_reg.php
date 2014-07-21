<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_reg.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_reg.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//语言包引入
$pu_langpackage=new publiclp;
$u_langpackage=new userslp;
$l_langpackage=new loginlp;

//引入语言包
$reg_langpackage=new reglp;
$regInfo_root="langpackage/".$langPackagePara."/regInfo.php";
$regInfo=file_get_contents($regInfo_root);
$regInfo=$regInfo=='' ?  "<center><b>".$reg_langpackage->re_none_ser."</b></center>":$regInfo;
$invite_code='';
//是否可以注册
$is_show=1;
if($allowReg==false){
	$is_show=0;
	$error_str=$u_langpackage->u_not_open_register;
}else{
	if($inviteCode==1){
		$invite_code=get_argg('invite_code');
		if(strlen($invite_code)==$inviteCodeLength){
			$t_invite_code=$tablePreStr."invite_code";
			$dbo=new dbex;
			dbtarget('r',$dbServs);
			$now_time=time();
			$left_time=$inviteCodeLife*60*60;
			$sql="delete from $t_invite_code where $now_time-add_time > $left_time";
			$dbo->exeUpdate($sql);
			
			$sql="select id from $t_invite_code where code_txt='$invite_code'";
			$is_check=$dbo->getRow($sql);
			if(empty($is_check)){
				$error_str=$u_langpackage->u_invite_incorrect_or_failed;
				$is_show=0;
			}else{
				$is_show=1;
			}
		}else{
			$is_show=0;
			$error_str=$u_langpackage->u_need_invite_register;
		}
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $siteName;?></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/layout.css">
<script language="javascript" src="servtools/ajax_client/ajax.js"></script>
<script language="javascript" src="skin/default/js/jooyea.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script>
</head>
<body>
<?php require('uiparts/guestheader.php');?>
<?php if($is_show==1){?>
<script language="javascript">
function ser_item(){
	var diag = new Dialog();
	diag.Top="50%";
	diag.Left="50%";
	diag.Title = "用户协议";
	diag.InnerHtml= '<div style="text-align:left"><?php echo $regInfo;?></div>';
	diag.OKEvent = function(){diag.close();};
	diag.show();
}

function goLogin(){
	Dialog.confirm("<?php echo $pu_langpackage->pu_login;?>",function(){top.location="<?php echo $indexFile;?>";});
}

function getVerCode(){
  $("verCodePic").src="servtools/veriCodes.php?vc="+Math.random();
}

function ajax_check(obj,type_id){
	if(type_id=='email'){
		div_value='user_email_message';
		suc_str='<?php echo $u_langpackage->u_reg_email_available;?>';
	}else{
		div_value='user_veriCode_message';
		suc_str='<?php echo $u_langpackage->u_reg_code_correct;?>';
	}
	var check=new Ajax();
	check.getInfo("do.php","get","app","act=reg&ajax=1&"+$(obj).id+"="+$(obj).value,function(c){if(c){refuse_submit(type_id,c);}else{pass_submit(type_id,suc_str);}});
}

function refuse_submit(type_id,c){
	if(type_id=='email'){
		user_email.className = 'ipt ipt_focus'
		user_email_message.style.color = 'red';
		user_email_message.innerHTML = c;
		user_email_status = false;
	}else{
		veriCode.className = 'ipt ipt_focus'
		veriCode_message.style.color = 'red';
		veriCode_message.innerHTML = c;
		user_veriCode_status = false;
	}
}

function pass_submit(type_id,c){
	if(type_id=='email'){
		user_email.className = 'ipt ipt_nomal';
		user_email_message.style.color = 'green';
		user_email_message.innerHTML = c;
		user_email_status = true;
	}else{
		veriCode.className = 'ipt ipt_nomal';
		veriCode_message.style.color = 'green';
		veriCode_message.innerHTML = c;
		user_veriCode_status = true;
	}
}
</script>
    <div class="reg_container">
			<ul class="regedit">
			<h2><?php echo $u_langpackage->u_reg_fast_registration;?></h2>
      <form action="javascript:void(0);" id="reg_form" name="reg_form" method="post" onSubmit="return checkForm();">
      	<input type='hidden' name='invite_code' value='<?php echo $invite_code;?>' />
        <li><label><?php echo $u_langpackage->u_reg_mailbox;?>：</label></li>
        <li>
            <input class="ipt ipt_nomal" name="user_email" id="user_email" />
            <div id="user_email_message" class="hint highlight"></div>
        </li>
        <li class="gray"><?php echo $u_langpackage->u_reg_common_mail;?>。</li>
        <li><label><?php echo $u_langpackage->u_uname;?>：</label></li>
        <li>
          <input class="ipt ipt_nomal" name="user_name" autocomplete="off" />
          <div id="user_name_message" class="hint"></div>
        </li>
        <li class="gray"><?php echo $u_langpackage->u_reg_username_not_modified;?>。</li>
        <li><label><?php echo $u_langpackage->u_reg_password;?>：</label></li>
        <li>
          <input class="ipt ipt_nomal" type="password" name="user_password" id="user_password" autocomplete="off" />
          <div id="user_password_message" class="hint"></div>
        </li>
        <li class="gray"><?php echo $u_langpackage->u_reg_6and20_symbols;?>。 </li>
        <li><label><?php echo $u_langpackage->u_reg_repassword;?>：</label></li>
        <li>
          <input class="ipt ipt_nomal" type="password" name="user_repassword" id="user_repassword" />
          <div id="user_repassword_message" class="hint highlight"></div>
        </li>
        <li class="gray"><?php echo $u_langpackage->u_reg_repeat_password;?></li> </li>
        <li><label><?php echo $u_langpackage->u_reg_sex;?>：</label>
          <input type='radio' value='1' style="vertical-align:middle" name='user_sex' /><?php echo $u_langpackage->u_man;?>
          <input type='radio' value='0' style="vertical-align:middle" name='user_sex' checked=checked /><?php echo $u_langpackage->u_wen;?>
          <div id="user_repassword_message" class="hint highlight"></div>
        </li>
        <li class="gray"><?php echo $u_langpackage->u_reg_sex_not_modified;?>。 </li>
        <li><label><?php echo $u_langpackage->u_reg_code;?>：</label></li>
        <li>
          <input class="ipt ipt_nomal" type="text" style="width:80px;" name="veriCode" id="veriCode" maxlength="5" onkeyup="javascript:if(this.value.length==5){ajax_check('veriCode','vocode')}" />
          <img border="0" src="servtools/veriCodes.php" height=20 style="margin:0 0 -3px 5px;" id="verCodePic" /> <a href="javascript:;" onclick='getVerCode();return false;'><?php echo $u_langpackage->u_not_see;?>？</a>
          <div id="user_veriCode_message" class="hint highlight"></div>
				</li>
        <li class="gray"><?php echo $u_langpackage->u_reg_photo_characters;?></li>
        <li><input class="button" type="submit" value="<?php echo $u_langpackage->u_reg_registration;?>" /><span><?php echo $u_langpackage->u_reg_accepted;?><a href="javascript:ser_item();"><?php echo $u_langpackage->u_reg_user_agreement;?></a><?php echo $u_langpackage->u_reg_and;?><?php echo $u_langpackage->u_reg_registration;?></span></li>  			
  		</form>
			</ul>
    </div>
    
<script language="JavaScript">
<!--
// 检测会员用户名
var user_name = document.getElementsByName('user_name')[0];
var user_name_message = $('user_name_message');
var user_name_status = false;
var user_name_reg = /^[A-Za-z0-9\u4E00-\u9FA5]*$/;	//用正则表达式/[\u4E00-\u9FA5]/表示中文
user_name.onmouseover = function(){user_name.className = 'ipt ipt_focus'};
user_name.onmouseout = function(){user_name.className = 'ipt ipt_nomal'};
user_name.onblur = function(){
	var user_name_size=check_code_size(user_name.value);
	if(user_name.value=='') {
		user_name.className = 'ipt ipt_focus'
		user_name_message.style.color = 'red';
		user_name_message.innerHTML = '* <?php echo $u_langpackage->u_reg_fill_username;?>';
		user_name_status = false;
	}else if(user_name_size < 4 || user_name_size > 14) {
		user_name.className = 'ipt ipt_error';
		user_name.onmouseout='ipt ipt_error';
		user_name_message.style.color = 'red';
		user_name_message.innerHTML = '* <?php echo $u_langpackage->u_reg_username_format_error;?>';
		user_name_status = false;
	} else if(!user_name_reg.test(user_name.value)){
		user_name.className = 'ipt ipt_error';
		user_name.onmouseout='ipt ipt_error';
		user_name_message.style.color = 'red';
		user_name_message.innerHTML = '*<?php echo $u_langpackage->u_special_characters_disable;?>';
		user_name_status = false;
	}else {
		user_name.className = 'ipt ipt_nomal';
		user_name_message.style.color = 'green';
		user_name_message.innerHTML = '<?php echo $u_langpackage->u_reg_username_available;?>';
		user_name_status = true;
	}
};

// 检测邮箱
var user_email = document.getElementsByName('user_email')[0];
var user_email_message = $('user_email_message');
var user_email_status = false;
var user_email_reg = /^[0-9a-zA-Z_\-\.]+@[0-9a-zA-Z_\-]+(\.[0-9a-zA-Z_\-]+)*$/;
user_email.onmouseover = function(){user_email.className = 'ipt ipt_focus'};
user_email.onmouseout = function(){user_email.className = 'ipt ipt_nomal'};
user_email.onblur = function(){
	if(user_email.value=='') {
		user_email.className = 'ipt ipt_focus'
		user_email_message.style.color = 'red';
		user_email_message.innerHTML = '* <?php echo $u_langpackage->u_reg_fill_email;?>';
		user_email_status = false;
	} else if(!user_email.value.match(user_email_reg)) {
		user_email.className = 'ipt ipt_error';
		user_email.onmouseout = 'ipt ipt_error'
		user_email_message.style.color = 'red';
		user_email_message.innerHTML = '* <?php echo $u_langpackage->u_reg_email_format_error;?>';
		user_email_status = false;
	} else {
		ajax_check(user_email,'email');
	}
};


// 检测密码
var user_password = document.getElementsByName('user_password')[0];
var user_password_message = $('user_password_message');
var user_password_status = false;
user_password.onmouseover = function(){user_password.className = 'ipt ipt_focus'};
user_password.onmouseout = function(){user_password.className = 'ipt ipt_nomal'};
user_password.onblur = function(){
	if(user_password.value=='') {
		user_password.className = 'ipt ipt_focus'
		user_password_message.style.color = 'red';
		user_password_message.innerHTML = '* <?php echo $u_langpackage->u_reg_fill_password;?>';
		user_password_status = false;
	} else if(user_password.value.length<6 || user_password.value.length>16) {
		user_password.className = 'ipt ipt_error'
		user_password.onmouseout='ipt ipt_error';
		user_password_message.style.color = 'red';
		user_password_message.innerHTML = '* <?php echo $u_langpackage->u_reg_password_format_error;?>';
		user_password_status = false;
	} else {
		user_password.className = 'ipt ipt_nomal';
		user_password_message.style.color = 'green';
		user_password_message.innerHTML = '<?php echo $u_langpackage->u_reg_password_format_correct;?>';
		user_password_status = true;
	}
};


// 检测确认密码
var user_repassword = document.getElementsByName('user_repassword')[0];
var user_repassword_message = $('user_repassword_message');
var user_repassword_status = false;
user_repassword.onmouseover = function(){user_repassword.className = 'ipt ipt_focus'};
user_repassword.onmouseout = function(){user_repassword.className = 'ipt ipt_nomal'};
user_repassword.onblur = function(){
	if(user_repassword.value=='') {
		user_repassword.className = 'ipt ipt_focus'
		user_repassword_message.style.color = 'red';
		user_repassword_message.innerHTML = '* <?php echo $u_langpackage->u_reg_confirm_password;?>';
		user_repassword_status = false;
	} else if(user_repassword.value!=user_password.value) {
		user_repassword.className = 'ipt ipt_error'
		user_repassword.onmouseout='ipt ipt_error';
		user_repassword_message.style.color = 'red';
		user_repassword_message.innerHTML = '* <?php echo $u_langpackage->u_reg_password_inconsistent;?>';
		user_repassword_status = false;
	} else if(user_repassword.value.length<6 || user_repassword.value.length>16) {
		user_repassword.className = 'ipt ipt_error'
		user_repassword.onmouseout='ipt ipt_error';
		user_repassword_message.style.color = 'red';
		user_repassword_message.innerHTML = '* <?php echo $u_langpackage->u_reg_password_format_error;?>';
		user_repassword_status = false;
	} else {
		user_repassword.className = 'ipt ipt_nomal'
		user_repassword_message.style.color = 'green';
		user_repassword_message.innerHTML = '<?php echo $u_langpackage->u_reg_cpassword_format_correct;?>';
		user_repassword_status = true;
	}
};

//检测验证码
	var veriCode = document.getElementsByName('veriCode')[0];
	var veriCode_message = document.getElementById('user_veriCode_message');
	veriCode.onmouseover = function(){veriCode.className = 'ipt ipt_focus'};
	veriCode.onmouseout = function(){veriCode.className = 'ipt ipt_nomal'};
	veriCode.onblur = function(){
		if(veriCode.value=='') {
			veriCode.className = 'ipt ipt_focus'
			veriCode_message.style.color = 'red';
			veriCode_message.innerHTML = '* <?php echo $u_langpackage->u_reg_fill_code;?>';
			user_veriCode_status = false;
		}else {
			ajax_check('veriCode','veriCode');
		}
	};

function checkForm(){
	user_name.onblur();
	user_email.onblur();
	user_password.onblur();
	user_repassword.onblur();
	veriCode.onblur();
	if(user_name_status && user_email_status && user_password_status && user_repassword_status && user_veriCode_status) {
		$("reg_form").action="do.php?act=reg";
		return true;
	} else {
		return false;
	}
}
</script>
<?php }?>

<?php if($is_show==0){?>
<div class="error_box reg_error">
  <h2><?php echo $error_str;?></h2>
  <p><?php echo $ah_langpackage->ah_system_will;?><span id="skip">5</span><?php echo $ah_langpackage->ah_seconds_return;?></p>
  <p><a href="<?php echo $siteDomain;?><?php echo $indexFile;?>" title="<?php echo $ah_langpackage->ah_click_return_home;?>"><?php echo $ah_langpackage->ah_click_return_home;?>&gt;&gt;</a></p>
</div>
<script type='text/javascript'>
function countDown(secs,surl){
if($("skip")){
	$("skip").innerHTML=secs;
	--secs>0?setTimeout("countDown("+secs+",'"+surl+"')",1000):location.href=surl;
	}
}
countDown(5,'<?php echo $siteDomain;?><?php echo $indexFile;?>');
</script>
<?php }?>
<?php require('uiparts/footor.php');?>

</body>
</html>