<?php
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
?>