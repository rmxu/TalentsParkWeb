<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/home.html
 * 如果您的模型要进行修改，请修改 models/home.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
header("content-type:text/html;charset=utf-8");
require("foundation/asession.php");
require("configuration.php");
require("includes.php");

//引入公共方法
require("foundation/fcontent_format.php");
require("foundation/module_mood.php");
require("foundation/module_users.php");
require("foundation/fplugin.php");
require("foundation/fgrade.php");
require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;
	$pu_langpackage=new publiclp;
	$s_langpackage=new sharelp;
	$mn_langpackage=new menulp;
	$hi_langpackage=new hilp;
	$mo_langpackage=new moodlp;
	$pr_langpackage=new privacylp;
	$ah_langpackage=new arrayhomelp;

	//变量获得
	$holder_id=intval(get_argg('h'));//主人id
	$user_id =get_sess_userid();
	$dress_name=short_check(get_argg('dress_name'));//装扮名称

	//表声明区
	$t_mood=$tablePreStr."mood";
	$t_users=$tablePreStr."users";
	$t_online=$tablePreStr."online";

	//获取并重写url参数
	$urlParaStr=getReUrl();

	//取得主人信息
	$user_info=$holder_id ? api_proxy("user_self_by_uid","*",$holder_id):array();
	$holder_name=empty($user_info) ? '':$user_info['user_name'];
	$is_self=($holder_id==$user_id) ? 'Y':'N';

	//隐私显示控制
	$show_error=false;
	$show_ques=false;
	$is_visible=0;
	$show_info="";
	$dbo = new dbex;
	dbtarget('r',$dbServs);
	if($user_info){
	  //最后更新心情
		$last_mood_rs=get_last_mood($dbo,$t_mood,$holder_id);
		$last_mood_txt='';
		if($last_mood_rs['mood']){
			$last_mood_txt=get_face($last_mood_rs['mood']);
			$last_mood_time=format_datetime_short($last_mood_rs['add_time']);
		}else{
			$last_mood_txt=$mo_langpackage->mo_null_txt;
			$last_mood_time='';
		}
		//主人姓名
		set_session($holder_id.'_holder_name',$user_info['user_name']);
		$user_online=array();
		
		//登录状态
		$ol_state_ico="skin/$skinUrl/images/online.gif";
		$ol_state_label=$ah_langpackage->ah_current_online;
		$timer_txt='';
		$user_online=get_user_online_state($dbo,$t_online,$holder_id);
		if($is_self=='N'&&(empty($user_online)||$user_online['hidden']==1)){
		  $ol_state_ico="skin/$skinUrl/images/offline.gif";
		  $ol_state_label=$ah_langpackage->ah_offline;
		  $timer_txt='('.format_datetime_short($user_info['lastlogin_datetime']).')';
		}else if($is_self=='Y' && $user_online['hidden']==1){
			$ol_state_ico="skin/$skinUrl/images/hiddenline.gif";
			$ol_state_label=$ah_langpackage->ah_stealth;
		}

		$is_admin=get_sess_admin();
		if($is_admin==''&&$is_self=='N'){
			if($user_info['is_pass']==0){
				$show_error=true;$show_info=$pu_langpackage->pu_lock;
			}elseif($user_info['access_limit']==1 && $user_id==''){
				$show_error=true;$show_info=$pr_langpackage->pr_acc_false;
			}elseif($user_info['access_limit']==2 && !api_proxy("pals_self_isset",$holder_id)){
				$show_error=true;$show_info=$pr_langpackage->pr_acc_false;
			}elseif($user_info['access_limit']==3 && get_session($holder_id.'homeAccessPass')!='1'){
				$show_ques=true;
			}else{
				$is_visible=1;
			}
		}else{
			$is_visible=1;
		}
	}else{
		$show_error=true;$show_info=$pu_langpackage->pu_no_user;
	}

	if($user_id){
		$inc_header="uiparts/homeheader.php";
	}else{
		$inc_header="uiparts/guestheader.php";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="Content-Language" content="zh-cn"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Description" content="<?php echo $metaDesc;?>,<?php echo $holder_name;?>" /><meta name="Keywords" content="<?php echo $metaKeys;?>,<?php echo $holder_name;?>" /><meta name="author" content="<?php echo $holder_name;?>" /><meta name="robots" content="all" /><title><?php echo $holder_name;?>的个人主页-<?php echo $siteName;?></title><base href='<?php echo $siteDomain;?>' /><?php $plugins=unserialize('a:0:{}');?><link rel="stylesheet" href="skin/<?php echo $skinUrl;?>/css/layout.css" /><script type='text/javascript' src="skin/default/js/jooyea.js"></script><script type='text/javascript' src="skin/default/js/jy.js"></script><SCRIPT type='text/javascript' src="servtools/ajax_client/ajax.js"></SCRIPT><script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script><script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script><script type="text/javascript" language="javascript" src="servtools/calendar.js"></script><script type='text/javascript'>function goLogin(){	Dialog.confirm("<?php echo $pu_langpackage->pu_login;?>",function(){top.location="<?php echo $siteDomain;?><?php echo $indexFile;?>";});}	//取得评论内容	function get_mod_com(type_id,mod_id,start_num,end_num){		if(frame_content.$("max_"+type_id+"_"+mod_id)){			var max_num=parseInt(frame_content.$("max_"+type_id+"_"+mod_id).innerHTML);			start_num=max_num;		}		var ajax_get_com=new Ajax();		ajax_get_com.getInfo("modules.php","GET","app","app=restore&mod_id="+mod_id+"&type_id="+type_id+"&start_num="+start_num+"&end_num="+end_num,function(c){get_com_callback(c,type_id,mod_id);});	}	//回复评论	function restore(user_name,type_id,mod_id,user_id){		if(parseInt(<?php echo $user_id;?>)){			if(frame_content.$("replycontent_"+type_id+"_"+mod_id)){				frame_content.toggle2("reply_"+type_id+"_"+mod_id);			}			$('restore').value=user_id;			frame_content.$('reply_'+type_id+'_'+mod_id+'_input').value='<?php echo $ah_langpackage->ah_reply;?>'+user_name+":";		}else{			goLogin();		}	}	//回复评论	function restore_com(holder_id,type_id,mod_id){		var r_content=frame_content.$('reply_'+type_id+'_'+mod_id+'_input').value;		var user_id='';		if($('restore').value!=''){			var user_id=$('restore').value;		}		//var is_hidden=frame_content.document.getElementById('hidden_'+type_id+'_'+mod_id).value;		var is_hidden=0;		if(trim(r_content)==''){			Dialog.alert('<?php echo $pu_langpackage->pu_data_empty;?>');		}else{			var ajax_comment=new Ajax();			ajax_comment.getInfo("do.php?act=restore_add&holder_id="+holder_id+"&type_id="+type_id+"&mod_id="+mod_id+"&is_hidden="+is_hidden+"&to_userid="+user_id,"post","app","CONTENT="+r_content,function(c){restore_com_callback(c,type_id,mod_id)});		}	}	//删除评论	function del_com(holder_id,type_id,parent_id,com_id,sendor_id){		var ajax_del_com=new Ajax();		ajax_del_com.getInfo("do.php","GET","app","act=restore_del&holder_id="+holder_id+"&type_id="+type_id+"&com_id="+com_id+"&sendor_id="+sendor_id+"&parent_id="+parent_id,function(c){del_com_callback(c,type_id,parent_id,com_id)});	}	//举报	function report(type_id,user_id,mod_id){		var diag = new Dialog();		diag.Width = 300;		diag.Height = 150;		diag.Top="50%";		diag.Left="50%";		diag.Title = "<?php echo $pu_langpackage->pu_report;?>";		diag.InnerHtml= '<div class="report_notice"><?php echo $pu_langpackage->pu_report_info;?><?php echo $pu_langpackage->pu_report_re;?><textarea id="reason"></textarea></div>';		diag.OKEvent = function(){act_report(type_id, user_id, mod_id);diag.close();};		diag.show();	}	//确认举报	function act_report(type_id,user_id,mod_id){		var reason_str=$("reason").value;		if(trim(reason_str)){			var ajax_act_report=new Ajax();			ajax_act_report.getInfo("do.php?act=report_add&type="+type_id+"&uid="+user_id+"&mod_id="+mod_id,"post","app","reason="+reason_str,function(c){act_report_callback(c)});		}else{			Dialog.alert('<?php echo $pu_langpackage->pu_report_none;?>');		}	}	//分享	function show_share(share_type,share_content_id,s_title,s_link){		var diag = new Dialog();		diag.Width = 300;		diag.Height = 180;		diag.Top="50%";		diag.Left="50%";		diag.Title = "<?php echo $s_langpackage->s_share;?>";		diag.InnerHtml= '<div class="share"><?php echo $s_langpackage->s_title;?><input maxlength="50" type="text" ' + (share_type<5 || share_type==8 ? 'disabled="disabled"' : '') + ' id="out_title" value="'+s_title+'" /><?php echo $ah_langpackage->ah_label;?>：<input type="text" id="tag" name="tag" value="" /><?php echo $s_langpackage->s_add_com;?><textarea id="share_com"></textarea></div>';		diag.OKEvent = function(){act_share(share_type,share_content_id,s_title,s_link);diag.close();};		diag.show();	}	//确认分享	function act_share(share_type,share_content_id,title_data,re_link){		if(share_type==5||share_type==6||share_type==7){			var out_title=$("out_title").value;			var title_data=trim(out_title);		}		var com_str=$("share_com").value;		var tag=$("tag").value;		var ajax_act_share=new Ajax();		ajax_act_share.getInfo("do.php?act=share_action&s_type="+share_type+"&share_content_id="+share_content_id,"post","app","comment="+com_str+"&title_data="+title_data+"&re_link="+re_link+"&tag="+tag,function(c){act_share_callback(c,share_type)});	}	//主页装扮提示	function dress_home(dress_name){		Dialog.confirm('<?php echo $ah_langpackage->ah_enable_dress;?>',function(){top.location.href="do.php?act=user_dress_change&dress_name="+dress_name;},function(){top.location.href="main.php?app=user_dressup";});	}</script></head><body id="home"><!--个人主页装扮!--><?php echo get_dressup($dbo,$t_users,$holder_id,"home",$dress_name);?><!--head_start!--><?php require($inc_header);?><!--head_end!--><!--隐私权限-start!--><?php if($show_error==true){?><div class="error_box">  <h2><?php echo $show_info;?></h2>  <p><?php echo $ah_langpackage->ah_system_will;?><span id="skip">5</span><?php echo $ah_langpackage->ah_seconds_return;?></p>  <p><a href="<?php echo $siteDomain;?><?php echo $indexFile;?>" title="<?php echo $ah_langpackage->ah_click_return_home;?>"><?php echo $ah_langpackage->ah_click_return_home;?>&gt;&gt;</a></p></div><?php }?><?php if($show_ques==true){?><div class="question_box">	<form name='acform' method="post" action="do.php?act=pr_access_login&holder_id=<?php echo $holder_id;?>">	  <h2><?php echo $pr_langpackage->pr_ans;?></h2>	  <p><?php echo $pr_langpackage->pr_select_que;?>：<?php echo pri_ques($user_info['access_questions'],$user_info['access_answers'],$holder_id);?></p>	  <p><?php echo $pr_langpackage->pr_write_ans;?>：<input name="answer" type="text" /></p>	  <p><input class="button" name="" type="submit" value="<?php echo $pr_langpackage->pr_button_action;?>" /></p>	</form></div><?php }?><!--隐私权限-end!--><input type="hidden" id="restore" value="" /><?php if($is_visible==1){?><!--home_start!--><div class="home_start"><div class="user_box">	<div class="user_status">        <div class="user_content">            <h1><?php echo $user_info['user_name'];?></h1><span title="<?php echo count_level($user_info['integral']);?>"><?php echo grade($user_info['integral']);?></span>            <span class="count">(<?php echo $ah_langpackage->ah_have;?><?php echo $user_info['guest_num'];?><?php echo $ah_langpackage->ah_had_seen;?>)</span>            <span class="myword"><?php echo $last_mood_txt;?></span>            <span class="time"><?php echo $last_mood_time;?></span><span class="time">|</span>            <span class="time"><a href="javascript:;" onclick="frame_content.location.href='modules.php?app=mood_more&user_id=<?php echo $holder_id;?>'"><?php echo $ah_langpackage->ah_more_mood;?></a></span>        </div>	</div></div><div class="clear"></div><div id="home_tabs" class="tabs">  <ul class="menu">	  <li class="active"><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=hstart&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_personal_homepage;?></a></li>	  <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=user_info&user_id=<?php echo $holder_id;?>&single=1';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_data;?></a></li>	  <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=blog_list&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_log;?></a></li>	  <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=album&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_album;?></a></li>	  <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=share_list&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_share;?></a></li>    <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=poll_mine&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_vote;?></a></li>    <li><a href="javascript:void(0);" onclick="frame_content.location.href='<?php echo $siteDomain;?>modules.php?app=group&user_id=<?php echo $holder_id;?>';return false;" hidefocus="true"><?php echo $ah_langpackage->ah_groups;?></a></li>  </ul></div><div class="main">	<!--homeleft_start!-->	<?php require("uiparts/homeleft.php");?>	<!--homeleft_end!-->  <div id="mainpart" class="mainpart">  	<div id="separator" class="switch"></div>    <div id="sidebar" class="sidebar">			<!--guest_start!-->    	<div class="sideitem">      	<div class="container">          <div class="sideitem_head"><h4><?php echo $ah_langpackage->ah_visitors;?></h4><a href="modules.php?app=guest_more&user_id=<?php echo $holder_id;?>" target="frame_content"><?php echo $ah_langpackage->ah_more;?></a></div>          <div class="sideitem_body">            <ul class="userlist" id="current_guest">            </ul>          </div>        </div>      </div>			<!--guest_end!-->			<!--plugins!-->			<div class="home_right_guest">				<?php echo isset($plugins['home_right_guest']) ? show_plugins($plugins['home_right_guest']):'';?>			</div>			<!--plugins!-->			<!--friend_start!-->			<div class="sideitem last">				<div class="container">				<div class="sideitem_head"><h4><?php echo $ah_langpackage->ah_friends_circle;?></h4><a href="modules.php?app=friend_all&user_id=<?php echo $holder_id;?>" target='frame_content'><?php echo $ah_langpackage->ah_more;?></a></div>					<div class="sideitem_body">						<ul class="userlist" id="friend_list">						</ul>					</div>				</div>			</div>			<!--friend_end!-->			<!--plugins!-->			<div class="home_right_friend">				<?php echo isset($plugins['home_right_friend'])?show_plugins($plugins['home_right_friend']):'';?>			</div>			<!--plugins!-->    </div>    <div id="bigpart" class="bigpart">			<iframe id="refresh" name="refresh" id="refresh" src="modules.php?app=refresh" scrolling="no" frameborder="0" height="0" width="0"></iframe>			<iframe onload="this.height=frame_content.document.body.scrollHeight" id="frame_content" name="frame_content"  src="modules.php<?php echo $urlParaStr;?>" scrolling="no" frameborder="0" width="100%" allowTransparency="true"></iframe>    </div>  </div></div><script type='text/javascript'>	function set_cur_guest(){		var ajax_guest=new Ajax();		ajax_guest.getInfo("modules.php","GET","app","app=guest&user_id=<?php echo $holder_id;?>",function(c){$("current_guest").innerHTML=trim(c);set_friend_list();});	}	function set_friend_list(){		var ajax_friend=new Ajax();		ajax_friend.getInfo("modules.php","GET","app","app=friend&user_id=<?php echo $holder_id;?>",function (c){$("friend_list").innerHTML=trim(c);});	}	if($("current_guest")){		set_cur_guest();	}</script><script language=JavaScript src="servtools/ajax_client/auto_ajax.js"></script><?php }?><?php if($is_visible==0){?><script type='text/javascript'>function countDown(secs,surl){	if($('skip')){	  $("skip").innerHTML=secs;	  --secs > 0 ? setTimeout("countDown("+secs+",'"+surl+"')",1000):location.href=surl;	}}countDown(5,'<?php echo $siteDomain;?><?php echo $indexFile;?>');</script><?php }?><?php require("uiparts/footor.php");?></div></body></html>