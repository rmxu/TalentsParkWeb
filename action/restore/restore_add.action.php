<?php
//引入语言包
	$pu_langpackage=new publiclp;
	$res_langpackage=new restore;
	$RefreshType='ajax';
//引入公共方法
	require("foundation/aintegral.php");
	require("api/base_support.php");
	require("foundation/aanti_refresh.php");

//变量定义区
	$visitor_id=get_sess_userid();
	$visitor_name=get_sess_username();
	$visitor_ico=get_sess_userico();
	$content=short_check(get_argp('CONTENT'));
	$host_id=intval(get_argg('holder_id'));
	$to_userid=intval(get_argg('to_userid'));
	$mod_id=intval(get_argg('mod_id'));
	$type_id=intval(get_argg('type_id'));
	$hidden_talk=intval(get_argg('hidden_talk'));
	$is_true=0;
	antiRePost($content);
  if(strlen($content) >=500){
		echo $pu_langpackage->pu_serp;exit;
	}

//数据表定义
  $t_share=$tablePreStr."share";
  $t_share_comment=$tablePreStr."share_comment";

	$t_poll=$tablePreStr."poll";
	$t_poll_comment = $tablePreStr."poll_comment";

	$t_album=$tablePreStr."album";
  $t_album_comment = $tablePreStr."album_comment";

	$t_photo=$tablePreStr."photo";
  $t_photo_comment = $tablePreStr."photo_comment";

  $t_blog=$tablePreStr."blog";
  $t_blog_comment=$tablePreStr."blog_comment";

  $t_group=$tablePreStr."groups";
  $t_subject=$tablePreStr."group_subject";
  $t_subject_comment=$tablePreStr."group_subject_comment";

  $t_mood=$tablePreStr."mood";
  $t_mood_comment=$tablePreStr."mood_comment";

  $t_users=$tablePreStr."users";
  $t_mypals=$tablePreStr."pals_mine";

  $dbo=new dbex();

		switch($type_id){
			case "0":
			$t_table=$t_blog;
			$t_table_com=$t_blog_comment;
			$mod_col_insert="log_id";
			$mod_value_insert=$mod_id;
			$num_col="log_id";
			$blog_title=api_proxy("blog_self_by_bid","log_title",$mod_id);
			$title=$blog_title['log_title'];
			$whole=$res_langpackage->res_blog;
			$link="home.php?h=$host_id&app=blog&id=$mod_id";
			break;
			case "1":
			$group_info=array();
			$group_info=api_proxy("group_sub_by_sid","group_id,title",$mod_id);
			$group_id=$group_info['group_id'];
			$title=$group_info['title'];
			dbtarget('w',$dbServs);
			$sql="update $t_group set comments=comments+1 where group_id=$group_id";
			$dbo->exeUpdate($sql);
			$t_table=$t_subject;
			$t_table_com=$t_subject_comment;
			$mod_col_insert="subject_id,group_id";
			$mod_value_insert=$mod_id.",".$group_id;
			$num_col="subject_id";
			$whole=$res_langpackage->res_subject;
			$link="home.php?h=$host_id&app=group_sub_show&subject_id=$mod_id&group_id=$group_id";
			break;
			case "2":
			$t_table=$t_album;
			$t_table_com=$t_album_comment;
			$mod_col_insert="album_id";
			$mod_value_insert=$mod_id;
			$num_col="album_id";
			$album_title=api_proxy("album_self_by_aid","album_name",$mod_id);
			$title=$album_title['album_name'];
			$whole=$res_langpackage->res_album;
			$link="home.php?h=$host_id&app=photo_list&album_id=$mod_id";
			break;
			case "3":
			$photo_info=array();
			$t_table=$t_photo;
			$t_table_com=$t_photo_comment;
			$mod_col_insert="photo_id";
			$mod_value_insert=$mod_id;
			$num_col="photo_id";
			$photo_info=api_proxy("album_photo_by_photoid","album_id,photo_name",$mod_id);
			$title=$photo_info['photo_name'];
			$whole=$res_langpackage->res_photo;
			$link="home.php?h=$host_id&app=photo&photo_id=$mod_id&album_id=".$photo_info['album_id'];
			break;
			case "4":
			$t_table=$t_poll;
			$t_table_com=$t_poll_comment;
			$mod_col_insert="p_id";
			$mod_value_insert=$mod_id;
			$num_col="p_id";
			$poll_info=api_proxy("poll_self_by_pollid","subject",$mod_id);
			$title=$poll_info['subject'];
			$whole=$res_langpackage->res_poll;
			$link="home.php?h=$host_id&app=poll&p_id=$mod_id";
			break;
			case "5":
			$t_table=$t_share;
			$t_table_com=$t_share_comment;
			$mod_col_insert="s_id";
			$mod_value_insert=$mod_id;
			$num_col="s_id";
			$share_info=api_proxy("share_self_by_sid","s_title",$mod_id);
			preg_match("/<a href=\"[^\"]*\" target=\"_blank\">([^<]*)<\/a>/",$share_info['s_title'],$take_data);
			$title=$take_data[1];
			$whole=$res_langpackage->res_share;
			$link="home.php?h=$host_id&app=share_list&remind=1&mod=$mod_id";
			break;
			case "6":
			$t_table=$t_mood;
			$t_table_com=$t_mood_comment;
			$mod_col_insert="mood_id";
			$mod_value_insert=$mod_id;
			$num_col="mood_id";
			$mood_info=api_proxy("mood_self_by_mid","mood",$mod_id);
			$title=$mood_info['mood'];
			$whole=$res_langpackage->res_mood;
			$link="home.php?h=$host_id&app=mood_more&remind=1&mod=$mod_id";
			break;
			default:
			echo 'error';
			break;
		}

//定义读操作
if($host_id!=$visitor_id){
	$user_info=api_proxy("user_self_by_uid","inputmess_limit",$host_id);
	if($user_info['inputmess_limit']){
		if($user_info['inputmess_limit']==2){
			echo $pu_langpackage->pu_not_com.'<br/>';exit;
		}else if($user_info['inputmess_limit']==1){
			if(!api_proxy("pals_self_isset",$host_id)){
				echo $pu_langpackage->pu_only_fri_com.'<br/>';exit;
			}
		}
	}
}

	dbtarget('w',$dbServs);
	$last_id='';
	$sql="insert into $t_table_com (visitor_id,visitor_name,host_id,$mod_col_insert,add_time,`content`,visitor_ico,is_hidden) values ($visitor_id,'$visitor_name',$host_id,$mod_value_insert,now(),'$content','$visitor_ico',$hidden_talk)";
	if($dbo->exeUpdate($sql)){
		$last_id=mysql_insert_id();
		$sql="update $t_table set comments=comments+1 where $num_col=$mod_id";
		$is_true=$dbo->exeUpdate($sql);
		$remind_content=str_replace("{title}","<a href=\'{link}\' onclick=\'{js}\' target=\'_blank\'>".sub_str($title,45)."</a>",$whole);
		if($visitor_id!=$host_id){
			$focus=api_proxy("message_get_remind_count",$host_id);
			if($focus[0]>=20){
				api_proxy("message_set_del","remind",'',$host_id);
			}
			api_proxy("message_set",$host_id,$remind_content,$link,1,0,"remind");
		}
		if($to_userid){
			$focus=api_proxy("message_get_remind_count",$to_userid);
			if($focus[0]>=20){
				api_proxy("message_set_del","remind",'',$to_userid);
			}
			api_proxy("message_set",$to_userid,$remind_content,$link,1,0,"remind");
		}
	}
	increase_integral($dbo,$int_com_msg,$visitor_id);

	if($last_id){
		echo '
			<div class="reaction" id="record_'.$type_id.'_'.$last_id.'">';
				if($visitor_id==$host_id){
					echo '<a href="javascript:parent.del_com('.$host_id.','.$type_id.','.$mod_id.','.$last_id.','.$visitor_id.')" class="replyclose"></a>';
				}
		echo '
				<a class="figure" href="home.php?h='.$visitor_id.'" target="_blank"><img src="'.$visitor_ico.'"/></a>
				<a href="home.php?h='.$visitor_id.'" target="_blank">'.filt_word($visitor_name).'</a><label>'.date("Y-m-d H:i:s",time()).'</label>
				<p class="left">'.filt_word(get_face($content)).'</p>
			</div>';
	}
?>

