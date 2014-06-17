<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/uiparts/homeleft.html
 * 如果您的模型要进行修改，请修改 models/uiparts/homeleft.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$mn_langpackage=new menulp;
	$u_langpackage=new userslp;
	$ah_langpackage=new arrayhomelp;
	
	$send_msgscrip='modules.php?app=msg_creator&2id='.$holder_id.'&nw=2';
	$add_friend="javascript:mypalsAddInit($holder_id)";
	$leave_word='modules.php?app=msgboard_more&user_id='.$holder_id;
	$send_hi="hi_action($holder_id)";
	$send_report="report_action(10,$holder_id,$holder_id);";
	if(!isset($user_id)){
	  	$send_msgscrip="javascript:parent.goLogin();";
	  	$add_friend='javascript:parent.goLogin()';
	  	$leave_word="javascript:parent.goLogin();";
	  	$send_hi='javascript:parent.goLogin()';
	  	$send_report='javascript:parent.goLogin()';
	  	set_session('pre_login_url',$_SERVER["REQUEST_URI"]);
	}
?><script type='text/javascript' language="javascript">
function goLogin(){
	Dialog.confirm("<?php echo $pu_langpackage->pu_login;?>",function(){top.location="<?php echo $indexFile;?>";});
}

function mypalsAddInit(other_id)
{
	  var mypals_add=new Ajax();
	  mypals_add.getInfo("do.php","GET","app","act=add_mypals&other_id="+other_id,function(c){if(c=="success"){Dialog.alert("<?php echo $ah_langpackage->ah_friends_add_suc;?>");}else{Dialog.alert(c);}});
}
function hi_action_int(){
	<?php echo $send_hi;?>;
}

function report_action_int(){
	<?php echo $send_report;?>
}

function report_action(type_id,user_id,mod_id){
	var diag = new Dialog();
	diag.Width = 300;
	diag.Height = 150;
	diag.Top="50%";
	diag.Left="50%";
	diag.Title = "<?php echo $pu_langpackage->pu_report;?>";
	diag.InnerHtml= '<div class="report_notice"><?php echo $pu_langpackage->pu_report_info;?><?php echo $pu_langpackage->pu_report_re;?><textarea id="reason"></textarea></div>';
	diag.OKEvent = function(){act_report(type_id, user_id, mod_id);diag.close();};
	diag.show();
}

function hi_action(uid){
	var diag = new Dialog();
	diag.Width = 330;
	diag.Height = 150;
	diag.Top="50%";
	diag.Left="50%";
	diag.Title = "<?php echo $u_langpackage->u_choose_type;?>";
	diag.InnerHtml= '<?php echo hi_window();?>';
	diag.OKEvent = function(){send_hi(uid);diag.close();};
	diag.show();
}

function send_hi_callback(content){
	if(content=="success"){
		Dialog.alert("<?php echo $hi_langpackage->hi_success;?>");
	}else{
		Dialog.alert(content);
	}
}

function send_hi(uid){
	var hi_type=document.getElementsByName("hi_type");
	for(def=0;def<hi_type.length;def++){
		if(hi_type[def].checked==true){
			var hi_t=hi_type[def].value;
		}
	}
	var get_album=new Ajax();
	get_album.getInfo("do.php","get","app","act=user_add_hi&to_userid="+uid+"&hi_t="+hi_t,function(c){send_hi_callback(c);});
}
</script>
<div class="homeside">
	<div class="usershow">
		<a class="figure"><img src="<?php echo str_replace("_small","",$user_info['user_ico']);?>" /></a>
		<?php if($is_self=='N'){?><a href="<?php echo $add_friend;?>" class="addfriend"><?php echo $ah_langpackage->ah_add_friend;?></a><?php }?>
		<span class="userstatus"><img src=<?php echo $ol_state_ico;?> /><?php echo $ol_state_label;?><?php echo $timer_txt;?></span>
	</div>
	<?php if($is_self=='N'){?>
	<div class="handle">
		<ul>
      <li><a href="javascript:void(0);" onclick="hi_action_int();"><img src="skin/<?php echo $skinUrl;?>/images/hi.gif" /><?php echo $ah_langpackage->ah_say_hello_to;?></a></li>
      <li><a href="<?php echo $send_msgscrip;?>" target="frame_content"><img src="skin/<?php echo $skinUrl;?>/images/mail.gif" /><?php echo $ah_langpackage->ah_send_letter;?></a></li>
      <li><a href="javascript:void(0);" onclick="report_action_int();"><img src="skin/<?php echo $skinUrl;?>/images/police.gif" /><?php echo $ah_langpackage->ah_report_user;?></a></li>
		</ul>
	</div>
	<?php }?>

	<div class="userinfo">
		<dl>
			<dt><?php echo $ah_langpackage->ah_basic_info;?></dt>
			<dd><?php echo $ah_langpackage->ah_residence;?>：<span><?php echo $user_info["reside_province"]?($user_info["reside_province"]==$user_info["reside_city"]?$user_info["reside_province"]:$user_info["reside_province"].$user_info["reside_city"]):$u_langpackage->u_set;?></span></dd>
      <dd><?php echo $ah_langpackage->ah_birthday;?>：<span><?php echo $user_info["birth_year"]&&$user_info["birth_month"]&&$user_info["birth_day"]?$user_info["birth_year"].$u_langpackage->u_year.$user_info["birth_month"].$u_langpackage->u_month.$user_info["birth_day"].$u_langpackage->u_day:$u_langpackage->u_set;?></span></dd>
			<dd><?php echo $ah_langpackage->ah_hometown;?>：<span><?php echo $user_info["birth_province"]?($user_info["birth_province"]==$user_info["birth_city"]?$user_info["birth_province"]:$user_info["birth_province"].$user_info["birth_city"]):$u_langpackage->u_set;?></span></dd>
		</dl>
	</div>
	<!-- plugins !-->
	<div class='home_info_bottom'>
		<?php echo isset($plugins['home_info_bottom'])?show_plugins($plugins['home_info_bottom']):'';?>
	</div>
	<!-- plugins !-->	
</div>