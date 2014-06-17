<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/photo_view.html
 * 如果您的模型要进行修改，请修改 models/modules/album/photo_view.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$a_langpackage=new albumlp;
	$mn_langpackage=new menulp;

	require("foundation/module_users.php");

	//变量取得
	$photo_id = intval(get_argg('photo_id'));
	$album_id=intval(get_argg('album_id'));
	$prev_next = get_argg('prev_next');
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");
	require("foundation/module_mypals.php");
	require("api/base_support.php");

	//数据显示控制
	$show_data="";
	$show_error="content_none";
	$show_content="content_none";

	//数据表定义区
	$t_photo = $tablePreStr."photo";
	$t_photo_comment = $tablePreStr."photo_comment";
	$t_users=$tablePreStr."users";
	$t_album=$tablePreStr."album";

	$album_info=array();
	$photo_row=array();
	$album_info=api_proxy("album_self_by_aid","album_name",$album_id);
	$a_who=($is_self=='Y') ? $a_langpackage->a_mine:str_replace('{holder}',filt_word(get_hodler_name($url_uid)),$a_langpackage->a_holder);
		
	if($album_info){
		//查找相册信息
		$album_name=$album_info['album_name'];
		if($prev_next){
			$photo_rs = api_proxy('album_photo_by_aid','photo_id',$album_id);
			$num = count($photo_rs);
			foreach($photo_rs AS $key=>$val)
			{
				if($val['photo_id'] == $photo_id)
				{
					$photo_id = $photo_rs[$prev_next === 'next' ? ($key == ($num - 1) ? 0 : $key + 1) : ($prev_next === 'prev' ? ($key == 0 ? $num - 1 : $key - 1) : 0)]['photo_id'];
					break;
				}
			}
		}
		
		$photo_row=api_proxy("album_photo_by_photoid","*",$photo_id);
		
		//查找照片信息
		if($photo_row['photo_src']){
			$img_info=getimagesize($photo_row['photo_src']);
		}
		$photo_inf=$photo_row['photo_information'] ? $photo_row['photo_information']:$a_langpackage->a_pht_inf;
		if($is_self=='Y'){
			$is_pri=1;
		}else{
			require("servtools/menu_pop/trans_pri.php");
			$is_pri=check_pri($photo_row['user_id'],$photo_row['privacy']);
		}
	}else{
		$show_data="content_none";
		$show_error="";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<base href='<?php echo $siteDomain;?>' />
<title></title>
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" type="text/css" href="servtools/menu_pop/menu_pop.css">
<script type='text/javascript' src='servtools/ajax_client/ajax.js'></script>
<?php echo $is_self=='Y' ? "<script type='text/javascript' src='servtools/menu_pop/group_user.php'></script>" : "";?>
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
<script type="text/javascript">
function change_photo_callback(content){
	var return_text=content;
	var return_text=return_text.replace(/[\s\n\r]/g,"");
	if(return_text==""){
	$("def_photo_info").innerHTML="<?php echo $a_langpackage->a_pht_inf;?>";}
	else{$("def_photo_info").innerHTML=return_text;}
	$("def_photo_info").style.display="";
	$("photo_info").style.display="none";
}
function change_photo(){
	var photo_id=$("photo_id").value;
	var photo_information_value=$("information_value").value;
	var change_photo=new Ajax();
	change_photo.getInfo("do.php?act=photo_im&photo_id=<?php echo $photo_id;?>&album_id=<?php echo $album_id;?>","post","app","photo_id="+photo_id+"&information_value="+photo_information_value,function(c){change_photo_callback(c);});
}

function change_state(){
	var return_text=$("def_photo_info").innerHTML;
	var return_text=return_text.replace(/[\s\n\r]/g,"");
	if(return_text=="<?php echo $a_langpackage->a_pht_inf;?>"){
		var information="";
	}else{
		var information=return_text;
	}
	$("information_value").value=information;
	$("def_photo_info").style.display="none";
	$("photo_info").style.display="";
}

function chancel(){
	$("def_photo_info").style.display="";
	$("photo_info").style.display="none";
}

function Get_mouse_pos(obj){
	var event=getEvent();
	if(navigator.appName=='Microsoft Internet Explorer'){
		return event.offsetX;
	}else if(navigator.appName=='Netscape'){
		return event.pageX-obj.offsetLeft;
	}
}
</script>
</head>
<body id="iframecontent" oncontextmenu="return false;">
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload&album_id=<?php echo $album_id;?>"><?php echo $a_langpackage->a_upload;?></a></div>
<?php }?>
<h2 class="app_album"><?php echo $a_who;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
        <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>
<?php }?>
<?php if($photo_row){?>
	<div class="iframe_contentbox">
		<div class="photo_showbox">
			<div class="sub_box">
				<div class="photo_name"><?php echo filt_word($photo_row['photo_name']);?><span>《<?php echo filt_word($album_name);?>》</span></div>
				<div class="photo_uploadtime"><?php echo str_replace("{date}",$photo_row['add_time'],$a_langpackage->a_send_time);?></div>
				<div class="photo_view">
					<img <?php if($is_self=='Y'){?>onmouseDown="menu_pop_show(event,this);"<?php }?> id='<?php echo $t_photo;?>:<?php echo $photo_row['photo_id'];?>:<?php echo $photo_row["privacy"];?>' style='display:none;max-width:470' onerror="parent.pic_error(this)" onmousemove='turnover(this);' />
					<img id='show_ajax' src='skin/<?php echo $skinUrl;?>/images/loading.gif' />
				</div>

				<?php if($photo_row['user_id']==$ses_uid){?>
				<div class='photo_intro'>
					<input class="med-text" type='hidden' name='photo_id' id='photo_id' value=<?php echo $photo_id;?> />
					<div id='def_photo_info' onmouseover="this.style.backgroundColor='#ffffce';this.style.borderColor='#efcf7b';" onmouseout="this.style.backgroundColor='#fffbff';this.style.borderColor='#ececec';" onclick="change_state();"><?php echo filt_word($photo_inf);?></div>
					<div id='photo_info' style='display:none;text-align:center;'>
						<textarea class="med-textarea" cols='40' rows='2' name='information_value' id='information_value'></textarea><br />
						<input type='button' value='<?php echo $a_langpackage->a_b_con;?>' class='small-btn' onclick='change_photo()' />
						<input type='button' value='<?php echo $a_langpackage->a_b_del;?>' class='small-btn' onclick='chancel()' />
					</div>
				</div>
				<div class="photo_operate">
					<a href='do.php?act=album_skin&photo_id=<?php echo $photo_row['photo_id'];?>&album_id=<?php echo $album_id;?>'><?php echo $a_langpackage->a_set_cov;?></a>
					<a href="javascript:void(0);" onclick="change_state();"><?php echo $a_langpackage->a_set_info;?></a>
					<a href='do.php?act=photo_del&photo_id=<?php echo $photo_row['photo_id'];?>&album_id=<?php echo $album_id;?>' onclick="return confirm('<?php echo $a_langpackage->a_del_asc;?>');"><?php echo $a_langpackage->a_com_del;?></a>
					<a href="<?php echo $photo_row['photo_src'];?>" target="_blank"><?php echo $a_langpackage->a_see_pic;?></a>
					<a href="modules.php?app=photo_list&album_id=<?php echo $album_id;?>&user_id=<?php echo $url_uid;?>"><?php echo $a_langpackage->a_bak_list;?></a>
				</div>
				<?php }?>
			</div>
		</div>
		<div class="photo_operate">
			<?php if($photo_row['user_id']!=$ses_uid){?>
				<?php if($photo_row['photo_information']!=''){?>
				<div id='def_photo_info'><?php echo filt_word($photo_row['photo_information']);?></div>
				<?php }?>
				<?php if($ses_uid){?>
				<a href="javascript:void(0);" onclick="parent.show_share(3,<?php echo $photo_row['photo_id'];?>,'<?php echo $photo_row['photo_name'];?>','');"><?php echo $mn_langpackage->mn_share;?></a>
				<a href="javascript:void(0);" onclick="parent.report(3,<?php echo $photo_row['user_id'];?>,<?php echo $photo_row['photo_id'];?>);"><?php echo $mn_langpackage->mn_report;?></a>
				<?php }?>
			<a href="<?php echo $photo_row['photo_src'];?>" target="_blank"><?php echo $a_langpackage->a_see_pic;?></a>
			<a href="modules.php?app=photo_list&album_id=<?php echo $album_id;?>&user_id=<?php echo $url_uid;?>"><?php echo $a_langpackage->a_bak_list;?></a>
			<?php }?>
		</div>
	</div>

<div class="tleft ml20">
	<div class="comment">
        <div id='show_3_<?php echo $photo_row["photo_id"];?>' class="tleft">
            <script type='text/javascript'>parent.get_mod_com(3,<?php echo $photo_row['photo_id'];?>,0,20);</script>
        </div>
		<?php if($ses_uid!=''){?>
		<div class="reply" <?php echo $show_content;?>>
				<img class="figure" src="<?php echo get_sess_userico();?>" />
				<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)" id="reply_3_<?php echo $photo_row['photo_id'];?>_input"></textarea></p>
				<div class="replybt">
					<input class="left button" onclick="parent.restore_com(<?php echo $photo_row['user_id'];?>,3,<?php echo $photo_row['photo_id'];?>);" type="submit" name="button" id="button" value="<?php echo $a_langpackage->a_b_com;?>" />
					<a class="right" href="javascript: void(0);" onclick="showim(''); showFace(this,'face_list_menu','reply_3_<?php echo $photo_row['photo_id'];?>_input');">表情</a>
				</div>
				<div class="clear"></div>
		</div>
		<?php }?>
	</div>
</div>
<?php }?>

<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
<!--锁定控制-->
<div class="guide_info <?php echo $show_error;?>"><?php echo $a_langpackage->a_ine;?></div>
<div class="guide_info <?php echo $show_content;?>"><?php echo $a_langpackage->a_add_pvw;?></div>

<?php if($photo_row){?>
	<script type='text/javascript'>
		var img_obj=$('<?php echo $t_photo;?>:<?php echo $photo_row["photo_id"];?>:<?php echo $photo_row["privacy"];?>');
		var ajax_obj=$('show_ajax');
		var show_img=new Image;
		show_img.src='<?php echo $is_pri ? $photo_row["photo_src"] : "skin/$skinUrl/images/error.gif";?>';
		var time_id=window.setTimeout("test_img_complete()",200);
		var show_width=130;
		<?php if($is_pri){?>
		var show_width=<?php echo $img_info[0];?>>470?470:<?php echo $img_info[0];?>;
		<?php }?>
		function test_img_complete(){
			if(show_img.complete==true){
				img_obj.src='<?php echo $is_pri ? $photo_row["photo_src"] : "skin/$skinUrl/images/error.gif";?>';
				img_obj.width=show_width;
				img_obj.style.display='';
				ajax_obj.style.display='none';
				window.clearTimeout(time_id);
			}else{
				var time_id=window.setTimeout("test_img_complete()",200);
			}
		}
		function turnover(obj){
			var move_x=Get_mouse_pos(obj);
			if(move_x >= show_width/2){
				obj.style.cursor="URL(skin/<?php echo $skinUrl;?>/images/next.cur),auto";
				obj.title='<?php echo $a_langpackage->a_page_down;?>';
				obj.onclick=function(){location.href="<?php echo $siteDomain;?>modules.php?app=photo&photo_id=<?php echo $photo_id;?>&album_id=<?php echo $album_id;?>&prev_next=next&user_id=<?php echo $url_uid;?>"};
			}else{
				obj.style.cursor="URL(skin/<?php echo $skinUrl;?>/images/pre.cur),auto";
				obj.title='<?php echo $a_langpackage->a_page_up;?>';
				obj.onclick=function(){location.href="<?php echo $siteDomain;?>modules.php?app=photo&photo_id=<?php echo $photo_id;?>&album_id=<?php echo $album_id;?>&prev_next=prev&user_id=<?php echo $url_uid;?>"};
			}
		}
	</script>
<?php }?>
</body>
</html>