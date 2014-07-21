<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/main.html
 * 如果您的模型要进行修改，请修改 models/main.php
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
//必须登录才能浏览该页面
require("foundation/auser_mustlogin.php");
require("foundation/module_users.php");
require("foundation/fplugin.php");
//语言包引入
$u_langpackage=new userslp;
$mn_langpackage=new menulp;
$pu_langpackage=new publiclp;
$mp_langpackage=new mypalslp;
$s_langpackage=new sharelp;
$hi_langpackage=new hilp;
$l_langpackage=new loginlp;
$rp_langpackage=new reportlp;
$ah_langpackage=new arrayhomelp;

$user_id=get_sess_userid();
$user_name=get_sess_username();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $user_name;?>的个人首页-<?php echo $siteName;?></title>
<base href='<?php echo $siteDomain;?>' />
<?php $plugins=unserialize('a:0:{}');?>
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/layout.css">
<script type="text/javascript" src="skin/default/js/jooyea.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script>
<script type="text/javascript" language="javascript" src="servtools/calendar.js"></script>
<script type="text/javascript" src="servtools/ajax_client/ajax.js"></script>
<script type="text/javascript" language="javascript" src="skin/default/js/jy.js"></script>
<script type="text/javascript" language="javascript" src="skin/default/js/iframeautoh.js"></script>
<script type='text/javascript'>
function goLogin(){
	Dialog.confirm("<?php echo $pu_langpackage->pu_login;?>",function(){top.location="<?php echo $indexFile;?>";});
}
//取得评论内容
	function get_mod_com(type_id,mod_id,start_num,end_num){
		if(frame_content.$("max_"+type_id+"_"+mod_id)){
			var max_num=parseInt(frame_content.$("max_"+type_id+"_"+mod_id).innerHTML);
			start_num=max_num;
		}
		var ajax_get_com=new Ajax();
		ajax_get_com.getInfo("modules.php","GET","app","app=restore&mod_id="+mod_id+"&type_id="+type_id+"&start_num="+start_num+"&end_num="+end_num,function(c){get_com_callback(c,type_id,mod_id)});
	}
	//回复评论
	function restore(user_name,type_id,mod_id,user_id){
		if(parseInt(<?php echo $user_id;?>)){
			if(frame_content.$("replycontent_"+type_id+"_"+mod_id)){
				frame_content.toggle2("reply_"+type_id+"_"+mod_id);
			}
			$('restore').value=user_id;
			frame_content.$('reply_'+type_id+'_'+mod_id+'_input').value='<?php echo $ah_langpackage->ah_reply;?>'+user_name+":";
		}else{
			goLogin();
		}
	}
	//回复评论
	function restore_com(holder_id,type_id,mod_id){
		var r_content=frame_content.$('reply_'+type_id+'_'+mod_id+'_input').value;
		var user_id='';
		if($('restore').value!=''){
			var user_id=$('restore').value;
		}
		//var is_hidden=frame_content.document.getElementById('hidden_'+type_id+'_'+mod_id).value;
		var is_hidden=0;
		if(trim(r_content)==''){
			Dialog.alert('<?php echo $pu_langpackage->pu_data_empty;?>');
		}else{
			var ajax_comment=new Ajax();
			ajax_comment.getInfo("do.php?act=restore_add&holder_id="+holder_id+"&type_id="+type_id+"&mod_id="+mod_id+"&is_hidden="+is_hidden+"&to_userid="+user_id,"post","app","CONTENT="+r_content,function(c){restore_com_callback(c,type_id,mod_id)});
		}
	}
	//删除评论
	function del_com(holder_id,type_id,parent_id,com_id,sendor_id){
		var ajax_del_com=new Ajax();
		ajax_del_com.getInfo("do.php","GET","app","act=restore_del&holder_id="+holder_id+"&type_id="+type_id+"&com_id="+com_id+"&sendor_id="+sendor_id+"&parent_id="+parent_id,function(c){del_com_callback(c,type_id,parent_id,com_id)});
	}

	//确认分享
	function act_share(share_type,share_content_id,title_data,re_link){
		if(share_type==5||share_type==6||share_type==7){
			var out_title=$("out_title").value;
			var title_data=trim(out_title);
		}
		var com_str=$("share_com").value;
		var tag=$("tag").value;
		var ajax_act_share=new Ajax();
		ajax_act_share.getInfo("do.php?act=share_action&s_type="+share_type+"&share_content_id="+share_content_id,"post","app","comment="+com_str+"&title_data="+title_data+"&re_link="+re_link+"&tag="+tag,function(c){act_share_callback(c,share_type)});
	}

	//举报
	function report(type_id,user_id,mod_id){
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
	//确认举报
	function act_report(type_id,user_id,mod_id){
		var reason_str=$("reason").value;
		if(trim(reason_str)){
			var ajax_act_report=new Ajax();
			ajax_act_report.getInfo("do.php?act=report_add&type="+type_id+"&uid="+user_id+"&mod_id="+mod_id,"post","app","reason="+reason_str,function(c){act_report_callback(c)});
		}else{
			Dialog.alert('<?php echo $pu_langpackage->pu_report_none;?>');
		}
	}
	//分享
	function show_share(share_type,share_content_id,s_title,s_link){
		var diag = new Dialog();
		diag.Width = 300;
		diag.Height = 420;
		diag.Top="50%";
		diag.Left="50%";
		diag.Title = "<?php echo $s_langpackage->s_share;?>";
		diag.InnerHtml= '<div class="share"><?php echo $s_langpackage->s_title;?><input maxlength="50" type="text" ' + (share_type<5 || share_type==8 ? 'disabled="disabled"' : '') + ' id="out_title" value="'+s_title+'" /><?php echo $ah_langpackage->ah_label;?>：<input type="text" id="tag" name="tag" value="" /><?php echo $s_langpackage->s_add_com;?><textarea id="share_com"></textarea></div>';
		diag.OKEvent = function(){act_share(share_type,share_content_id,s_title,s_link);diag.close();};
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

//添加好友	
function mypalsAddInit(other_id)
{
	  var mypals_add=new Ajax();
	  mypals_add.getInfo("do.php","GET","app","act=add_mypals&other_id="+other_id,function(c){if(c=="success"){Dialog.alert("<?php echo $ah_langpackage->ah_friends_add_suc;?>");}else{Dialog.alert(c);}});
}
</script>
</head>
<body>
<input type="hidden" value="" id="restore" />
<?php require("uiparts/mainheader.php");?>
<div class="main">
<?php require("uiparts/mainleft.php");?>
<div id="mainpart" class="mainpart">
    <div id="separator" class="switch"></div>
    <div id="sidebar" class="sidebar">
        <iframe style="width:200px;padding:0px;margin:0px;" onload="this.height=0" id="remind" name="remind" src="modules.php?app=remind" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
        <div class="sideitem">
            <div class="container">
                <div class="sideitem_head">
                    <h4><?php echo $mn_langpackage->mn_visi;?></h4>
                    <a href="modules.php?app=guest_more&user_id=<?php echo $user_id;?>" target='frame_content'><?php echo $mp_langpackage->mp_more;?></a>
                </div>
                <div class="sideitem_body">
                	<ul id='current_guest' class="userlist"></ul>
                </div>
            </div>
        </div>
        <!--plugins!-->
        <div class="main_right_guest">
            <?php echo isset($plugins['main_right_guest'])?show_plugins($plugins['main_right_guest']):'';?>
        </div>
        <!--plugins!-->
        <div class="sideitem last">
            <div class="container">
                <div class="sideitem_head"><h4><?php echo $mn_langpackage->mn_pal;?></h4><a href="modules.php?app=friend_all&user_id=<?php echo $user_id;?>" target='frame_content'><?php echo $mp_langpackage->mp_more;?></a></div>
                <div class="sideitem_body"><ul id='friend_list' class="userlist"></ul></div>
            </div>
        </div>
        <!--plugins!-->
        <div class="main_right_friend">
            <?php echo isset($plugins['main_right_friend'])?show_plugins($plugins['main_right_friend']):'';?>
        </div>
        <!--plugins!-->                
    </div>
    <div id="bigpart" class="bigpart">
    	<iframe onload="this.height=frame_content.document.body.scrollHeight" id="frame_content" name="frame_content" src="modules.php<?php echo '?'.$_SERVER['QUERY_STRING'];?>" scrolling="no" frameborder="0" width="100%" height="100%" allowTransparency="true"></iframe>
    </div>
</div>
</div>
<?php require("uiparts/footor.php");?>
<script type='text/javascript'>
function set_cur_guest(){
	var ajax_guest=new Ajax();
	ajax_guest.getInfo("modules.php","GET","app","app=guest&user_id=<?php echo $user_id;?>",function(c){$("current_guest").innerHTML=trim(c);set_friend_list();});
}

function set_friend_list() {
	var ajax_friend=new Ajax();
	ajax_friend.getInfo("modules.php","GET","app","app=friend&user_id=<?php echo $user_id;?>",function (c){$("friend_list").innerHTML=trim(c);});
}
set_cur_guest();
</script>
<script language="JavaScript" src="im/im_js.php"></script>
<SCRIPT language=JavaScript src="servtools/ajax_client/auto_ajax.js"></SCRIPT>
</body>
</html>