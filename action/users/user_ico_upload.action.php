<?php
   //引入模块公共方法文件
  require("foundation/module_users.php");
  require("foundation/aintegral.php");
  require("foundation/fcontent_format.php");
  require("api/base_support.php");

  //语言包引入
  $pu_langpackage=new pubtooslp;
  $u_langpackage=new userslp;
  $limcount=1;//限制每次上传附件数量

  if(count($_FILES['attach']['name'])!=$limcount||$_FILES['attach']['name'][0]==''){
     action_return(0,$u_langpackage->u_choose_upload_images,'-1');exit;
  }
  
  //1、处理直接上传的200X200的头像文件
  $file_is_ico=0;
  $ico_size=getimagesize($_FILES['attach']['tmp_name'][0]);
  	if(get_argp('type')=='ico'){
  		if($ico_size[0]!=200||$ico_size[1]!=200){
  			action_return(0,$u_langpackage->u_upl_size_err,'-1');exit;
  		}else{
  			$file_is_ico=1;
  		}
  	}
  	if(get_argp('type')=='photo'){
  		if($ico_size[0]<200||$ico_size[1]<200){
  			action_return(0,$u_langpackage->u_up_small,'-1');
  		}
  	}

    //保存图片以及图片信息
    dbtarget('w',$dbServs);
    $dbo=new dbex();

    $up = new upload();
    $up->set_dir($webRoot.'uploadfiles/photo_store/','{y}/{m}/{d}');
    $fs=$up->execute();

    $user_id=get_sess_userid();//用户ID
    $user_name=get_sess_username();//用户名

    //定义文件表
    $t_uploadfile=$tablePreStr.'uploadfile';
    $t_users=$tablePreStr.'users';
    $t_mypals=$tablePreStr."pals_mine";
    $t_pals_req=$tablePreStr."pals_request";

    $realtxt=$fs[0];

      if($realtxt['flag']==1){
          $fileSrcStr=str_replace($webRoot,"",$realtxt['dir']).$realtxt['name'];
          $fileName=$realtxt['initname'];

          $sql="insert into $t_uploadfile (file_name,file_src,user_id,add_time) values ('$fileName','$fileSrcStr','$user_id',NOW())";
          $dbo->exeUpdate($sql);

				if($file_is_ico==1){
					$field_ico='user_ico';
					$field_id='user_id';
					$pals_ico="pals_ico";$p_field_id="pals_id";
					$req_ico="req_ico";$q_field_id="req_id";
	     		if(function_exists('imagecopyresampled')){
						//生成70px缩略图
						$img_ext=get_ext($fileSrcStr);
						if($img_ext=='jpg'||$img_ext=='jpeg'){
							$temp_img = imagecreatefromjpeg($fileSrcStr);
						}
						if($img_ext=='gif'){
							$temp_img = imagecreatefromgif($fileSrcStr);
						}
						if($img_ext=='png'){
							$temp_img = imagecreatefrompng($fileSrcStr);
						}
						$s_ico=str_replace('.'.$img_ext, '_small.'.$img_ext, $fileSrcStr);
						$small_ico = imagecreatetruecolor(70,70);
						imagecopyresampled($small_ico,$temp_img,0,0,0,0,70,70,200,200);
						imagejpeg($small_ico,$s_ico);
					}else{
						$s_ico=$fileSrcStr;
					}

					if(update_user_ico($dbo,$t_users,$field_ico,$field_id,$user_id,$s_ico)){
						if(get_sess_userico()=="skin/$skinUrl/images/d_ico_0_small.gif" or get_sess_userico()=="skin/$skinUrl/images/d_ico_1_small.gif"){
							increase_integral($dbo,$int_one_ico,get_sess_userid());
					}
						update_user_ico($dbo,$t_mypals,$pals_ico,$p_field_id,$user_id,$s_ico);
						update_user_ico($dbo,$t_pals_req,$req_ico,$q_field_id,$user_id,$s_ico);
						set_sess_userico($s_ico);

						//记录新鲜事
						$title=$u_langpackage->u_picture_update;
						$content='<img class="photo_frame" onerror=parent.pic_error(this) src="'.$fileSrcStr.'" align="top">';
						api_proxy("message_set",0,$title,$content,1,7);
						action_return(1,$u_langpackage->u_upl_suc,'modules.php?app=user_ico');
	         }else{
	         	   action_return(0,$u_langpackage->u_upl_false,'-1');
	         }
       }else{
					action_return(1,'','modules.php?app=user_ico_cut&photo_url='.$fileSrcStr);
       }
      }else if($realtxt['flag']==-1){
      	  action_return(0,$pu_langpackage->pu_type_err,'-1');
      }else if($realtxt['flag']==-2){
          action_return(0,$pu_langpackage->pu_capacity_err,'-1');
      }

?>

