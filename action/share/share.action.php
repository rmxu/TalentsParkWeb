<?php
//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("foundation/module_share.php");
require("foundation/aintegral.php");
require("api/base_support.php");
require("foundation/ftag.php");

//引入语言包
$s_langpackage=new sharelp;

//变量声明区
	$user_id=get_sess_userid();
  $s_comment=short_check(get_argp('comment'));
	$s_type=short_check(get_argg('s_type'));
	$s_content_id=intval(get_argg('share_content_id'));
	$title_data=short_check(get_argp('title_data'));
	$re_link=short_check(get_argp('re_link'));
	$re_thumb=get_session('m_thumb');
	$re_m_link=get_session('m_link');
	$tag=short_check(get_argp('tag'));

	//数据表定义
	$t_share=$tablePreStr."share";

  //初始化数据库操作对象
  $dbo=new dbex;
	dbtarget('r',$dbServs);
	if(empty($re_link)){
		$col=" and for_content_id='$s_content_id' ";
	}else{
		$col=" and out_link= '$re_link' ";
	}
	$sql="select s_id from $t_share where user_id=$user_id and type_id=$s_type $col";
	$is_share=$dbo->getRow($sql);
	if(!empty($is_share)){
		echo $s_langpackage->s_is_share;exit;
	}

	switch($s_type){
		case "0":
		$rs = api_proxy("blog_self_by_bid","user_id,user_name,log_title,log_content",$s_content_id);
		if($rs['user_id']==$user_id){
			echo $s_langpackage->s_no_share;
		}else{
			$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
			$title_data=$title_data ? $title_data:$rs['log_title'];
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_log.'<a href="home.php?h='.$rs['user_id'].'&app=blog&id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
			$content=get_blog_lentxt($rs['log_content']);
		}
		break;

		case "1":
		$rs = api_proxy("group_self_by_gid","add_userid,group_name,group_creat_name,group_resume",$s_content_id);
		if($rs['add_userid']==$user_id){
			echo $s_langpackage->s_no_share;
		}else{
			$user_name='<a href="home.php?h='.$rs['add_userid'].'" target="_blank">'.$rs['group_creat_name'].'</a>';
			$title_data=$title_data ? $title_data:$rs['group_name'];
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_group.'<a href="home.php?h='.$rs['add_userid'].'&app=group_space&group_id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
			$content=get_blog_lentxt($rs['group_resume']);
		}
		break;

		case "2":
		$rs = api_proxy("album_self_by_aid","album_name,user_id,user_name,album_skin",$s_content_id);
		if($rs['user_id']==$user_id){
			echo $s_langpackage->s_no_share;
		}else{
			$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
			$title_data=$title_data ? $title_data:$rs['album_name'];
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_album.'<a href="home.php?h='.$rs['user_id'].'&app=photo_list&album_id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
			$content='<a href="home.php?h='.$rs['user_id'].'&app=photo_list&album_id='.$s_content_id.'" target="_blank"><img class="photo_frame" onerror=parent.pic_error(this) src="'.$rs['album_skin'].'" /></a>';
		}
		break;

		case "3":
		$rs = api_proxy("album_photo_by_photoid","album_id,user_id,photo_name,photo_thumb_src,user_name",$s_content_id);
		if($rs['user_id']==$user_id){
			echo $s_langpackage->s_no_share;
		}else{
			$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
			$title_data=$title_data ? $title_data:$rs['photo_name'];
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_photos.'<a href="home.php?h='.$rs['user_id'].'&app=photo&photo_id='.$s_content_id.'&album_id='.$rs['album_id'].'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
			$content='<a href="home.php?h='.$rs['user_id'].'&app=photo&photo_id='.$s_content_id.'&album_id='.$rs['album_id'].'" target="_blank"><img class="photo_frame" onerror=parent.pic_error(this) src="'.$rs['photo_thumb_src'].'"/></a>';
		}
		break;

		case "4":
		$rs = api_proxy("poll_self_by_pollid","user_id,username,subject,`option`,multiple",$s_content_id);
		if($rs['user_id']==$user_id){
			echo $s_langpackage->s_no_share;
		}else{
			$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['username'].'</a>';
			$title_data=$title_data ? $title_data:$rs['subject'];
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_vote.'<a href="home.php?h='.$rs['user_id'].'&app=poll&p_id='.$s_content_id.'" target="_blank">'.$rs['subject'].'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
			$input_type=($rs['multiple']==1)?'checkbox':'radio';
			$option_array=unserialize($rs['option']);
			$content="<input type=".$input_type." disabled />".$option_array[0]."<br /><input type=".$input_type." disabled />".$option_array[1]."<br />......";
		}
		break;

		case "5":
			$user_name='';
			if($s_content_id){
				$rs=api_proxy("share_self_by_sid","s_id,user_id,user_name,s_title",$s_content_id);
				$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
				$title_data=$title_data ? $title_data:$rs['s_title'];
				$re_link=$re_link ? $re_link:$rs['out_link'];
			}
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_link.'<a href="'.$re_link.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag,$re_link);
			$content='<a href="'.$re_link.'" target="_blank">'.$re_link.'</a>';
		break;

		case "6":
			$user_name='';
			$uid=$user_id;
			if($s_content_id){
				$rs=api_proxy("share_self_by_sid","s_id,user_id,user_name,s_title,out_link",$s_content_id);
				$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
				$title_data=$title_data ? $title_data:$rs['s_title'];
				$re_link=$re_link ? $re_link:$rs['out_link'];
				$uid=$rs['user_id'];
			}else{
				$sql="select max(s_id) as max from $t_share";
				$share_last=$dbo->getRow($sql);
				$s_content_id=$share_last['max']+1;
			}
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_music.'<a href="home.php?h='.$uid.'&app=share_show&s_id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag,$re_link);
			$content='<div class="music_player"><a href="home.php?h='.$user_id.'&app=share_show&s_id='.$share_id.'" target="_blank"></a></div>';
		break;

		case "7":
		$user_name='';
		$uid=$user_id;
		if($s_content_id){
			$rs=api_proxy("share_self_by_sid","movie_thumb,movie_link,s_title,user_name,user_id",$s_content_id);
			$re_thumb=$rs['movie_thumb'];
			$re_m_link=$rs['movie_link'];
			$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
			$title_data=$title_data ? $title_data:$rs['s_title'];
			$uid=$rs['user_id'];
		}else{
			$sql="select max(s_id) as max from $t_share";
			$share_last=$dbo->getRow($sql);
			$s_content_id=$share_last['max']+1;
		}
			$re_thumb=$re_thumb?$re_thumb:"skin/$skinUrl/images/movie_def.gif";
			$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_video.'<a href="home.php?h='.$uid.'&app=share_show&s_id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
			$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag,$re_link,$re_thumb,$re_m_link);
			$content='<div style="background:url('.$re_thumb.') no-repeat scroll 0% 0% transparent;" class="movie_player"><a href="home.php?h='.$user_id.'&app=share_show&s_id='.$share_id.'" target="_blank"></a></div>';
		break;

		case "8":
			$rs=api_proxy("group_sub_by_sid","group_id,user_id,user_name,title,content",$s_content_id);
			if($rs['user_id']==$user_id){
				echo $s_langpackage->s_no_share;
			}else{
				$user_name='<a href="home.php?h='.$rs['user_id'].'" target="_blank">'.$rs['user_name'].'</a>';
				$title_data=$title_data ? $title_data:$rs['title'];
				$title=$s_langpackage->s_shared.$user_name.$s_langpackage->s_s_topic.'<a href="home.php?h='.$rs['user_id'].'&app=group_sub_show&group_id='.$rs['group_id'].'&subject_id='.$s_content_id.'" target="_blank">'.$title_data.'</a>';
				$share_id=share_act($dbo,$s_type,$s_content_id,$title,$tag);
				$content=get_blog_lentxt($rs['content']);
				break;
			}
		}

	//标签功能
	$tag_id=tag_add($tag);
	$tag_state=tag_relation(3,$tag_id,$share_id);
	$content.=$s_comment ? '<div class="clear"></div><div class="left_g left"></div><div class="center_g left">'.$s_comment.'</div><div class="right_g left"></div><div class="clear"></div>':'';

	//插入动态表
	$is_suc=api_proxy("message_set",$share_id,$title,$content,4,5);
	if($is_suc){
		$sql="update $t_share set content='$content' where s_id=$share_id";
		$is_update=$dbo->exeUpdate($sql);
		increase_integral($dbo,$int_share,$user_id);
		echo "success";
	}else{
		echo $s_langpackage->s_share_los;
	}
?>