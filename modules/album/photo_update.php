<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/photo_update.html
 * 如果您的模型要进行修改，请修改 models/modules/album/photo_update.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$a_langpackage=new albumlp;
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();
	$s_fs=get_session("S_fs");
	$fs = array();
	
	//表定义区
	$t_tmp_file = $tablePreStr."tmp_file";
	$album_row=api_proxy("album_self_by_aid","privacy,album_name",$album_id);
	
	if(empty($s_fs)){
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		$sql="select data_array from $t_tmp_file where mod_id=$album_id";
		$session_data=$dbo->getRow($sql);
		$fs=unserialize($session_data['data_array']);
		$sql="delete from $t_tmp_file where mod_id=$album_id";
		$dbo->exeUpdate($sql);
	}else{
		$fs=$s_fs;
		set_session("S_fs",'');
	}
	
if($fs){
	//新鲜事
	if($album_row['privacy']==''){
		$show_limit=0;
		$content='';
		foreach($fs as $val){
			if($show_limit==4){
				break;
			}
			$show_limit++;
			$content.='<a href="home.php?h='.$user_id.'&app=photo&photo_id='.$val['photo_id'].'&album_id='.$album_id.'" target="_blank">'
			          .'<img class="photo_frame" onerror=parent.pic_error(this) src="'.$val['dir'].$val['thumb'].'"></a>';
		}
		if($show_limit==1){
			$mod_type=3;
			$mod_id=$val['photo_id'];
		}else{
			$mod_type=2;
			$mod_id=$album_id;
		}
		$title=$a_langpackage->a_in_album.'<a href="home.php?h='.$user_id.'&app=photo_list&album_id='.$album_id.'" target="_blank">'.$album_row['album_name'].'</a>'.$a_langpackage->a_upload_new_photo;
		$is_suc=api_proxy("message_set",$mod_id,$title,$content,2,$mod_type);
	}
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<div class="create_button"><a href="modules.php?app=photo_upload"><?php echo $a_langpackage->a_upload;?></a></div>
<h2 class="app_album"><?php echo $a_langpackage->a_upload;?></h2>
<div class="tabs">
	<ul class="menu">
        <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>
<?php if($fs){?>
<form action='do.php?act=photo_upd&album_id=<?php echo $album_id;?>' method='post'>
<?php foreach($fs as $index=>$realtxt){?>
	<?php $thumb_src=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['thumb'];?>
	<div class="front_cover"><input type='hidden' name='photo_id[]' value=<?php echo $realtxt['photo_id'];?> /><img src=<?php echo $realtxt['dir'];?><?php echo $realtxt['thumb'];?> onerror="parent.pic_error(this)" /></div>
	<div class="album_remark">
	  <p><?php echo $a_langpackage->a_p_name;?></p>
	  <input class="small-text" type='text' name='photo_name[]' value='<?php echo preg_replace("/\.\w*$/","",$realtxt['initname']);?>' style="width:100%" />
	</div>
	<div class="album_remark">
	  <p><?php echo $a_langpackage->a_p_inf;?></p>
	  <textarea class="med-textarea" rows='4'  style="width:100%" name='photo_information[]'></textarea>
	  <p class="mt10"><input type='radio' style="vertical-align:middle" name=album_skin value=<?php echo $thumb_src;?> /><?php echo $a_langpackage->a_set_cov;?></p>
	</div>
	<div class="blank"></div>
<?php }?>
	<div class="clear"></div>
	<input type='submit' name='action' value='<?php echo $a_langpackage->a_b_con;?>' class='regular-btn mt10' />
</form>
<?php }?>

<?php if(!$fs){?>
<div class="guide_info">
	对不起，您所上传照片不符合规范，请重新上传
</div>
<?php }?>
</body>
</html>
