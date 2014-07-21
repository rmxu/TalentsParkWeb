<?php
//引入公共方法
require("foundation/module_users.php");
require("foundation/fcontent_format.php");
require("api/base_support.php");

//引入语言包
$u_langpackage=new userslp;
$rf_langpackage=new recaffairlp;
$ah_langpackage=new arrayhomelp;

//变量获得
$holder_id=intval(get_argg('user_id'));//主人id
$user_id=get_sess_userid();
$holder_name=get_hodler_name($holder_id);
$is_self=($holder_id==$user_id) ? 'Y':'N';
$msg_act=$user_id ? "send_msg($holder_id)":"parent.goLogin()";

//数据表定义区
$t_users=$tablePreStr."users";
$t_blog=$tablePreStr."blog";
$t_album=$tablePreStr."album";
$t_photo=$tablePreStr."photo";

//留言板展示
$user_info=api_proxy("user_self_by_uid","inputmess_limit",$holder_id);
$show_msg=1;
if($is_self=='N' && $user_info['inputmess_limit']){
	if($user_info['inputmess_limit']==2){
		$show_msg=0;
	}else if($user_info['inputmess_limit']==1){
		if(!api_proxy("pals_self_isset",$holder_id)){
			$show_msg=0;
		}
	}
}

$dbo=new dbex;
//读写分离定义方法
dbtarget('r',$dbServs);

$holder_photo=array();
$holder_blog=array();
$holder_message=array();

//取得最新照片
$sql="select p.album_id,p.photo_id,p.photo_thumb_src,p.add_time,p.photo_name from $t_album as a join $t_photo as p on(a.album_id=p.album_id) where p.user_id=$holder_id and a.privacy='' and p.privacy='' and a.is_pass=1 and p.is_pass=1 order by p.photo_id desc limit 4";
$holder_photo=$dbo->getRs($sql);

//取得最新日志
$sql="select log_id,log_title,log_content,add_time from $t_blog where user_id=$holder_id and is_pass=1 and privacy='' order by log_id desc limit 3 ";
$holder_blog=$dbo->getRs($sql);

?>