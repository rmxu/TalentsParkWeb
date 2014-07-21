<?php
require("session_check.php");
$is_check=check_rights("a01");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
$version_url="../docs/version.txt";
$whole_version=file_get_contents($version_url);

//语言包引入
$f_langpackage=new foundationlp;
$ad_langpackage=new adminmenulp;

$dbo = new dbex;
dbtarget('w',$dbServs);

$show_count=array("users","groups","album","photo","blog","online","group_subject","recent_affair","msgboard","share");
$modules_count=array();
foreach($show_count as $rs){
	$sql="select count(*) as count from {$tablePreStr}{$rs}";
	$modules_count[]=$dbo->getRow($sql);
}
$sql="select VERSION()";
$db_version=$dbo->getRow($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css" />
</head>
<body>
<div id="jywrap">
    <div id="maincontent">
        <div class="wrap">
            <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="right.php"><?php echo $ad_langpackage->ad_manage_index;?></a></div>
            <hr />
            <div class="infobox left" style="width:48%">
                <h3><?php echo $f_langpackage->f_data_num;?></h3>
                <div class="content">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td width="50%"><a href="member_list.php?order_by=user_id&amp;order_sc=desc"><?php echo $f_langpackage->f_people_num?> ：<?php echo $modules_count[0]['count'];?></a></td>
                                <td width="50%"><a href="group_list.php?order_by=group_id&amp;order_sc=desc"><?php echo $f_langpackage->f_group_num?> ：<?php echo $modules_count[1]['count'];?></a></td>
                            </tr>
                            <tr>
                                <td><a href="album_list.php?order_by=album_id&amp;order_sc=desc"><?php echo $f_langpackage->f_album_num?> ：<?php echo $modules_count[2]['count'];?></a></td>
                                <td><a href="photo_list.php?order_by=photo_id&amp;order_sc=desc"><?php echo $f_langpackage->f_photo_num?> ：<?php echo $modules_count[3]['count'];?></a></td>
                            </tr>
                            <tr>
                                <td><a href="blog_list.php?order_by=log_id&amp;order_sc=desc"><?php echo $f_langpackage->f_blog_num?> ：<?php echo $modules_count[4]['count'];?></a></td>
                                <td><a href="member_list.php?order_by=user_id&amp;order_sc=desc&amp;online=1"><?php echo $f_langpackage->f_online_num?> ：<?php echo $modules_count[5]['count'];?></a></td>
                            </tr>
                            <tr>
                                <td><a href="subject_list.php?order_by=subject_id&amp;order_sc=desc"><?php echo $f_langpackage->f_subject_num?> ：<?php echo $modules_count[6]['count'];?></a></td>
                                <td><a href="affair_list.php?order_by=id&amp;order_sc=desc"><?php echo $f_langpackage->f_affair_num?> ：<?php echo $modules_count[7]['count'];?></a></td>
                            </tr>
                            <tr>
                                <td><a href="msgboard_list.php?order_by=mess_id&amp;order_sc=desc"><?php echo $f_langpackage->f_mess_num?> ：<?php echo $modules_count[8]['count'];?></a></td>
                                <td><a href="share_list.php?order_by=s_id&amp;order_sc=desc"><?php echo $f_langpackage->f_share_num?> ：<?php echo $modules_count[9]['count'];?></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="infobox right" style="width:50%">
                <h3><?php echo $f_langpackage->f_new_official?></h3>
                <div class="content">
                    <table width="100%">
                        <tr>
                            <th><?php echo $f_langpackage->f_new_official?>：</th>
                        </tr>
                        <tr>
                            <td> <?php echo str_replace("{url}",'<a href="http://www.jooyea.net" target="_blank">',$f_langpackage->f_wel_web)?></a> </td>
                        </tr>
                        <tr>
                            <th><?php echo $f_langpackage->f_skill_serve?>：</th>
                        </tr>
                        <tr>
                            <td>
                                <a href="http://tech.jooyea.com/bbs" target="_blank"><?php echo $f_langpackage->f_official_bbs?></a> &nbsp|&nbsp
                                <a href="http://tech.jooyea.com/download.php" target="_blank"><?php echo $f_langpackage->f_new_edition?></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clear"></div>
            <div class="infobox">
                <h3><?php echo $f_langpackage->f_Program_db_edition?></h3>
                <div class="content">
                    <p><?php echo $f_langpackage->f_OS?>：<?php echo PHP_OS;?></p>
                    <p><?php echo $f_langpackage->f_sever_edition?>：<?php echo $_SERVER['SERVER_SOFTWARE'];?></p>
                    <p><?php echo $f_langpackage->f_db_edition?>：<?php echo $db_version[0];?></p>
                    <p><?php echo $f_langpackage->f_sns_edition?>：<?php echo $whole_version;?></p>
					
					<p><?php echo "安全模式";?>：<?php if(ini_get('safe_mode')) echo "是";else echo "否";?></p>
					<p><?php echo "安全模式GID";?>：<?php if(ini_get('safe_mode_gid')) echo "是";else echo "否";?></p>
					<p><?php echo "Socket支持";?>：<?php if(ini_get('mysql.default_socket')) echo "是";else echo "否";?></p>
					<p><?php echo "文件上传大小";?>：<?php echo ini_get('upload_max_filesize');?></p>
					<p><?php echo "默认时区";?>：<?php echo date_default_timezone_get();?></p>
                </div>
            </div>
            <div class="infobox">
                <h3><?php echo $f_langpackage->f_team?></h3>
                <div class="content">
                    <p><?php echo $f_langpackage->f_copyright?></p>
                    <p><?php echo $f_langpackage->f_ceo?>  ：erysin</p>
                    <p><?php echo $f_langpackage->f_team?>：nswe ，napoleon，dumb ，arthur</p>
                    <p><?php echo $f_langpackage->f_art?>：Eric，Mike</p>
                    <p><?php echo $f_langpackage->f_web?>：<a href="http://www.jooyea.net" target="_blank">http://www.jooyea.net</a></p>
                </div>
            </div>
        </div>
	</div>
</div>
</body>
</html>