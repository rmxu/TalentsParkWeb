<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/recentaffair/rec_affair.html
 * 如果您的模型要进行修改，请修改 models/modules/recentaffair/rec_affair.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	require("foundation/fcontent_format.php");
	
	//引入语言包
	$rf_langpackage=new recaffairlp;

	//变量取得
	$ra_type=intval(get_argg('t'));
	$user_id=get_sess_userid();
	$pals_id=get_sess_mypals();
	$start_num=intval(get_argg('start_num'));
	$hidden_pals_id=get_session('hidden_pals');
	$hidden_type_id=get_session('hidden_type');
	$holder_id=intval(get_argg('user_id'));
	$visitor_ico=get_sess_userico();
	$ra_type_str=$ra_type ? " and type_id=$ra_type " : "";
	$pals_str='';
	$limit_num=0;
	$ra_rs = array();
	
	//数据表定义区
	$t_rec_affair=$tablePreStr."recent_affair";
	
	//数据库读操作
	$dbo=new dbex;
	dbtarget('r',$dbServs);	
	
	if($holder_id!=''){//home新鲜事
		$limit_num=$homeAffairNum;
		$hidden_button_over="void(0)";
		$hidden_button_out="void(0)";
	  $sql = "select * from $t_rec_affair where user_id=$holder_id order by id desc limit $start_num , $limit_num";
	  $ra_rs = $dbo->getRs($sql);
	}else{//main新鲜事
		if($pals_id){
			$limit_num=$mainAffairNum;
			$ra_mod_type='';
		  $hidden_button_over="feed_menu({id},1);";
		  $hidden_button_out="feed_menu({id},0);";
		  
		  //屏蔽某人新鲜事
			if($hidden_pals_id!='' && $hidden_pals_id!=','){
				$pals_str=",".$pals_id.",";
				$hidden_pals_array=explode(",",$hidden_pals_id);
				foreach($hidden_pals_array as $rep){
					if($rep!=''){
						$pals_str=str_replace(",".$rep.",",",",$pals_str);
					}
				}
				$pals_str=preg_replace(array("/^,/","/,$/"),"",$pals_str);
				$pals_id=$pals_str;
			}
			
			//屏蔽某类新鲜事
			if($hidden_type_id!='' && $hidden_type_id!=','){
				$hidden_type_id=preg_replace(array("/^,/","/,$/"),"",$hidden_type_id);
				$ra_mod_type=" and mod_type not in ($hidden_type_id)";
			}
			
			//是否开启更多
			$is_more=intval($start_num/5);
			if($is_more){
				$pals_id_array=array_slice(explode(',',$pals_id),$is_more*$mainAffairNum,$mainAffairNum);
			}else{
				$pals_id_array=explode(',',$pals_id,$mainAffairNum+1);
			}
			if(isset($pals_id_array[0])){
				foreach($pals_id_array as $p_id){
					if(strpos($p_id,',')) break;
				  $sql = "select * from $t_rec_affair where user_id=$p_id $ra_type_str $ra_mod_type order by id desc limit 0,3";
				  $ra_rs_part=$dbo->getRs($sql);
				  $ra_rs=array_merge($ra_rs,$ra_rs_part);
				}
			}
		}
	}
?><?php foreach($ra_rs as $rs){?>
<li id="feed_<?php echo $rs['id'];?>" onmouseover="<?php echo str_replace('{id}',$rs['id'],$hidden_button_over);?>" onmouseout="<?php echo str_replace('{id}',$rs['id'],$hidden_button_out);?>;">
	<a id="a_feed_menu_<?php echo $rs['id'];?>" class="popbtn" href="javascript:void(0);" onclick="ajaxmenu(this, this.id,<?php echo $rs['user_id'];?>,<?php echo $rs['mod_type'];?>)" style="display: none;"></a>
	<div class="avatar">
		<a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank" title="<?php echo $rf_langpackage->rf_v_home;?>"><img src='<?php echo $rs["user_ico"];?>' /></a>
	</div>
  <div class="details">
  	<h3><a href="home.php?h=<?php echo $rs['user_id'];?>" target="_blank" title="<?php echo $rf_langpackage->rf_v_home;?>"><?php echo filt_word($rs["user_name"]);?></a><?php echo $rs['title'];?></h3>
    <div class="content">
    	<?php echo filt_word(get_face($rs['content']));?>
	</div>
		<div class="toolbar toolbar_<?php echo $rs['mod_type'];?>">
			<span>(<?php echo format_datetime_txt($rs['date_time']);?>)</span>
			<?php if($rs['for_content_id']!=0){?><a onclick=toggle("replycontent",<?php echo $rs['mod_type'];?>,<?php echo $rs['for_content_id'];?>) id="openreply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>" href="javascript:void(0);"><?php echo $rf_langpackage->rf_re_com;?></a><?php }?>
		</div>
		<div id='replycontent_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>'>
		<?php if($rs['for_content_id']!=0){?>
    <div class="comment">
			<div id="show_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>"><script>parent.get_mod_com(<?php echo $rs['mod_type'];?>,<?php echo $rs['for_content_id'];?>,0,3);</script></div>
    <?php if($user_id!=''){?>
    <div id="reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>_1" class="replyer"><input onclick='toggle2("reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>")' name="input" value="<?php echo $rf_langpackage->rf_add_com;?>" type="text"></div>
		<div id="reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>_2" class="reply" style="display: none;">
			<img class="figure" src="<?php echo $visitor_ico;?>">
			<p><textarea type="text" maxlength="150" onkeyup="return isMaxLen(this)" id="reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>_input" onblur=toggle2("reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>")></textarea></p>
			<div class="replybt">
				<input class="left button" onclick="parent.restore_com(<?php echo $rs['user_id'];?>,<?php echo $rs['mod_type'];?>,<?php echo $rs['for_content_id'];?>);" type="submit" name="button" id="button" value="<?php echo $rf_langpackage->rf_submit;?>" />
				<a class="right" href="javascript: void(0);" onclick="showim(''); showFace(this,'face_list_menu','reply_<?php echo $rs['mod_type'];?>_<?php echo $rs['for_content_id'];?>_input');"><?php echo $rf_langpackage->rf_face;?></a>
			</div>
			<div class="clear"></div>
		</div>
		<?php }?>
	</div>
		<?php }?>
		</div>
  </div>
</li>
<?php }?>