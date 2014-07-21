<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/invite.html
 * 如果您的模型要进行修改，请修改 models/modules/invite.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入公共方法
	require("foundation/module_mood.php");

//引入语言包
	$u_langpackage=new userslp;
	$ah_langpackage=new arrayhomelp;

 //变量获得
  $holder_id=intval(get_argg('uid'));//主人id

	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_online=$tablePreStr."online";

	$dbo=new dbex;
	//读写分离定义方法
	dbtarget('r',$dbServs);

	$info_item_init=$u_langpackage->u_unset;
  $user_info=get_user_info($dbo,$t_users,$holder_id);
  $user_sex_txt=get_user_sex($user_info['user_sex']);
  $user_lastlogin_time=$user_info['lastlogin_datetime'];

  if(!$user_info){
  	 echo $u_langpackage->u_not_invite;exit;
  }

	$user_online=get_user_online_state($dbo,$t_online,$holder_id);
  $ol_state_ico="skin/$skinUrl/images/offline.gif";
  $ol_state_label=$u_langpackage->u_not_onl.'('.format_datetime_short($user_lastlogin_time).')';
  if($user_online['hidden']==='0'){
	  $ol_state_ico="skin/$skinUrl/images/online.gif";
	  $ol_state_label=$u_langpackage->u_onl;
  }
  
  //设置发出邀请用户的id
  set_session("InviteFromUid",$user_info['user_id']);
?><link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/layout.css">
<div class="recom_user" style="margin-bottom:-20px">
   	<div id="invite">
        <div class="top"><h2 class="highlight"><?php echo $ah_langpackage->ah_welcome_you;?><a class="highlight"  href="home.php?h=<?php echo $user_info['user_id'];?>" target="_blank"><?php echo $user_info['user_name'];?></a><?php echo $ah_langpackage->ah_invite_you_friends;?></h2></div>
        <div class="clear"></div>
        <div class="invite_text">
        <?php echo $ah_langpackage->ah_after_friend;?>
        </div>
	   	<table cellpadding="10" cellspacing="0" class="invite_table">
	   		<tr>
	   			<td width="28%" valign="top" align="left">
	   				<a class="photo_iframe" href="home.php?h=<?php echo $user_info['user_id'];?>">
                    <img src="<?php echo str_replace("_small","",$user_info['user_ico']);?>" /></a>
	   			</td>
	   			<td width="72%" valign="top">
		   			 <?php echo $u_langpackage->u_uname;?>：
		   			 	<a href="home.php?h=<?php echo $user_info['user_id'];?>">
		   			 		<span class="highlight"><b><?php echo $user_info["user_name"];?></b></span>
		   			 	</a><img src=<?php echo $ol_state_ico;?> />
						 <span id='ol_label_txt' class="gray"><?php echo $ol_state_label;?></span><br/>
					     <span id='ol_label_txt' class="gray">
					    	<?php echo get_uhome_url($holder_id);?>
					     </span>
				     <p> 	<?php echo str_replace(array("{visitor_num}","{integral}"),array($user_info['guest_num'],$user_info['integral']),$u_langpackage->u_ustate);?>
				     </p>
				     <?php echo $u_langpackage->u_sex;?>：<?php echo info_item_format($info_item_init,$user_sex_txt);?><br />	   			
	   			      <?php echo $u_langpackage->u_from;?>：<?php echo info_item_format($info_item_init,$user_info["reside_province"].$user_info["reside_city"]);?>
                     <p class="mt20"><a href="home.php?h=<?php echo $user_info['user_id'];?>" ><img src="skin/<?php echo $skinUrl;?>/images/tohomelabel.gif" align="middle"></a></p>
	   			</td>
	   		</tr>
	   </table>
	</div>   
</div>
<div class="clear"></div>