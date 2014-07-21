<?php
   //引入模块公共方法文件
  require("foundation/module_users.php");
	require("foundation/aintegral.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");

  //语言包引入
  $u_langpackage=new userslp;

  //数据库操作
  dbtarget('w',$dbServs);
  $dbo=new dbex();
  $photo_url=short_check(get_argg('pic'));
  $user_id=get_sess_userid();//用户ID
  $user_name=get_sess_username();//用户名
  $ico_url=long_check(get_argp('u_ico_url'));

  //定义文件表,头像字段,参考字段
  $t_users=$tablePreStr.'users';
  $t_mypals=$tablePreStr."pals_mine";
  $t_pals_req=$tablePreStr."pals_request";

  $user_ico="user_ico";$u_field_id="user_id";
  $pals_ico="pals_ico";$p_field_id="pals_id";
  $req_ico="req_ico";$q_field_id="req_id";

  if($ico_url==''){
  	  action_return(0,$u_langpackage->u_save_false,'-1');exit;
  }

			//生成70px缩略图
			if(function_exists('imagecopyresampled')){
				$img_ext=get_ext($ico_url);
				if($img_ext=='jpg'||$img_ext=='jpeg'){
					$temp_img = imagecreatefromjpeg($ico_url);
				}
				if($img_ext=='gif'){
					$temp_img = imagecreatefromgif($ico_url);
				}
				if($img_ext=='png'){
					$temp_img = imagecreatefrompng($ico_url);
				}
				$s_ico=str_replace('.'.$img_ext, '_small.'.$img_ext, $ico_url);
				$small_ico = imagecreatetruecolor(70,70);
				imagecopyresampled($small_ico,$temp_img,0,0,0,0,70,70,200,200);
				imagejpeg($small_ico,$s_ico);
			}else{
				$s_ico=$ico_url;
			}

    if(update_user_ico($dbo,$t_users,$user_ico,$u_field_id,$user_id,$s_ico)){
			if(get_sess_userico()=="skin/$skinUrl/images/d_ico_0_small.gif" or get_sess_userico()=="skin/$skinUrl/images/d_ico_1_small.gif"){
				increase_integral($dbo,$int_one_ico,get_sess_userid());
			}

			//更新数据
			update_user_ico($dbo,$t_mypals,$pals_ico,$p_field_id,$user_id,$s_ico);
			update_user_ico($dbo,$t_pals_req,$req_ico,$q_field_id,$user_id,$s_ico);
			set_sess_userico($s_ico);
			if(preg_match("/uploadfiles\/photo_store/",$photo_url)){
				unlink($photo_url);//删除临时图片文件
			}

			//记录新鲜事
			$title=$u_langpackage->u_picture_update;
			$content='<img class="photo_frame" onerror=parent.pic_error(this) src="'.$ico_url.'" align="top">';
			api_proxy("message_set",0,$title,$content,1,7);
      action_return(1,"",'modules.php?app=user_ico');
     }else{
     	   action_return(0,$u_langpackage->u_save_false,'-1');
     }
?>

