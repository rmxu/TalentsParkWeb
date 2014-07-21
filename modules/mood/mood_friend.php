<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/mood/mood_friend.html
 * 如果您的模型要进行修改，请修改 models/modules/mood/mood_friend.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$mo_langpackage=new moodlp;
	
	//变量取得
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$userico=get_sess_userico();
	$pals_id=get_sess_mypals();
	
	//引入模块公共权限过程文件
	require("foundation/fcontent_format.php");
	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//数据表定义区
	$t_mood = $tablePreStr."mood";
	$t_users = $tablePreStr."users";
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	$mood_rs=api_proxy("mood_self_by_uid","*","$pals_id");

	//分页显示
	$isNull=0;
	$data_none='content_none';
	$show_data='';	
	if(empty($mood_rs)){
		$isNull=1;
		$data_none='';
		$show_data='content_none';
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<SCRIPT type='text/javascript' src="servtools/ajax_client/ajax.js"></SCRIPT>
<SCRIPT language=JavaScript src="skin/default/js/jooyea.js"></SCRIPT>
<script type='text/javascript'>
function mood_com(mood_id,mod_id,type_id,start_num,end_num){
	var obj_mood=$('content_mood_'+mood_id);
	if(obj_mood.style.display=='none'){
		obj_mood.style.display="";
		if($("show_"+type_id+"_"+mod_id).innerHTML==''){
			parent.get_mod_com(type_id,mod_id,start_num,end_num);
		}
	}else{
		obj_mood.style.display='none';
		$('face_list_menu').style.display='none';
	}
}
</script>
</head>

<body id="iframecontent">
<h2 class="app_mood"><?php echo $mo_langpackage->mo_mood;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li><a href="modules.php?app=mood_more" hidefocus="true"><?php echo $mo_langpackage->mo_mine;?></a></li>
            <li class="active"><a href="modules.php?app=mood_friend" hidefocus="true"><?php echo $mo_langpackage->mo_pals;?></a></li>
        </ul>
    </div>

    <div class="mood_box">
		<ul>
			<?php foreach($mood_rs as $val){?>
			<li class="mood_list">
				<div class="user_photo"><a href='home.php?h=<?php echo $val["user_id"];?>' target='_blank' class="avatar"><img src="<?php echo $val["user_ico"];?>" onerror="parent.pic_error(this)" /></a></div>
				<div class="mood_cont">
					<div class="mood_text"><?php echo filt_word(get_face($val['mood']));?></div>
					<div class="mood_info">
						<span><?php echo $val['user_name'];?>&nbsp;&nbsp;<?php echo $val['add_time'];?></span>
						<span><a id="restore_mood_<?php echo $val['mood_id'];?>" href='javascript:mood_com(<?php echo $val['mood_id'];?>,<?php echo $val['mood_id'];?>,6,0,10);'><?php echo $mo_langpackage->mo_com;?>(<label id="num_6_<?php echo $val['mood_id'];?>"><?php echo $val['comments'];?></label>)</a></span>
					</div>
          <div class="tleft" style="display:none;" id='content_mood_<?php echo $val["mood_id"];?>'>
          <div class="comment">
          <div id='show_6_<?php echo $val["mood_id"];?>'></div>
          <?php if($ses_uid!=''){?>
            <div class="reply">
              <img class="figure" src='<?php echo get_sess_userico();?>' />
              <p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)"  id="reply_6_<?php echo $val['mood_id'];?>_input"></textarea></p>
              <div class="replybt">
                  <input class="left button" onclick="parent.restore_com(<?php echo $val['user_id'];?>,6,<?php echo $val['mood_id'];?>);show('face_list_menu',200)" type="button" name="button" id="button" value="<?php echo $mo_langpackage->mo_b_con;?>" />
                  <a id="reply_a_<?php echo $val['mood_id'];?>_input" class="right" href="javascript:void(0);" onclick=" showFace(this,'face_list_menu','reply_6_<?php echo $val['mood_id'];?>_input');"><?php echo $mo_langpackage->mo_face;?></a>
              </div>
              <div class="clear"></div>
            </div>
          <?php }?>    
          </div>
          </div>
				</div>
				<div class="clear"></div>
			<?php }?>
			</li>
		</ul>
		<div class="clear"></div>
			<?php echo page_show($isNull,$page_num,$page_total);?>
    </div>

<!-- face begin -->
<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
<!-- face end -->
<div class="guide_info <?php echo $data_none;?>">
	<?php echo $mo_langpackage->mo_none_data;?>
</div>
</body>
</html>
