<?php
require("session_check.php");
require("../foundation/fcontent_format.php");
require("../foundation/module_lang.php");
require("../foundation/fchange_exp.php");
require("../foundation/asmtp_info.php");
$is_check=check_rights("a02");
if(!$is_check){
	echo $m_langpackage->m_no_pri;
	exit;
}
//语言包引入
$regInfo_root="../langpackage/".$langPackagePara."/regInfo.php";
$f_langpackage=new foundationlp;
$ad_langpackage=new adminmenulp;
$lp_f_amend_suc=$f_langpackage->f_amend_suc;
$content=file_get_contents("../configuration.php");//引入配置信息
$regInfo=file_get_contents($regInfo_root);//引入注册条款

	if(get_argp('action')){
		
		$msg = '';
		
		$is_check=check_rights("a03");
		if(!$is_check){
			echo $m_langpackage->m_no_pri;
			exit;
		}

		//变量区
		$errorReport=get_argp('errorReport');
		$base_href=get_argp('base_href');
		$regInfo=get_argp('regInfo');
		$regInfo=preg_replace("/\s+/","<br />",$regInfo);
		if(!is_utf8($regInfo)){
			$regInfo=iconv("gb2312","utf-8",$regInfo);
		}
		
		//配置文件静态化
		$content=change_exp($content);
		$content=preg_replace("/(ini_set\(\"display_errors\"),(\d)(\);)/","$1,$errorReport$3",$content);//错误报告
		$content=preg_replace('/(\$siteDomain=\"http:\/\/{\$\_SERVER\[\'HTTP_HOST\']}\/)[^\;]*(";)/',"$1{$base_href}$2",$content);//网站域名
		$f_ref=fopen("../configuration.php","w+");
		$c_num = fwrite($f_ref,trim($content));
		fclose($f_ref);
		
		// 站点Logo
		$snslogo = $_FILES['snslogo'];
		if(isset($snslogo))
		{
			if($snslogo['error'] == 0)
			{
				if(!($snslogo['name'] == 'snslogo.gif' && $snslogo['size'] < 2000000 && move_uploaded_file($snslogo['tmp_name'], $webRoot.'skin/'.$skinUrl.'/images/snslogo.gif')))
				{
					$msg .= $f_langpackage->f_site_logo_upload_error.'\n';
				}
			}
		}
		
		// 个人主页Logo
		$snslogo = $_FILES['logo'];
		if(isset($snslogo))
		{
			if($snslogo['error'] == 0)
			{
				if(!($snslogo['name'] == 'logo.gif' && $snslogo['size'] < 2000000 && move_uploaded_file($snslogo['tmp_name'], $webRoot.'skin/'.$skinUrl.'/images/logo.gif')))
				{
					$msg .= $f_langpackage->f_home_logo_upload_error.'\n';
				}
			}
		}

		//smtp配置信息
		$smtp_info=file_get_contents('../foundation/asmtp_info.php');
		$smtp_info=change_exp($smtp_info);
		$s_ref=fopen("../foundation/asmtp_info.php","w+");
		$s_num=fwrite($s_ref,trim($smtp_info));
		fclose($s_ref);

		//注册条款
		$reg_ref=fopen("$regInfo_root","w+");
		fwrite($reg_ref,$regInfo);
		fclose($reg_ref);
		if(isset($_COOKIE['lp_name'])){
			$_COOKIE['lp_name']='';
		}
		
		if($s_num == 0)
		{
			$msg .= $f_langpackage->f_email_set_error.'\n';
		}
		
		if($c_num == 0)
		{
			$msg .= $f_langpackage->f_site_Set_error.'\n';
		}
		
		if(!$msg){
			echo '<script type="text/javascript">alert("'.$f_langpackage->f_update_successful.'");window.location.href="basic_set.php";</script>';
		}
		else
		{
			echo '<script type="text/javascript">alert("'.$msg.'");window.location.href="basic_set.php";</script>';
		}
	}else{
		//错误报告控制
		if(strpos($content,"ini_set(\"display_errors\",0)")){
			$errorReport=0;$open_report="";$close_report="selected";
		}else{
			$errorReport=1;$open_report="selected";$close_report="";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $f_langpackage->f_site_set?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" media="all" type="text/css" href="css/admin.css">
<script type='text/javascript'>
function GET_TOP_URL(){
	var location_site="http://"+location.host+location.pathname+location.search;
	var no_file_url=location_site.replace(/.[^\/]*$/g,"/");
	var no_dir_url=no_file_url.replace(/\/sysadmin\//,"/");
	return no_dir_url;
}

function show_domain(){
	var domain_url=GET_TOP_URL();
	document.getElementById('domain_url').innerHTML=domain_url;
	var base_url=document.getElementById("web_site_domain").value;
	var host_href=document.getElementById("host_href").value;
	if(domain_url!=host_href+base_url){
		document.getElementById("web_site_domain").style.color='red';
		document.getElementById('domain_url').style.color='red';
	}
}

function check_domain(){
	var domain_url=GET_TOP_URL();
	var base_url=document.getElementById("web_site_domain").value;
	var host_href=document.getElementById("host_href").value;
	var filtrateStr=document.getElementById("filtrateStr").value;
	document.getElementById("filtrateStr").value=filtrateStr.replace(/\||，|\n|\r/g,",");
	if(domain_url!=host_href+base_url){
		show_domain();
		return confirm('<?php echo $f_langpackage->f_tip_domain;?>');
	}
}

function set_domain(){
	<?php $web_dir=str_replace("http://{$_SERVER['HTTP_HOST']}/",'',$siteDomain);?>
	document.getElementById("web_site_domain").value="<?php echo $web_dir;?>";
	document.getElementById("web_site_domain").style.color='';
	document.getElementById('domain_url').style.color='';
}
</script>
</head>
<body onload='show_domain()'>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0)"><?php echo $ad_langpackage->ad_global_set;?></a> &gt;&gt; <a href="basic_set.php"><?php echo $ad_langpackage->ad_site_set;?></a></div>
        <hr />
<form enctype="multipart/form-data" action='' method='POST' onsubmit='return check_domain()'>

<div class="infobox">
<h3><?php echo $f_langpackage->f_site_set?></h3>
	<table class="form-table" style="table-layout:fixed">
		<tr>
			<td width="150"> <?php echo $f_langpackage->f_site_name?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $siteName;?>' name='siteName' /> <?php echo $f_langpackage->f_site_name_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_domain?>：	</td>
			<td> <input type='text' class='small-text' id='host_href' disabled value='<?php echo "http://{$_SERVER['HTTP_HOST']}/";?>' /><input class="small-text" type='text' value='<?php echo str_replace("http://{$_SERVER['HTTP_HOST']}/",'',$siteDomain);?>' name='base_href' id='web_site_domain' /> （<?php echo $f_langpackage->f_check_domain?><a href='javascript:set_domain();' title='<?php echo $f_langpackage->f_click_set?>'><span id='domain_url'></span></a>） </td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_keyword?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $metaKeys;?>' name='metaKeys' /> <?php echo $f_langpackage->f_keyword_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_description?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $metaDesc;?>' name='metaDesc' /> <?php echo $f_langpackage->f_description_annotations?></td>
		</tr>
        <tr>
			<td> <?php echo $f_langpackage->f_author?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $metaAuthor;?>' name='metaAuthor' /></td>
		</tr>
        <tr>
			<td> <?php echo $f_langpackage->f_mail?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $adminEmail;?>' name='adminEmail' /></td>
		</tr>
        <tr>
			<td> <?php echo $f_langpackage->f_copyright_inf?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $copyright;?>' name='copyright' /> <?php echo $f_langpackage->f_copyright_inf_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_record?>：	</td>
			<td> <input class="regular-text" type='text' value='<?php echo $domainRemark;?>' name='domainRemark' /> <?php echo $f_langpackage->f_record_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_close_say?>：	</td>
			<td>
				<textarea class="regular-textarea" rows="5" name='offlineMessage'><?php echo $offlineMessage;?></textarea> <?php echo $f_langpackage->f_close_say_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_open_err?>：	</td>
			<td>
				<select name='errorReport'>
					<option value="0" <?php echo $close_report;?>><?php echo $f_langpackage->f_close?></option>
					<option value="1" <?php echo $open_report;?>><?php echo $f_langpackage->f_open?></option>
				</select>
				<?php echo $f_langpackage->f_open_err_annotations?>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_close_control?>：	</td>
			<td>
				<select name='offLine'>
					<option value='0' <?php echo $offLine==1 ? '':'selected';?>><?php echo $f_langpackage->f_open?></option>
					<option value='1' <?php echo $offLine==1 ? 'selected':'';?>><?php echo $f_langpackage->f_close?></option>
				</select>
				<?php echo $f_langpackage->f_close_annotations?>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_open_cookie?>：	</td>
			<td>
				<select name='cookieOpen'>
					<option value='0' <?php echo $cookieOpen==1 ? '':'selected';?>><?php echo $f_langpackage->f_close?></option>
					<option value='1' <?php echo $cookieOpen==1 ? 'selected':'';?>><?php echo $f_langpackage->f_open?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_rewrite_set;?>：	</td>
			<td>
				<select name='urlRewrite'>
					<option value='0' <?php echo $urlRewrite==0 ? 'selected':'';?>><?php echo $f_langpackage->f_close?></option>
					<option value='1' <?php echo $urlRewrite==1 ? 'selected':'';?>><?php echo $f_langpackage->f_general?></option>
					<option value='2' <?php echo $urlRewrite==2 ? 'selected':'';?>><?php echo $f_langpackage->f_advanced?></option>
				</select>
				<?php echo $f_langpackage->f_rewrite_info?>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_site_logo?>：	</td>
			<td>
				<input type="file" class="regular-text" name="snslogo" />
				<?php echo $f_langpackage->f_site_logo_rule?>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_home_logo?>：	</td>
			<td>
				<input type="file" class="regular-text" name="logo" />
				<?php echo $f_langpackage->f_home_logo_rule?>
			</td>
		</tr>
	</table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_reg_set?></h3>
    <table class='form-table'>
			<tr>
				<td width='150'><?php echo $f_langpackage->f_open_new_user_reg?>：</td>
				<td>
					<input type='radio' value='1' <?php echo $allowReg==1 ? 'checked':'';?> name='allowReg' /><?php echo $f_langpackage->f_yes?> &nbsp 
					<input type='radio' <?php echo $allowReg==0 ? 'checked':'';?> value='0' name='allowReg' /><?php echo $f_langpackage->f_no?>
					<?php echo $f_langpackage->f_whether_allow_reg?>
				</td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_open_invite_reg?>：</td>
				<td>
					<input type='radio' value='1' <?php echo $inviteCode==1 ? 'checked':'';?> name='inviteCode' /><?php echo $f_langpackage->f_yes?> &nbsp 
					<input type='radio' value='0' <?php echo $inviteCode==0 ? 'checked':'';?> name='inviteCode' /><?php echo $f_langpackage->f_no?>
					<?php echo $f_langpackage->f_need_invite_reg?>
				</td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_failure_time_invite?>：</td>
				<td>
					<input type='text' class='small-text' value='<?php echo $inviteCodeLife;?>' /><?php echo $f_langpackage->f_hour?>
					<?php echo $f_langpackage->f_invite_valid_time_hour?>
				</td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_takes_invite_points?>：</td>
				<td>
					<input type='text' class='small-text' name='inviteCodeValue' value='<?php echo $inviteCodeValue;?>' /><?php echo $f_langpackage->f_point?>
					<?php echo $f_langpackage->f_invite_valid_time_minute?>
				</td>
			</tr>
			<tr>
				<td> <?php echo $f_langpackage->f_article?>：	</td>
				<td>
					<textarea class="regular-textarea" name='regInfo' id='regInfo' ><?php echo str_replace("<br />","\r\n",$regInfo);?></textarea>	<br /><?php echo $f_langpackage->f_article_annotations?>
				</td>
			</tr>			
    </table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_mail_set?></h3>
    <table class='form-table'>
			<tr>
				<td width='150px'><?php echo $f_langpackage->f_smtp_address;?>：</td>
				<td><input class="regular-text" type='text' name='smtpAddress' value='<?php echo $smtpAddress;?>' /></td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_smtp_email;?>：</td>
				<td><input class="regular-text" type='text' name='smtpEmail' value='<?php echo $smtpEmail;?>' /></td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_smtp_port;?>：</td>
				<td><input class="regular-text" type='text' name='smtpPort' value='<?php echo $smtpPort;?>' /></td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_smtp_user;?>：</td>
				<td><input class="regular-text" type='text' name='smtpUser' value='<?php echo $smtpUser;?>' /></td>
			</tr>
			<tr>
				<td><?php echo $f_langpackage->f_smtp_password;?>：</td>
				<td><input class="regular-text" type='password' name='smtpPassword' value='<?php echo $smtpPassword;?>' /></td>
			</tr>
			</tr>
    </table>
</div>

<div class="infobox">
<h3><?php echo $f_langpackage->f_display_set?></h3>
	<table class='form-table'>
		<tr>
			<td width="150"> <?php echo $f_langpackage->f_main_dynamic_num?>：	</td>
			<td> <input class="small-text" type='text' value='<?php echo $mainAffairNum;?>' name='mainAffairNum' /> <?php echo $f_langpackage->f_person;?><?php echo $f_langpackage->f_main_dynamic_num_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_home_dynamic_num?>：	</td>
			<td> <input class="small-text" type='text' value='<?php echo $homeAffairNum;?>' name='homeAffairNum' /> <?php echo $f_langpackage->f_item;?> <?php echo $f_langpackage->f_home_dynamic_num_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_cache?>：	</td>
			<td>
				<select name='ctrlCache'>
					<option value='1' <?php echo $ctrlCache==1 ? 'selected':'';?>><?php echo $f_langpackage->f_open?></option>
					<option value='0' <?php echo $ctrlCache==1 ? '':'selected';?>><?php echo $f_langpackage->f_close?></option>
				</select>
				<?php echo $f_langpackage->f_cache_annotations?>
			</td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_cache_update?>：	</td>
			<td> <input class="small-text" type='text' value='<?php echo $cache_update_delay_time;?>' name='cache_update_delay_time' /> <?php echo $f_langpackage->f_second?><?php echo $f_langpackage->f_cache_update_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_page_num?>：	</td>
			<td> <input class="small-text" type='text' name='cachePages' value='<?php echo $cachePages;?>' /> <?php echo $f_langpackage->f_page;?><?php echo $f_langpackage->f_page_num_annotations?></td>
		</tr>
		<tr>
			<td> <?php echo $f_langpackage->f_birthday?>：	</td>
			<td>
				<input class="small-text" type='text' name='setMinYear' value='<?php echo $setMinYear;?>' />~<input type='text' class="small-text" name='setMaxYear' value='<?php echo $setMaxYear;?>' /><?php echo $f_langpackage->f_birthday_annotations?>
			</td>
		</tr>
	</table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_watering_set?></h3>
    <table class='form-table'>
        <tr>
            <td width="150"> <?php echo $f_langpackage->f_alternation_time?>：	</td>
            <td> <input type='text' class="small-text" name='allowRefreshTime' value='<?php echo $allowRefreshTime;?>' /> <?php echo $f_langpackage->f_second?> <?php echo $f_langpackage->f_alternation_time_annotations?></td>
        </tr>
        <tr>
            <td> <?php echo $f_langpackage->f_defer_time?>：	</td>
            <td> <input class="small-text" type='text' name='delayTime' value='<?php echo $delayTime;?>' /> <?php echo $f_langpackage->f_second?> <?php echo $f_langpackage->f_defer_time_annotations?></td>
        </tr>
    </table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_filter_set?></h3>
    <table class='form-table'>
        <tr>
            <td width="150"> <?php echo $f_langpackage->f_open_filter?>：	</td>
            <td>
                <select name='wordFilt'>
                    <option value='1' <?php echo $wordFilt==1 ? 'selected':'';?>><?php echo $f_langpackage->f_open?></option>
                    <option value='0' <?php echo $wordFilt==1 ? '':'selected';?>><?php echo $f_langpackage->f_close?></option>
                </select>
                <?php echo $f_langpackage->f_open_filter_annotations?>
            </td>
        </tr>
        <tr>
            <td> <?php echo $f_langpackage->f_filter_content?>：	</td>
            <td> <textarea class="regular-textarea" rows="4" id='filtrateStr' name='filtrateStr' ><?php echo $filtrateStr;?></textarea> <?php echo $f_langpackage->f_filter_content_annotations?> </td>
        </tr>
    </table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_sess_pre?></h3>
    <table class='form-table'>
        <tr>
            <td width="150"> <?php echo $f_langpackage->f_sess_pre?>：	</td>
            <td> <input class="small-text" type='text' name='session_prefix' value='<?php echo $session_prefix;?>' /> <?php echo $f_langpackage->f_sess_pre_inf?></td>
        </tr>
    </table>
</div>

<div class="infobox">
    <h3><?php echo $f_langpackage->f_high_set?></h3>
    <table class='form-table'>
        <tr>
            <td width="150"> <?php echo $f_langpackage->f_support_library?>：	</td>
            <td> <input class="regular-text" type='text' name='baseLibsPath' value='<?php echo $baseLibsPath;?>' /> <?php echo $f_langpackage->f_support_library_annotations?></td>
        </tr>
        <tr>
            <td> <?php echo $f_langpackage->f_language_pack?>：	</td>
            <td>
                <?php echo show_back_lp($langPackagePara);?>
                <?php echo $f_langpackage->f_language_pack_annotations;?>
            </td>
        </tr>
    </table>
</div>
<input class="regular-button" type="submit" value="<?php echo $f_langpackage->f_refer;?>" name="action" />
</form>
</div></div>
</body>
</html>