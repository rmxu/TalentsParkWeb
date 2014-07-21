<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/restore/get_restore.html
 * 如果您的模型要进行修改，请修改 models/modules/restore/get_restore.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$pu_langpackage=new publiclp;

	//变量取得
	$mod_id=intval(get_argg('mod_id'));
	$type_id=intval(get_argg('type_id'));
	$start_num=short_check(get_argg('start_num'));
	$show_num=short_check(get_argg('end_num'));

	$dbo = new dbex;
	dbtarget('r',$dbServs);

  $t_share=$tablePreStr."share";
  $t_share_comment=$tablePreStr."share_comment";

	$t_poll=$tablePreStr."poll";
	$t_poll_comment=$tablePreStr."poll_comment";

	$t_album=$tablePreStr."album";
  $t_album_comment = $tablePreStr."album_comment";

	$t_photo=$tablePreStr."photo";
  $t_photo_comment = $tablePreStr."photo_comment";

  $t_blog=$tablePreStr."blog";
  $t_blog_comment=$tablePreStr."blog_comment";

  $t_subject=$tablePreStr."group_subject";
  $t_subject_comment=$tablePreStr."group_subject_comment";

  $t_mood=$tablePreStr."mood";
  $t_mood_comment=$tablePreStr."mood_comment";

switch($type_id){
		case "0":
		$t_table=$t_blog;
		$t_table_com=$t_blog_comment;
		$mod_col="log_id";
		break;
		case "1":
		$t_table=$t_subject;
		$t_table_com=$t_subject_comment;
		$mod_col="subject_id";
		break;
		case "2":
		$t_table=$t_album;
		$t_table_com=$t_album_comment;
		$mod_col="album_id";
		break;
		case "3":
		$t_table=$t_photo;
		$t_table_com=$t_photo_comment;
		$mod_col="photo_id";
		break;
		case "4":
		$t_table=$t_poll;
		$t_table_com=$t_poll_comment;
		$mod_col="p_id";
		break;
		case "5":
		$t_table=$t_share;
		$t_table_com=$t_share_comment;
		$mod_col="s_id";
		break;
		case "6":
		$t_table=$t_mood;
		$t_table_com=$t_mood_comment;
		$mod_col="mood_id";
		break;
		default:
		echo 'error';
		break;
	}
	$function="parent.get_mod_com(".$type_id.",".$mod_id.",".intval($show_num+$start_num).",10);document.getElementById('page_".$type_id."_".$mod_id."').parentNode.style.display='none';document.getElementById('page_".$type_id."_".$mod_id."').parentNode.innerHTML='';";
	$visitor_id=get_sess_userid();
	$info_row=array();
  $com_rs=array();
	$show_str=intval($start_num+$show_num);
	$sql="select comments,user_id from $t_table where $mod_col=$mod_id";
	$info_row=$dbo->getRow($sql);
	$is_show=0;
	if($info_row['comments']>0){
		$is_show=1;
		$sql="select * from $t_table_com where $mod_col=$mod_id order by `comment_id` desc limit $start_num,$show_num";
		$com_rs=$dbo->getRs($sql);
		if($info_row['comments'] <= $start_num+$show_num){
			$show_str=intval($info_row['comments']);
			$function="void(0)";
		}
	}
?><?php if($is_show==1){?>
	<?php foreach($com_rs as $rs){?>
		<div class="reaction" id="record_<?php echo $type_id;?>_<?php echo $rs["comment_id"];?>">
			<?php if($visitor_id==$rs['host_id']){?>
				<a href="javascript:parent.del_com(<?php echo $rs["host_id"];?>,<?php echo $type_id;?>,<?php echo $mod_id;?>,<?php echo $rs["comment_id"];?>,<?php echo $rs["visitor_id"];?>)" class="replyclose"></a>
			<?php }?>
			<a href="home.php?h=<?php echo $rs["visitor_id"];?>" target="_blank" class="figure"><img src="<?php echo $rs["visitor_ico"];?>" /></a>
			<a href="home.php?h=<?php echo $rs["visitor_id"];?>" target="_blank"><?php echo filt_word($rs["visitor_name"]);?></a><label><?php echo $rs["add_time"];?>
			<?php if($visitor_id!=$rs["visitor_id"]){?>
				<a href="javascript:void(0)" onclick=parent.restore("<?php echo $rs['visitor_name'];?>",<?php echo $type_id;?>,<?php echo $mod_id;?>,<?php echo $rs["visitor_id"];?>)><?php echo $pu_langpackage->pu_re;?></a>
			<?php }?>
			</label>
			<p class="left"><?php echo filt_word(get_face($rs["content"]));?></p>
		</div>
	<?php }?>
	<div class="stat"><a href="javascript:void(0)" id="page_<?php echo $type_id;?>_<?php echo $mod_id;?>" onclick="<?php echo $function;?>"><?php echo $pu_langpackage->pu_total_com;?><span id="total_<?php echo $type_id;?>_<?php echo $mod_id;?>"><?php echo $info_row["comments"];?></span><?php echo $pu_langpackage->pu_one_pi;?>，<?php echo $pu_langpackage->pu_local_show;?><span id="max_<?php echo $type_id;?>_<?php echo $mod_id;?>"><?php echo $show_str;?></span><?php echo $pu_langpackage->pu_one_pi;?></a></div>
<?php }?>