<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/homestart.html
 * 如果您的模型要进行修改，请修改 models/modules/homestart.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入公共方法
require("foundation/module_users.php");
require("foundation/fcontent_format.php");
require("api/base_support.php");

//引入语言包
$u_langpackage=new userslp;
$rf_langpackage=new recaffairlp;
$ah_langpackage=new arrayhomelp;

//变量获得
$holder_id=intval(get_argg('user_id'));//主人id
$user_id=get_sess_userid();
$holder_name=get_hodler_name($holder_id);
$is_self=($holder_id==$user_id) ? 'Y':'N';
$msg_act=$user_id ? "send_msg($holder_id)":"parent.goLogin()";

//数据表定义区
$t_users=$tablePreStr."users";
$t_blog=$tablePreStr."blog";
$t_album=$tablePreStr."album";
$t_photo=$tablePreStr."photo";

//留言板展示
$user_info=api_proxy("user_self_by_uid","inputmess_limit",$holder_id);
$show_msg=1;
if($is_self=='N' && $user_info['inputmess_limit']){
	if($user_info['inputmess_limit']==2){
		$show_msg=0;
	}else if($user_info['inputmess_limit']==1){
		if(!api_proxy("pals_self_isset",$holder_id)){
			$show_msg=0;
		}
	}
}

$dbo=new dbex;
//读写分离定义方法
dbtarget('r',$dbServs);

$holder_photo=array();
$holder_blog=array();
$holder_message=array();

//取得最新照片
$sql="select p.album_id,p.photo_id,p.photo_thumb_src,p.add_time,p.photo_name from $t_album as a join $t_photo as p on(a.album_id=p.album_id) where p.user_id=$holder_id and a.privacy='' and p.privacy='' and a.is_pass=1 and p.is_pass=1 order by p.photo_id desc limit 4";
$holder_photo=$dbo->getRs($sql);

//取得最新日志
$sql="select log_id,log_title,log_content,add_time from $t_blog where user_id=$holder_id and is_pass=1 and privacy='' order by log_id desc limit 3 ";
$holder_blog=$dbo->getRs($sql);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" href="skin/<?php echo $skinUrl;?>/css/layout.css" />
<link rel="stylesheet" href="skin/<?php echo $skinUrl;?>/css/iframe.css" />
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDrag.js"></script>
<script type="text/javascript" language="javascript" src="servtools/dialog/zDialog.js"></script>
<script type='text/javascript' src="servtools/ajax_client/ajax.js"></script>
<script type='text/javascript'>
function fixImage(i,w,h){
	return true;
}
//新鲜事
function list_recent_affair(h_id,ra_type,is_more){
	hidden_obj('msg_content');show_obj('affair_content');
	hidden_obj('none_data');show_obj('affair_info');
	var recent_affair_div=$("sec_Content");
	if(is_more!=1){//重新切换类别新鲜事则清空预设值
		$('affair_start_num').value=0;
		recent_affair_div.innerHTML="<div style=\"width:150px; margin-left:auto; margin-right:auto; height:100px; padding:60px; *padding:30px; line-height:32px;\"><span class='right'><?php echo $ah_langpackage->ah_loading_data;?></span><img src='skin/<?php echo $skinUrl;?>/images/loading.gif'></div>";
	}else{
		$('affair_start_num').value=parseInt($('affair_start_num').value)+<?php echo $homeAffairNum;?>;
	}
	if(ra_type==='')	ra_type=$('affair_type').value;
		else	$('affair_type').value=ra_type;
	var start_num=parseInt($('affair_start_num').value);
	var list_affair=new Ajax();//实例化Ajax
	list_affair.getInfo("modules.php","get","app","app=recent_affair&user_id="+h_id+"&t="+ra_type+"&start_num="+start_num,function(c){
		if(is_more==1){
			recent_affair_div.innerHTML=recent_affair_div.innerHTML+c;
		}else{
			recent_affair_div.innerHTML=c;
		}
		if(c==''){
			hidden_obj('affair_info');show_obj('none_data');
		}else{
			pick_script(c);
		}	
	});
}

//留言板
function get_msg(uid){
	hidden_obj('affair_content');
	show_obj('msg_content');
	$('msg_content').innerHTML="<div style=\"width:150px; margin-left:auto; margin-right:auto; height:100px; padding:60px; *padding:30px; line-height:32px;\"><span class='right'><?php echo $ah_langpackage->ah_loading_data;?></span><img src='skin/<?php echo $skinUrl;?>/images/loading.gif'></div>";
	var list_msg=new Ajax();//实例化Ajax
	list_msg.getInfo("modules.php","get","app","app=msgboard&user_id="+uid,function(c){$('msg_content').innerHTML=c;});
}

//给主人留言
function send_msg(uid){
	var content=$('msgboard').value;
	if(content == '')
	{
		parent.Dialog.alert("<?php echo $ah_langpackage->ah_fill_content;?>");
		return;
	}
	var to_user_id=$('user_id').value;
	var act_msg=new Ajax();//实例化Ajax
	act_msg.getInfo("do.php?act=msgboard_send&ajax=1&user_id="+uid,"post","app","msgboard="+content+"&to_user_id="+to_user_id,function(c){$('msgboard').value='';$('user_id').value='';get_msg(uid);});
}

function changeStyle(obj){
	var tagList = obj.parentNode;
	var tagOptions = tagList.getElementsByTagName("li");
	for(i=0;i<tagOptions.length;i++){
		if(tagOptions[i].className.indexOf('active')>=0){
			tagOptions[i].className = '';
		}
	}
	obj.className = 'active';
}
parent.showDiv();
</script>

</head>
<body id="iframecontent">
<input type="hidden" name="to_user_id" id="user_id" value="" />
<input type='hidden' id='affair_type' value=0 />
<input type='hidden' id='affair_start_num' value=0 />
<!-- album start -->
<?php if($holder_photo){?>
<div class="module zoom">
  <h3><?php echo $ah_langpackage->ah_latest_photos;?></h3>
  <ul class="albumlist">
  	<?php foreach($holder_photo as $photo_item){?>
  	<li>
  		<a href="<?php echo rewrite_fun("home.php?h=".$holder_id."&app=photo&photo_id=".$photo_item['photo_id']."&album_id=".$photo_item['album_id']."&user_id=".$holder_id);?>" target="_blank">
  			<img src="<?php echo $photo_item['photo_thumb_src'];?>" />
  		</a>
  		<h5><a class="highlight" target="_blank" href="<?php echo rewrite_fun("home.php?h=".$holder_id."&app=photo&photo_id=".$photo_item['photo_id']."&album_id=".$photo_item['album_id']."&user_id=".$holder_id);?>"><?php echo $photo_item['photo_name'];?></a></h5>
  		<p class="meta"><?php echo $photo_item['add_time'];?></p>
  	</li>
  	<?php }?>
  </ul>
  <div class="more"><a target="_blank" class="highlight" title="<?php echo $ah_langpackage->ah_view_all_my_photos;?>" href="home.php?h=<?php echo $holder_id;?>&app=album&user_id=<?php echo $holder_id;?>"><?php echo $ah_langpackage->ah_all_photos;?> »</a></div>
</div>
<?php }?>
<!-- album end -->

<!-- blog start -->
<?php if($holder_blog){?>
<div class="module zoom">
    <h3><?php echo $ah_langpackage->ah_latest_blog;?></h3>
	<ul class="bloglist">
		<?php foreach($holder_blog as $blog_item){?>
        <li>
            <h4><a target="_blank" class="highlight" href="<?php echo rewrite_fun("home.php?h=".$holder_id."&app=blog&id=".$blog_item['log_id']."&user_id=".$holder_id);?>"><?php echo $blog_item['log_title'];?></a><span class="meta"><?php echo $blog_item['add_time'];?></span></h4>
            <p class="meta"><?php echo get_short_txt($blog_item['log_content']);?></p>
        </li>
        <?php }?>
  	</ul>
  <div class="more"><a target="_blank" class="highlight" title="<?php echo $ah_langpackage->ah_see_all_my_log;?>" href="home.php?h=<?php echo $holder_id;?>&app=blog_list&user_id=<?php echo $holder_id;?>"><?php echo $ah_langpackage->ah_all_logs;?> »</a></div>
</div>
<?php }?>
<!-- blog end -->
<!--msg!-->
<?php if($show_msg==1){?>
<div class="msg">
	<p><span><?php echo $ah_langpackage->ah_you_can_enter;?><input disabled="disabled" value="150" id="msgnum"><?php echo $ah_langpackage->ah_word;?></span><?php echo $ah_langpackage->ah_to;?><label><?php echo $holder_name;?></label><?php echo $ah_langpackage->ah_message;?></p>
	<p><textarea name="msgboard" id="msgboard"  maxlength="150" onpropertychange="javascript:$('msgnum').value=''+(150-this.value.length)+'';" onkeyup="return isMaxLen(this)"></textarea></p>
	<script>var $=function(o){return document.getElementById(o)};if(window.addEventListener){$('msgboard').addEventListener('input',function(){$('msgnum').value=''+(150-this.value.length)+'';}, false);}</script>
	<p><a class="em_bg left" href="javascript:void(0);" onclick="showFace(this,'face_list_menu','msgboard');"  hidefocus="true"><?php echo $ah_langpackage->ah_expression;?></a><input class="button right" name="" onclick="<?php echo $msg_act;?>" type="button" value="<?php echo $ah_langpackage->ah_message;?>" /></p>
</div>
<?php }?>
<!--msg!-->
<div class="tabs">
	<ul class="menu">
  	<li class="active" onclick="list_recent_affair(<?php echo $holder_id;?>,0);changeStyle(this);"><a href="javascript:;" hidefocus="true"><?php echo $ah_langpackage->ah_new_nothing;?></a></li>
    <li onclick="get_msg(<?php echo $holder_id;?>);changeStyle(this);"><a href="javascript:;" hidefocus="true"><?php echo $ah_langpackage->ah_message_board;?></a></li>
  </ul>
</div>
<div class="feedcontainer" id="affair_content">
	<ul id="sec_Content"></ul>
	<div id='none_data' style="display:none;" class="gray mt20"><?php echo $rf_langpackage->rf_none;?></div>
	<div title="<?php echo $ah_langpackage->ah_see_more_novelty;?>" id="affair_info" onclick="list_recent_affair(<?php echo $holder_id;?>,'',1);" class="more"></div>
</div>

<div class="message" id="msg_content"></div>

<div id="face_list_menu" style="display:none;z-index:100;" class="emBg"></div>
<script type='text/javascript'>
	list_recent_affair(<?php echo $holder_id;?>,0);
</script>
</body>
</html>