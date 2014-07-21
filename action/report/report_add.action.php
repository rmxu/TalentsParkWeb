<?php
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/aintegral.php");
	require("api/base_support.php");

	//数据表定义区
	$t_report=$tablePreStr."report";

	//引入语言包
	$rp_langpackage=new reportlp;
	
	$dbo = new dbex;

	//变量区
	$type = intval(get_argg('type'));
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$userd_id = intval(get_argg('uid'));
	$reason = short_check(get_argp('reason'));
	$mod_id=intval(get_argg('mod_id'));
	
	$t_report=$tablePreStr."report";

	dbtarget('r',$dbServs);
	$sql="select user_id from $t_report where reported_id=$mod_id and type=$type";
	$report_row=$dbo->getRow($sql);
	
	if($report_row){
		if($user_id==$report_row['user_id']){
			echo $rp_langpackage->rp_no_repeat;exit;
		}else{
			dbtarget('w',$dbServs);
			$sql="update $t_report set rep_num=rep_num+1 where reported_id=$mod_id and type=$type";
			if($dbo->exeUpdate($sql)){
				echo 'true';
			}else{
				echo $rp_langpackage->rp_los;exit;
			}
		}
	}else{
		switch($type){
			case 0:
			$blog_row=api_proxy("blog_self_by_bid","user_id,log_title,user_name,log_id",$mod_id);
			$userd_id=$blog_row['user_id'];
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$user_id.'" target="_blank">'.$blog_row['user_name'].'</a>'.$rp_langpackage->rp_blog.'<a href="../home.php?h='.$userd_id.'&app=blog&id='.$blog_row['log_id'].'" target="_blank">{ico_url}</a>';
			$content=str_replace('{ico_url}',$blog_row['log_title'],$content);
			break;
			case 1:
			$groups_row=api_proxy("group_self_by_gid","*",$mod_id);
			$userd_id=$groups_row['add_userid'];
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$groups_row['group_creat_name'].'</a>'.$rp_langpackage->rp_group.'<a href="../home.php?h='.$userd_id.'&app=group_space&group_id='.$groups_row['group_id'].'" target="_blank">{ico_url}</a>';
			$content=str_replace('{ico_url}',$groups_row['group_name'],$content);
			break;
			case 2:
			$album_row=api_proxy("album_self_by_aid","*",$mod_id);
			$userd_id=$album_row['user_id'];
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$album_row['user_name'].'</a>'.$rp_langpackage->rp_alb.'<a href="../home.php?h='.$userd_id.'&app=photo_list&album_id='.$album_row['album_id'].'" target="_blank">'.$album_row['album_name'].'<br /><img src="../{ico_url}" width="100" /></a>';
			$content=str_replace('{ico_url}',$album_row['album_skin'],$content);
			break;
			case 3:
			$t_photo=$tablePreStr."photo";
			$t_user=$tablePreStr."users";
			$photo_row=api_proxy("album_photo_by_photoid","*",$mod_id);
			$userd_name=$photo_row['user_name'];
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$userd_name.'</a>'.$rp_langpackage->rp_pho.'<a href="../home.php?h='.$userd_id.'&app=photo&album_id='.$photo_row['album_id'].'&photo_id='.$photo_row['photo_id'].'" target="_blank">'.$photo_row['photo_name'].'<br /><img src="../{ico_url}" width="100" /></a>';
			$content=str_replace('{ico_url}',$photo_row['photo_src'],$content);
			break;
			case 4:
			$poll_row=api_proxy("poll_self_by_pollid","*",$mod_id);
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$poll_row['username'].'</a>'.$rp_langpackage->rp_poll.'<a href="../home.php?h='.$userd_id.'&app=poll&p_id='.$poll_row['p_id'].'" target="_blank">{ico_url}</a>';
			$content=str_replace('{ico_url}',$poll_row['subject'],$content);
			break;
			case 8:
			$share_row=api_proxy("share_self_by_sid","*",$mod_id);
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$share_row['user_name'].'</a>'.$rp_langpackage->rp_share.'<br />{ico_url}';
			$search=array('href="');
			$replace=array('href="../');
			$share_content=str_replace($search,$replace,strstr($share_row['s_title'],'<'));
			$content=str_replace('{ico_url}',$share_content,$content);
			break;
			case 9:
			$subject_row=api_proxy("group_sub_by_sid","*",$mod_id);
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">'.$subject_row['user_name'].'</a>'.$rp_langpackage->rp_sub.'<a href="../home.php?h='.$userd_id.'&app=group_sub_show&subject_id='.$subject_row['subject_id'].'&group_id='.$subject_row['group_id'].'" target="_blank">{ico_url}</a>';
			$content=str_replace('{ico_url}',$subject_row['title'],$content);
			break;
			case 10:
			$user_row=api_proxy("user_self_by_uid","user_id,user_name",$mod_id);
			$content=$rp_langpackage->rp_report.'<a href="../home.php?h='.$userd_id.'" target="_blank">{ico_url}</a>'.$rp_langpackage->rp_space;
			$content=str_replace('{ico_url}',$user_row['user_name'],$content);
			break;
		}
		dbtarget('w',$dbServs);
		$sql="insert into $t_report (user_id,reason,user_name,type,content,add_time,reported_id,userd_id) "
			."values ('$user_id','$reason','$user_name','$type','$content',now(),'$mod_id','$userd_id')";
		if($dbo->exeUpdate($sql)){
			echo 'true';
		}else{
			echo $rp_langpackage->rp_los;
		}
	}
?>