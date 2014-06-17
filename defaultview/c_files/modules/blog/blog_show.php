<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/blog/blog_show.html
 * 如果您的模型要进行修改，请修改 models/modules/blog/blog_show.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
  //引入公共模块
  require("foundation/module_mypals.php");
	require("foundation/module_blog.php");
	require("api/base_support.php");

  //语言包引入
  $b_langpackage=new bloglp;
	$mn_langpackage=new menulp;

  //变量区
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$is_friend=intval(get_argg('is_friend'));
	$ulog_id=intval(get_argg("id"));
	$is_admin=get_sess_admin();
	$blog_row=array();

  //引入模块公共权限过程文件
  $is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

  //数据表定义
  $t_blog=$tablePreStr."blog";

  //初始化数据库操作对象
  $dbo=new dbex;
  if($ses_uid!=get_session('b_'.$ulog_id)){
	  //读写分离方法-写操作
	  dbtarget('w',$dbServs);
	  $sql="update $t_blog set hits=hits+1 where log_id=$ulog_id";
	  $dbo->exeUpdate($sql);
	  set_session('b_'.$ulog_id,$ses_uid);
	}

	//控制数据显示
	$show_error="content_none";
	$is_show=1;
	$error_str='';
	if($ulog_id){
		$blog_row=api_proxy("blog_self_by_bid","*",$ulog_id);
	  if($is_self=='N'){
	  	require("servtools/menu_pop/trans_pri.php");
	  	$is_show=check_pri($blog_row['log_id'],$blog_row['privacy']);
	  }
	}
	//控制按钮显示
	$button_ctrl_his="content_none";
	$button_ctrl_mine="";

	if(get_argg('is_friend')==1){
		$button_ctrl_his="";
		$button_ctrl_mine="content_none";
	}

	if(empty($blog_row)){
		$error_str=$b_langpackage->b_error;
		$is_show=0;
		$show_error="";
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" type="text/css" href="servtools/menu_pop/menu_pop.css">
<script type="text/javascript" src="servtools/imgfix.js"></script>
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript' src='servtools/ajax_client/ajax.js'></script>
<?php echo $is_self=='Y' ? "<script type='text/javascript' src='servtools/menu_pop/group_user.php'></script>" : "";?>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
<script type='text/javascript'>
function CheckForm(){
	if (trim(document.myform.CONTENT.value)==""){
		parent.Dialog.alert("<?php echo $b_langpackage->b_empty;?>");
		return false;
	}
}
</script>
</head>
<body id="iframecontent">
    <div class="create_button <?php echo $button_ctrl_his;?>"><a href="modules.php?app=blog_friend&user_id=<?php echo $userid;?>"><?php echo $b_langpackage->b_re_list;?></a></div>
    <div class="create_button <?php echo $button_ctrl_mine;?>"><a href="modules.php?app=blog_list&user_id=<?php echo $userid;?>"><?php echo $b_langpackage->b_re_list;?></a></div>
    <h2 id="page_title" class="app_blog"><?php echo $b_langpackage->b_scan;?></h2>
	<?php if($is_show){?>
	<dl class="log_list">
		<dt>
			<strong><?php echo filt_word($blog_row["log_title"]);?></strong><br />
			<span><?php echo $b_langpackage->b_sort;?>：<a href="modules.php?app=blog_list&sort_id=<?php echo $blog_row['log_sort'];?>&sort_name=<?php echo urlencode($blog_row['log_sort_name']);?>&user_id=<?php echo $blog_row['user_id'];?>" title="<?php echo $b_langpackage->b_same_sort;?>"><?php echo empty($blog_row['log_sort_name'])? $b_langpackage->b_default_sort :filt_word($blog_row['log_sort_name']);?></a></span><span class="log_list_options"><?php echo $blog_row["add_time"];?></span>
    </dt>
		<dd class="log_list_content"><?php echo filt_word($blog_row['log_content']);?></dd>
        <dd><span><?php echo $b_langpackage->b_label;?>：<?php echo $blog_row['tag'];?></span>
        	<span><?php echo str_replace("{b_com_num}",$blog_row['comments'],$b_langpackage->b_com_num);?></span>
					<span><?php echo str_replace("{b_read_num}",$blog_row['hits'],$b_langpackage->b_read_num);?></span>
					<?php if($blog_row['user_id']!=$ses_uid&&$ses_uid){?>
					<span>
						<a href="javascript:void(0);" onclick="parent.show_share(0,<?php echo $blog_row['log_id'];?>,'<?php echo $blog_row['log_title'];?>','');"><?php echo $mn_langpackage->mn_share;?></a>
						<a href="javascript:void(0);" onclick="parent.report(0,<?php echo $blog_row['user_id'];?>,<?php echo $blog_row['log_id'];?>);"><?php echo $mn_langpackage->mn_report;?></a>
					</span>
					<?php }?>
          <?php if($blog_row['user_id']==$ses_uid){?>
					<span class="log_edit_link">
						<a href='modules.php?app=blog_edit&id=<?php echo $blog_row["log_id"];?>'><?php echo $b_langpackage->b_edit;?></a>
					</span>
					<span class="log_del_link">
						<a href='do.php?act=blog_del&id=<?php echo $blog_row["log_id"];?>' onclick='return confirm("<?php echo $b_langpackage->b_a_del;?>");'><?php echo $b_langpackage->b_del;?></a>
					</span>
					<span onmousedown='menu_pop_show(event,this,1);' id='<?php echo $t_blog;?>:<?php echo $blog_row["log_id"];?>:<?php echo $blog_row["privacy"];?>' class="authority_set">
						<a href="javascript:void(0)"><?php echo $b_langpackage->b_pri;?></a>
					</span>
					<?php }?>
       </dd>
		<!--评论控制显示!-->
		<dd class="log_list_comment"></dd>
	</dl>
	<div class="tleft ml20">
	<div class="comment">
        <div id='show_0_<?php echo $blog_row["log_id"];?>'>
            <script type='text/javascript'>parent.get_mod_com(0,<?php echo $blog_row['log_id'];?>,0,20);</script>
        </div>
    <?php if($ses_uid!=''){?>    
		<div class="reply">
				<img class="figure" src="<?php echo get_sess_userico();?>" />
				<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)" id="reply_0_<?php echo $blog_row['log_id'];?>_input"></textarea></p>
				<div class="replybt">
					<input class="left button" onclick="parent.restore_com(<?php echo $blog_row['user_id'];?>,0,<?php echo $blog_row['log_id'];?>);show('face_list_menu',200)" type="submit" name="button" id="button" value="<?php echo $b_langpackage->b_button_re;?>" />
					<a id="reply_a_<?php echo $blog_row['log_id'];?>_input" class="right" href="javascript: void(0);" onclick=" showFace(this,'face_list_menu','reply_0_<?php echo $blog_row['log_id'];?>_input');"><?php echo $b_langpackage->b_face;?></a>
				</div>
				<div class="clear"></div>
		</div>
		<?php }?>
	</div>
	</div>
	<?php }?>
	<!-- face begin -->
	<div id="face_list_menu" class="emBg" style="display:none;z-index:100;">
	</div>
	<!-- face end -->
	<div class="guide_info <?php echo $show_error;?>"><?php echo $error_str;?> </div>
</body>
</html>