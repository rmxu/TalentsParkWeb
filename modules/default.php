<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/default.html
 * 如果您的模型要进行修改，请修改 models/modules/default.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//�������԰�
$ah_langpackage=new arrayhomelp;
?><script src="skin/default/js/login.js" language="javascript"></script>
<div class="snsidea"><a href="modules.php?app=user_reg" hidefocus="true"><img src="skin/<?php echo $skinUrl;?>/images/sns_idea1.jpg" width="664" height="314" /></a></div>
  	<div class="login">
        <h2><?php echo $l_langpackage->l_momber_login;?> <span id="loadingmsg"></span></h2>
        <form name="login_form" method="post" onsubmit="return false;">
            <p><label><?php echo $l_langpackage->l_email;?>： <span id="emailmsg" class="red"></span></label><input class="input" name="login_email" id="login_email" type="text" /></p>
            <p><label><?php echo $l_langpackage->l_pass;?>： <span id="pwdmsg" class="red"></span></label><input class="pwd" name="login_pws" id="login_pws" type="password" /></p>
            <p class="chk">
                <label for="tmpiId">
                    <input name="tmpiId" class="chk" id="tmpiId" type="checkbox" value="save" checked="checked" onKeyDown="getEnt();">
                    <?php echo $l_langpackage->l_save_aco;?>
                </label>
                <label for="hidden">
                	<input name="hidden" class="chk" id="hidden" type="checkbox" value="1" onKeyDown="getEnt();">
                	<?php echo $l_langpackage->l_hid;?>
                </label>
            </p>
            <p><span><a href="modules.php?app=user_forget" class="forget"><?php echo $ah_langpackage->ah_forgot_password;?>？</a></span><input type="submit" onclick="login();" class="button"  name="loginsubm" id="loginsubm" hidefocus="true" value="<?php echo $l_langpackage->l_login;?>"></p>
        </form>
	<!--插件位置!-->
	<div class="index_right">
		
		<?php echo isset($plugins['index_right'])?show_plugins($plugins['index_right']):'';?>
	</div>
	<!--插件位置!-->
	</div>

</div>
<div class="clear"></div>
<div class="main recom_user">
	<div class="cont">
    	<div class="user_holder">
            <h2><?php echo $ah_langpackage->ah_total;?><?php echo $total_member;?><?php echo $ah_langpackage->ah_member_events_here;?></h2>
    <div class="left_part">
        <div id="MainPromotionBanner">
            <div id="SlidePlayer">
                <ul class="Slides">
                    <?php if(!empty($rec_rs1)){?>
                    <?php foreach($rec_rs1 as $val){?>
                    <li><a href="home.php?h=<?php echo $val['user_id'];?>" target="_blank"><img src="<?php echo $val['show_ico'];?>" alt="<?php echo $val['user_name'];?>" /></a></li>
                    <?php }?>
                    <?php }?>
                    <?php if(empty($rec_rs1)){?>
                    <li><a href="#"><img src="skin/<?php echo $skinUrl;?>/images/def.jpg" alt="" /></a></li>
                    <?php }?>
                </ul>
            </div>
        </div>

        <script type="text/javascript">
        TB.widget.SimpleSlide.decorate('SlidePlayer', {eventType:'mouse', effect:'scroll'});
        </script>
	</div>
    <div class="right_part">
        <?php foreach($user_rs as $val){?>
            <dl>
                <dt><a class="avatar" hidefocus="true" href="home.php?h=<?php echo $val['user_id'];?>" target="_blank"><img src="<?php echo $val['user_ico'];?>" alt="<?php echo $val['user_name'];?>" /></a></dt>
                <dd><a href="home.php?h=<?php echo $val['user_id'];?>" hidefocus="true" target="_blank"><?php echo sub_str($val['user_name'],5,true);?></a></dd>
                <dd class="time"><?php echo format_datetime_short($val['lastlogin_datetime']);?></dd>
            </dl>
        <?php }?>
    </div>

        </div>
        <div class="snsintro">
        	<dl>
                <dt class="space1"><?php echo $ah_langpackage->ah_personal_space;?></dt><?php echo $ah_langpackage->ah_personal_space_detail;?>
            </dl>
            <dl>
                <dt class="group2"><?php echo $ah_langpackage->ah_groups_share;?></dt><?php echo $ah_langpackage->ah_groups_share_detail;?>
            </dl>
            <dl>
                <dt class="game3"><?php echo $ah_langpackage->ah_game_application;?></dt><?php echo $ah_langpackage->ah_game_application_detail;?>
            </dl>
        </div>
	</div>
	<!--插件位置!-->
	<div class="index_newMember">
		
		<?php echo isset($plugins['index_newMember'])?show_plugins($plugins['index_newMembers']):'';?>
	</div>
	<!--插件位置!-->
<script language="javascript">
function goLogin(){
	Dialog.confirm("<?php echo $pu_langpackage->pu_login;?>",function(){top.location="<?php echo $indexFile;?>";});
}
function getEnt(){
	document.onkeydown = function (e){
		var theEvent = window.event || e;
		var code = theEvent.keyCode || theEvent.which;
		if(code == 13){
			  login();
		}
	}
}
function inputTxt(obj,act)
{
  var str="<?php echo $ah_langpackage->ah_enter_name;?>";
  if(obj.value==''&&act=='set')
  {
     obj.value=str;
     obj.style.color='#cccccc';
  }
  if(obj.value==str&&act=='clean')
  {
     obj.value='';
     obj.style.color='gray';
  }
}
//ajax
function login_callback(content)
{
	var check=/\.php/;
	if(check.test(content)){
		 if($("tmpiId").checked){
			saveTmpEmail(1);
		 }else{
			  saveTmpEmail(0);
		 }
		 window.location.href=content;
	}else{
		$("emailmsg").innerHTML = '';
		$("pwdmsg").innerHTML = '';
		$("loadingmsg").innerHTML = '';
		var return_array=content.split("|");
		$(return_array[0]).innerHTML = return_array[1];
	}
}
function login_unready_callback(){
	var argb_div = $("loadingmsg");
	if($("emailmsg").innerHTML == '' || $("pwdmsg").innerHTML == ''){
		argb_div.innerHTML='';
	}else{
		argb_div.innerHTML="<img src='skin/<?php echo $skinUrl;?>/images/login_loading.gif' align='top' ><?php echo $l_langpackage->l_loading;?>";
	}
}
function saveTmpEmail(para){
	var email_time=new Date();
	var login_time=new Date();
	email_time.setTime(email_time.getTime() +3600*1000*24*300 );
	login_time.setTime(login_time.getTime() +3600*250 );
	if(para==1){
		var uemailStr=$("login_email").value;
		document.cookie='iweb_email='+uemailStr+';expires='+ email_time.toGMTString();
	}
	document.cookie="IsReged=Y;expires="+ login_time.toGMTString();
}
function login(){
	u_email=$('login_email').value;
	u_pws=$('login_pws').value;
	u_hidden=0;
	if($('hidden').checked){
		u_hidden=1;
	}
	var ajax_login=new Ajax();
	ajax_login.getInfo("do.php?act=login","post","app","u_email="+u_email+"&u_pws="+u_pws+"&hidden="+u_hidden,function(c){login_callback(c);},function(){login_unready_callback();});
}
//取得cookie值
$('login_email').value=get_cookie('iweb_email');
</script>