<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/poll/poll_show_config.html
 * 如果您的模型要进行修改，请修改 models/modules/poll/poll_show_config.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
//引入模块公共方法文件
require("foundation/module_poll.php");
require("api/base_support.php");

//引入语言包
$pol_langpackage=new polllp;

//变量声明区
	$user_id=get_sess_userid();
	$set_option=short_check(get_argg('set_option'));
  $pid=intval(get_argg('pid'));
  $poll_info=array();
  
  if($set_option=="add_award"){
		$u_int = api_proxy("user_self_by_uid","integral",$user_id);
	}
  $poll_info = api_proxy("poll_self_by_pollid","*",$pid);
?>
<style type="text/css">
.mini_button{background:url(servtools/dialog/images/button.gif)  no-repeat;width:58px;height:24px; margin-right:5px;line-height:24px;cursor:pointer;overflow:hidden;border:0;}
.bottom_td{border-top: 1px solid #DADEE5; padding: 8px 20px; text-align: right; background-color:#f6f6f6;}
td.showdialog { text-align:left; padding:0px 0 15px 15px;}
</style>
	<?php if($set_option=="stop_award"){?>
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_grant_end;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_grant_back;?></td></tr>
		<tr><td class="bottom_td"><input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick="frame_content.action_set_config('<?php echo $set_option;?>','<?php echo $pid;?>');" />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>
	<?php }?>
	
	<?php if($set_option=="add_award"){?>
		<tr><td class="showdialog"><table id='award_table'>
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_add_grant;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_add_grant_total;?>：<input type='text' size=5 id='add_award_sum' value='' />（0~<?php echo $u_int['integral'];?>）</td></tr>
		<?php if($poll_info['percredit'] < 10){?>
			<tr><td class="showdialog"><?php echo $pol_langpackage->pol_add_grant_singular;?>：<input type='text' size=5 id='add_award_sing' value='' />（<?php echo str_replace("{max_p}",10-$poll_info['percredit'],$pol_langpackage->pol_r_point);?>）</td></tr>
		<?php }?>
		</table></td></tr>
        <tr><td class="bottom_td"><input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick='frame_content.action_set_config("<?php echo $set_option;?>",<?php echo $pid;?>);' />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>		
	<?php }?>
		
	<?php if($set_option=="add_option"){?>	
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_add_option;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_add_new_option;?>：</td></tr>
		<tr><td class="showdialog"><input type='text' id='add_new_option' /></td></tr>
		<tr><td class="bottom_td"><input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick='frame_content.action_set_config("<?php echo $set_option;?>",<?php echo $pid;?>);' />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>
	<?php }?>
		
	<?php if($set_option=="del_poll"){?>	
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_del;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_del_con;?></td></tr>
		<tr><td class="bottom_td"><input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick='frame_content.action_set_config("<?php echo $set_option;?>",<?php echo $pid;?>);' />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>
	<?php }?>
		
	<?php if($set_option=="change_date"){?>	
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_amend_end_time;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_new_end_time;?>：</td></tr>
        <tr><td class="showdialog"><input type='text' readonly id='expiration' style='width:80px' value="<?php echo date('Y-m-d',time()+60*60*720);?>" onclick='calendar(this)' /></td></tr>
		<tr><td class="bottom_td">
		
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick='frame_content.action_set_config("<?php echo $set_option;?>",<?php echo $pid;?>);' />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>	
	<?php }?>
		
	<?php if($set_option=="add_summary"){?>	
		<tr><td class="showdialog"><b><?php echo $pol_langpackage->pol_sum;?></b></td>
		<tr><td class="showdialog"><?php echo $pol_langpackage->pol_write_sum;?>：</td></tr>
		<tr><td class="showdialog"><textarea style="width:92%" rows='5' id='add_summary'><?php echo $poll_info['summary'];?></textarea></td></tr>
		<tr><td class="bottom_td"><input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_con;?>' onclick='frame_content.action_set_config("<?php echo $set_option;?>",<?php echo $pid;?>);' />
		<input type='button' class='mini_button' value='<?php echo $pol_langpackage->pol_cancel;?>' onclick='Dialog.close();' /></td></tr>
	<?php }?>
