<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/poll/poll_show.html
 * 如果您的模型要进行修改，请修改 models/modules/poll/poll_show.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
  //引入公共模块
  require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/module_poll.php");
	require("foundation/fcontent_format.php");

  //语言包引入
  $pol_langpackage=new polllp;
  $g_langpackage=new grouplp;
	$mn_langpackage=new menulp;

  //变量区
	$ses_uid=get_sess_userid();
	$u_sex=get_sess_usersex();
	$pid=intval(get_argg('p_id'));
	$url_uid=intval(get_argg('user_id'));
	$is_admin=get_sess_admin();
	$mod=get_argg('m');

	$new_active="";
	$hot_active="";
	$reward_active="";
	$mine_active="";
	switch($mod){
		case "new":
		$new_active="active";
		break;
		case "hot":
		$hot_active="active";
		break;
		case "reward":
		$reward_active="active";
		break;
		default:
		$mine_active="active";
	}

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	 dbtarget('r',$dbServs);
   $dbo=new dbex;

//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_poll=$tablePreStr."poll";
	$t_polloption=$tablePreStr."polloption";
	$t_polluser=$tablePreStr."polluser";

	$p_result_rs=array();
	$p_com_rs=array();
	$p_put_info=array();
	$poll_row=array();
	$sum_poll_num=array();
	$is_poll=array();
	$pals_id='';
	$refuse_str='';
	$error_str='';
	if($pid){
		$poll_row=get_poll_row($dbo,$t_poll,$pid);

		$sql="select * from $t_polloption where pid=$pid";
	 	$p_result_rs=$dbo->getRs($sql);

	 	$sql="select sum(votenum) as sum_votenum from $t_polloption where pid=$pid";
	 	$sum_poll_num=$dbo->getRow($sql);

	 	$pals_id=getMypals($dbo,$poll_row['user_id'],$t_mypals);

		$sql="select * from $t_polluser where pid=$pid order by dateline desc limit 10";
		$p_put_info=$dbo->getRs($sql);
	}

//投票信息
	$show_poll_info="";
	if(empty($p_put_info)){
		$show_poll_info="content_none";
	}
	if($ses_uid!=''){
		$sql="select username from $t_polluser where uid=$ses_uid and pid=$pid";
		$is_poll=$dbo->getRs($sql);
	}
	//防止重复投票
	if(empty($is_poll)){
		$check_form="check_form();";
		$show_check="";
		$action_URL="do.php?act=poll_submit&pid=".$poll_row['p_id'];
	}else{
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_repeat;
	}

//性别限定
if($ses_uid!=''){
	if($poll_row['sex']!=2&&$poll_row['sex']!=$u_sex){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=str_replace("{sex}",get_user_sex($poll_row['sex']),$pol_langpackage->pol_sex_limit);
	}
}

//锁定判断
	$show_error="content_none";
	if($poll_row['is_pass']==0 && $is_admin==''){
    $error_str=$pol_langpackage->pol_lock;
    $show_error="";
	}

//总结报告
	$show_sumary="";
	if(empty($poll_row['summary'])){
		$show_sumary="content_none";
	}

//操作显示
	$action_ctrl="";
	$sendor_info="content_none";
	$show_share="content_none";
	$show_i_com="";
	if($ses_uid!=$poll_row['user_id']){
		$sendor_info="";
		$action_ctrl="content_none";
		$show_share="";
	//评论好友限定
		if($poll_row['noreply']==1&&!strstr($pals_id,$ses_uid)||$ses_uid==NULL){
			$show_i_com="content_none";
		}
	}

//显示奖励积分
	$show_award_int="";
	if(empty($poll_row['percredit'])){
		$show_award_int="content_none";
	}

//控制数据显示
	$show_data="";
	if(empty($poll_row)){
		$error_str=$pol_langpackage->pol_error;
		$show_error="";
		$show_data="content_none";
		$show_com="content_none";
		$show_sumary="content_none";
		$isNull=1;
		$sendor_info="content_none";
		$action_ctrl="content_none";
		$show_i_com="content_none";
		$show_poll_info="content_none";
		$show_award_int="content_none";
	}

//投票结束
	if(strtotime($poll_row['expiration']) < strtotime(date("Y-m-d",time()))){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_date_over;
		$date_color='color:red';
	}
//未登录
	if(!$ses_uid){
		$show_check="content_none";
		$action_URL='#';
		$check_form="refuse_poll();";
		$refuse_str=$pol_langpackage->pol_not_login;
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script type='text/javascript' src='servtools/calendar.js'></script>
<script type='text/javascript' src="skin/default/js/jooyea.js"></script>
<script type='text/javascript'>
function CheckForm(){
	if(trim(document.myform.CONTENT.value)==""){
		parent.Dialog.alert("<?php echo $g_langpackage->g_not_null;?>");
		return (false);
	}
		return (true);
}

	function check_form(){
		var checked_num=0;
		var def=0;
		var pol_cho=document.getElementsByName("pol_cho[]");
		for(def=0;def<pol_cho.length;def++){
			if(pol_cho[def].checked){
				checked_num++;
			}
		}
		if(checked_num==0||checked_num><?php echo $poll_row['maxchoice'];?>){
			parent.Dialog.alert('<?php echo $pol_langpackage->pol_num_option;?>:1~<?php echo $poll_row["maxchoice"];?>');
			return false;
		}
	}

	function refuse_poll(){
		parent.Dialog.alert('<?php echo $refuse_str;?>');
		return false;
	}

	function show_config_callback(content){
		var diag = new parent.Dialog();
		diag.Width = 300;
		diag.Height = 200;
		diag.Top="50%";
		diag.Left="50%";
		diag.Title = "<?php echo $pol_langpackage->pol_voting;?>";
		diag.InnerHtml= content;
		diag.show();
	}

	function show_set_config(set_option,pid){
		var show_config=new Ajax();
		show_config.getInfo("modules.php","GET","app","app=poll_show_config&set_option="+set_option+"&pid="+pid,function(c){show_config_callback(c);});
	}

	function act_config_callback(content,type_value){
		if(content=='success'){
			if(type_value=='del_poll'){
				window.location.href="modules.php?app=poll_mine";
			}else{
				window.location.reload();
			}
		}else{
			parent.Dialog.alert(content);
		}
	}

	function action_set_config(set_option,pid){
		var act_config=new Ajax();
		var send_str='';

		switch(set_option){
			case "stop_award":
			send_str='';
			break;

			case "add_award":
			var obj_num=parent.document.getElementById("award_table").rows.length;
			if(obj_num==3){
				var add_award_sing=parent.document.getElementById("add_award_sing").value;
				var add_award_sum=parent.document.getElementById("add_award_sum").value;
				if(!isNaN(add_award_sum)||!isNaN(add_award_sing)){
					send_str="add_award_sum="+add_award_sum+"&add_award_sing="+add_award_sing;
				}
			}
			if(obj_num==2){
				var add_award_sum=parent.document.getElementById("add_award_sum").value;
				if(!isNaN(add_award_sum)){
					send_str="add_award_sum="+add_award_sum;
				}
			}
			break;

			case "add_option":
			var add_new_option=parent.document.getElementById("add_new_option").value;
				send_str="add_new_option="+add_new_option;
			break;

			case "del_poll":
			send_str='';
			break;

			case "change_date":
			var expiration=parent.document.getElementById("expiration").value;
			send_str="expiration="+expiration;
			break;

			case "add_summary":
			var add_summary=parent.document.getElementById("add_summary").value;
				send_str="add_summary="+add_summary;
			break;
		}
		act_config.getInfo("do.php?act=poll_set_config&set_option="+set_option+"&pid="+pid,"post","app",send_str,function(c){act_config_callback(c,set_option);parent.Dialog.close();});
	}


</script>
</head>
<body id="iframecontent">
<?php if($is_self=='Y'){?>
<div class="create_button"><a href="javascript:location.href='modules.php?app=poll_send';"><?php echo $pol_langpackage->pol_send;?></a></div>
<?php }?>
<h2 class="app_vote"><?php echo $pol_langpackage->pol_title;?></h2>
<?php if($is_self=='Y'){?>
<div class="tabs">
	<ul class="menu">
    <li class="<?php echo $new_active;?>"><a href="modules.php?app=poll_list&m=new" hidefocus="true"><?php echo $pol_langpackage->pol_new;?></a></li>
    <li class="<?php echo $hot_active;?>"><a href="modules.php?app=poll_list&m=hot" hidefocus="true"><?php echo $pol_langpackage->pol_hot;?></a></li>
    <li class="<?php echo $reward_active;?>"><a href="modules.php?app=poll_list&m=reward" hidefocus="true"><?php echo $pol_langpackage->pol_reward;?></a></li>
    <li class="<?php echo $mine_active;?>"><a href="modules.php?app=poll_mine" hidefocus="true"><?php echo $pol_langpackage->pol_mine;?></a></li>
  </ul>
</div>
<?php }?>
	<div class="iframe_contentbox">
    <?php if($ses_uid&&$poll_row['user_id']!=$ses_uid){?>
			<span class="right">
				<a class="highlight" href='javascript:void(0)' onclick="parent.show_share(4,<?php echo $poll_row['p_id'];?>,'<?php echo filt_word($poll_row['subject']);?>','','');"><?php echo $mn_langpackage->mn_share;?></a>
				<a class="highlight" href="javascript:void(0);" onclick="parent.report(4,<?php echo $poll_row['user_id'];?>,<?php echo $poll_row['p_id'];?>);"><?php echo $mn_langpackage->mn_report;?></a>
			</span>
			<?php }?>
		<div class="poll_initiator <?php echo $sendor_info;?>">
			<div class='poll_photo'>
				<a class="avatar" href="home.php?h=<?php echo $poll_row['user_id'];?>" target="_blank">
				<img src="<?php echo $poll_row['user_ico'];?>" onerror="parent.pic_error(this)" alt='<?php echo $poll_row['username'];?>' title='<?php echo $poll_row['username'];?>' height='43px' width='43px' /></a>
			</div>
			<div class="poll_userinfo">
				<h3><label style="color:#ce1221"><?php echo filt_word($poll_row['username']);?></label><?php echo $pol_langpackage->pol_sponsored_vote;?></h3>
				<p><a class="first" href='home.php?h=<?php echo $poll_row['user_id'];?>' target='_blank'><?php echo str_replace("{name}",filt_word($poll_row['username']),$pol_langpackage->pol_who_home);?></a><a href="modules.php?app=poll_list&user_id=<?php echo $poll_row['user_id'];?>">TA<?php echo $pol_langpackage->pol_vote;?></a><a href='javascript:window.history.go(-1);'><?php echo $pol_langpackage->pol_re_last;?></a></p>
			</div>
		</div>
		<div class="clear"></div>
		<?php if($poll_row['is_pass']==1 || $is_admin!='' && !empty($poll_row)){?>
			<dl class="poll_info">
				<dd><?php echo $pol_langpackage->pol_send_date;?><span><?php echo $poll_row['dateline'];?></span></dd>
				<dd><?php echo $pol_langpackage->pol_stop_time;?><span><?php echo $poll_row['expiration'];?></span><dd>
				<dd><?php echo $pol_langpackage->pol_leave_int;?><span><?php echo $poll_row['credit'];?></span><dd>
				<dt><?php echo $pol_langpackage->pol_join_num;?><span><?php echo $poll_row['voternum'];?></span><dt>
			</dl>
			<?php if($ses_uid==$poll_row['user_id']){?>
			<ul class="poll_mag <?php echo $show_data;?>">
				<li><a href='javascript:void(0)' onclick='show_set_config("stop_award",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_s_award;?></a></li>
				<li><a href='javascript:void(0)' onclick='show_set_config("add_award",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_add_award;?></a></li>
				<li><a href='javascript:void(0)' onclick='show_set_config("add_option",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_add_option;?></a></li>
				<li><a href='javascript:void(0)' onclick='show_set_config("del_poll",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_del_poll;?></a></li>
				<li><a href='javascript:void(0)' onclick='show_set_config("change_date",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_c_date;?></a></li>
				<li><a href='javascript:void(0)' onclick='show_set_config("add_summary",<?php echo $poll_row["p_id"];?>);'><?php echo $pol_langpackage->pol_write_sum;?></a></li>
			</ul>
			<?php }?>
		   <div class="clear"></div>
		   <div class="poll_title">
		   	<span class="main_title_dot5"></span><?php echo filt_word($poll_row['subject']);?><span class="poll_choice"><?php echo str_replace("{s_num}",$poll_row['maxchoice'],$pol_langpackage->pol_s_num);?></span>
		   </div>
           <div class="poll_credit"><?php echo filt_word($poll_row['message']);?></div>
		   <div class='poll_credit <?php echo $show_award_int;?>'><?php echo str_replace("{int}",$poll_row['percredit'],$pol_langpackage->pol_award_int);?></div>
	<form action='<?php echo $action_URL;?>' method='post' onsubmit='return <?php echo $check_form;?>' style='margin-left:0px'>
		<input type='hidden' name='credit' value='<?php echo $poll_row['credit'];?>' />
		<input type='hidden' name='percredit' value='<?php echo $poll_row['percredit'];?>' />
		<input type='hidden' name='subject' value='<?php echo $poll_row['subject'];?>' />
	<?php $default_id=0;?>
	<?php foreach($p_result_rs as $rs){?>
		<div class="poll_area"><input type='<?php echo choo_type($poll_row['multiple']);?>' name='pol_cho[]' value="<?php echo $rs['oid'];?>" class='<?php echo $show_check;?> poll_selbt' />
			<div class="poll_option"><?php echo $rs['option'];?>：</div>
			<div class="poll_percent">
				<div id='poll_bg_<?php echo $default_id;?>' class="poll_init" style="width:1px;"></div>
			</div>&nbsp;<?php echo $rs['votenum'];?>&nbsp;(<?php echo option_per($rs['votenum'],$sum_poll_num['sum_votenum']);?>%)
		</div>
	<?php if(!empty($sum_poll_num['sum_votenum'])){?>
		<script type='text/javascript'>
			function incr(){
				var width_val=parseInt(document.getElementById("poll_bg_<?php echo $default_id;?>").style.width);
				if(width_val>=140*<?php echo $rs['votenum']/$sum_poll_num['sum_votenum'];?>){
					window.clearInterval("padding_option_<?php echo $default_id;?>");
				}else{
					document.getElementById("poll_bg_<?php echo $default_id;?>").style.width=width_val+3+"px";
				}
			}
			var color_type=Array("b1.gif","b2.gif","b3.gif","b4.gif","b5.gif","b6.gif","b7.gif","b8.gif","b9.gif","b10.gif","b1.gif");
			var color_s=Math.round(Math.random()*10);
			var url = "url(skin/<?php echo $skinUrl;?>/images/vote/";
			document.getElementById("poll_bg_<?php echo $default_id;?>").style.backgroundImage = url + color_type[color_s] +')';
			var padding_option_<?php echo $default_id;?>=window.setInterval(incr,30);
		</script>
	<?php }?>
	<?php $default_id++;?>
	<?php }?>
	<div class="poll_button">
		<input type='submit' class='small-btn' name='action' value='<?php echo $pol_langpackage->pol_title;?>' />&nbsp;&nbsp;<input type='checkbox' value='1' style="vertical-align:middle" name='anonymity' /> <?php echo $pol_langpackage->pol_anon;?>
	</div>
	</form>
<div class="clear"></div>
<!--总结报告!-->
	<div class='poll_summary <?php echo $show_sumary;?>'>
		<?php echo str_replace("{name}",filt_word($poll_row['username']),$pol_langpackage->pol_sumary);?>
		<div class='poll_summary_content'>
			<div class="left_g left"></div><div class="center_g left"><?php echo filt_word($poll_row['summary']);?></div><div class="right_g left"></div>
		</div>
	</div>
</div>

<!--最新投票区!-->
	<div class="container <?php echo $show_poll_info;?>">
		<div class="rs_head"><?php echo $pol_langpackage->pol_info;?></div>
	</div>

	<ul class="poll_list <?php echo $show_poll_info;?>">
	<?php foreach($p_put_info as $rs){?>
		<li class='poll_credit'><?php echo anon_poll($rs['anony'],$rs['uid'],$rs['username']);?> <?php echo str_replace(array("{date}","{option}"),array(format_datetime_txt($rs['dateline']),$rs['option']),$pol_langpackage->pol_input_info);?></li>
	<?php }?>
	</ul>

	<div class="tleft ml20 mt20">
		<div class="comment">
	    <div id='show_4_<?php echo $poll_row["p_id"];?>'>
	    	<script type='text/javascript'>parent.get_mod_com(4,<?php echo $poll_row['p_id'];?>,0,20);</script>
	    </div>
	    <?php if($ses_uid!=''){?>
			<div class="reply">
				<img class="figure"  src='<?php echo get_sess_userico();?>' onerror="parent.pic_error(this)" />
				<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)" istyle="height:50px" id="reply_4_<?php echo $poll_row['p_id'];?>_input"></textarea></p>
				<div class="replybt">
					<input class="left button" onclick="parent.restore_com(<?php echo $poll_row['user_id'];?>,4,<?php echo $poll_row['p_id'];?>);show('face_list_menu',200)" type="submit" name="button" id="button" value="<?php echo $pol_langpackage->pol_submit;?>" />
					<a id="reply_a_<?php echo $poll_row['p_id'];?>_input" class="right" href="javascript:void(0);" onclick=" showFace(this,'face_list_menu','reply_4_<?php echo $poll_row['p_id'];?>_input');"><?php echo $pol_langpackage->pol_face;?></a>
				</div>
				<div class="clear"></div>
			</div>
			<?php }?>
		</div>
	</div>
	<?php }?>
<div id="face_list_menu" class="emBg" style="display:none;z-index:100;"></div>
<div class="guide_info <?php echo $show_error;?>"><?php echo $error_str;?></div>
</body>
</html>
