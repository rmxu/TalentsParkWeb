<?php 
require("session_check.php");
$is_check=check_rights("a14");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
	//语言包引入
	$f_langpackage=new foundationlp;
	$ad_langpackage=new adminmenulp;
	
	$dbo = new dbex;

	//数据表定义区
	$t_integral=$tablePreStr."integral";
	if(get_argp('action')){
		$is_check=check_rights("a15");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}
		//变量定义区
		$blog = get_argp('blog');
		$photo = get_argp('photo');
		$com_msg = get_argp('com_msg');
		$subject = get_argp('subject');
		$com_sub = get_argp('com_sub');
		$com_sub = get_argp('com_sub');
		
		$del_blog = get_argp('del_blog');
		$del_photo = get_argp('del_photo');
		$del_com_msg = get_argp('del_com_msg');
		$del_subject = get_argp('del_subject');
		$del_com_sub = get_argp('del_com_sub');
		
		$invited = get_argp('invited');
		$login = get_argp('login');
		$one_ico = get_argp('one_ico');
		
		$convert = get_argp('convert');
		$upgrade = get_argp('upgrade');
		$poll = get_argp('poll');
		$del_poll = get_argp('del_poll');
		$sha = get_argp('sha');
		$del_sha = get_argp('del_sha');
		
		dbtarget('w',$dbServs);
		
		$sql="update $t_integral set integral=$blog where operation='blog'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$photo where operation='photo'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$com_msg where operation='com_msg'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$subject where operation='subject'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$com_sub where operation='com_sub'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_blog where operation='del_blog'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_photo where operation='del_photo'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_com_msg where operation='del_com_msg'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_subject where operation='del_subject'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_com_sub where operation='del_com_sub'";
		$dbo->exeUpdate($sql);
		
		$sql="update $t_integral set integral=$invited where operation='invited'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$login where operation='login'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$one_ico where operation='one_ico'";
		$dbo->exeUpdate($sql);
		
		$sql="update $t_integral set integral=$convert where operation='convert'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$upgrade where operation='upgrade'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$poll where operation='poll'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_poll where operation='del_poll'";
		$dbo->exeUpdate($sql);
		
		$sql="update $t_integral set integral=$sha where operation='share'";
		$dbo->exeUpdate($sql);
		$sql="update $t_integral set integral=$del_sha where operation='del_share'";
		$dbo->exeUpdate($sql);
		
		
		$configfile=$webRoot.$baseLibsPath.'integral.php';
		//配置文件静态化
		$config_re=fopen($configfile,"w+");
		$insert_config_data='
<?php
$int_blog='.$blog.';
$int_photo='.$photo.';
$int_com_msg='.$com_msg.';
$int_subject='.$subject.';
$int_com_sub='.$com_sub.';
$int_del_blog='.$del_blog.';
$int_del_photo='.$del_photo.';
$int_del_com_msg='.$del_com_msg.';
$int_del_subject='.$del_subject.';
$int_del_com_sub='.$del_com_sub.';
$int_invited='.$invited.';
$int_login='.$login.';
$int_one_ico='.$one_ico.';
$int_convert='.$convert.';
$int_upgrade='.$upgrade.';
$int_poll='.$poll.';
$int_del_poll='.$del_poll.';
$int_share='.$sha.';
$int_del_share='.$del_sha.';
?>';
		fwrite($config_re,trim($insert_config_data));
		fclose($config_re);
		
		echo "<script type='text/javascript'>alert('$f_langpackage->f_amend_suc');window.location.href='integral.php';</script>";
	}
	dbtarget('r',$dbServs);
	
	$sql="select * from $t_integral";
	$integral_rs = $dbo->getRs($sql);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<title></title>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
<div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_user_management;?></a> &gt;&gt; <a href="integral.php"><?php echo $ad_langpackage->ad_inter_rule;?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $f_langpackage->f_integral;?></h3>
<div class="content">
<form method="post" action="">
  <table border="0" class="list_table">
    <thead><tr>
        
            <th><?php echo $f_langpackage->f_add_integral?></th>
            <th><?php echo $f_langpackage->f_add?></th>
            <th><?php echo $f_langpackage->f_abatement_integral?></th>
            <th><?php echo $f_langpackage->f_abatement?></th>
        
    </tr></thead>
    <tr>
      <td><?php echo $f_langpackage->f_add_blog?></td>
      <td><input type="text" name="blog" value="<?php echo $integral_rs[0][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_blog?></td>
      <td><input type="text" name="del_blog" value="<?php echo $integral_rs[8][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_poll?></td>
      <td><input type="text" name="poll" value="<?php echo $integral_rs[15][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_poll?></td>
      <td><input type="text" name="del_poll" value="<?php echo $integral_rs[16][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_pic?></td>
      <td><input type="text" name="photo" value="<?php echo $integral_rs[1][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_pic?></td>
      <td><input type="text" name="del_photo" value="<?php echo $integral_rs[9][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_com?></td>
      <td><input type="text" name="com_msg" value="<?php echo $integral_rs[4][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_com?></td>
      <td><input type="text" name="del_com_msg" value="<?php echo $integral_rs[11][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_sub?></td>
      <td><input type="text" name="subject" value="<?php echo $integral_rs[3][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_sub?></td>
      <td><input type="text" name="del_subject" value="<?php echo $integral_rs[10][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_reply?></td>
      <td><input type="text" name="com_sub" value="<?php echo $integral_rs[2][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_reply?></td>
      <td><input type="text" name="del_com_sub" value="<?php echo $integral_rs[12][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_add_share?></td>
      <td><input type="text" name="sha" value="<?php echo $integral_rs[17][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_del_share?></td>
      <td><input type="text" name="del_sha" value="<?php echo $integral_rs[18][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_request_friend?></td>
      <td><input type="text" name="invited" value="<?php echo $integral_rs[6][1];?>" class="small-text" /></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_login?></td>
      <td><input type="text" name="login" value="<?php echo $integral_rs[5][1];?>" class="small-text" /></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_set_head?></td>
      <td><input type="text" name="one_ico" value="<?php echo $integral_rs[7][1];?>" class="small-text" /></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_intergral_class?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo $f_langpackage->f_change_num?></td>
      <td><input type="text" name="convert" value="<?php echo $integral_rs[13][1];?>" class="small-text" /></td>
      <td><?php echo $f_langpackage->f_upgrade_num?></td>
      <td><input type="text" name="upgrade" value="<?php echo $integral_rs[14][1];?>" class="small-text" /></td>
    </tr>
    <tr>
      <td colspan="4"><input name="action" type="submit" value="<?php echo $f_langpackage->f_refer?>" class="regular-button" /></td>
    </tr>
  </table>
</form>
</div>
</div>
</div>
</div>
</body>
</html>