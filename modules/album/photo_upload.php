<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/album/photo_upload.html
 * 如果您的模型要进行修改，请修改 models/modules/album/photo_upload.php
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

	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//限制时间段访问站点
	limit_time($limit_action_time);

	//引入模块公共方法文件
	require("foundation/module_album.php");

	//变量取得
	$album_id=intval(get_argg('album_id'));
	$user_id=get_sess_userid();

	//变量定义区
	$t_online = $tablePreStr."online";
	$t_album = $tablePreStr."album";
	
	if($album_id==''){
		$dbo = new dbex;
		dbtarget('r',$dbServs);
		$sql="select count(*) as a_num from $t_album where user_id=$user_id";
		$album_num=$dbo->getRow($sql);
		if(!$album_num['a_num']){
			echo '<script type="text/javascript">window.location.href="modules.php?app=album_edit";</script>';
		}
	}
	
	$session_code=md5(rand(0,10000));

	$sess_code_str=$session_code."|".$user_id;

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$sql="update $t_online set session_code = '$session_code' where user_id=$user_id";
	$dbo->exeUpdate($sql);

	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id,500);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" type="text/css" href="servtools/flash_mod/history/history.css" />
<script src="servtools/flash_mod/AC_OETags.js" language="javascript"></script>
<script src="servtools/flash_mod/history/history.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 28;
</script>
<script type='text/javascript'>
function change_upload_type(){
	var f_type=document.getElementById("flash_upload");
	var n_type=document.getElementById("normal_upload");
	if(f_type.style.display=='none'){
		f_type.style.display='';
		n_type.style.display='none';
	}else{
		f_type.style.display='none';
		n_type.style.display='';
	}
}

function check_form()
{
  if(document.getElementById("album_name").value==""){
  	parent.Dialog.alert("<?php echo $a_langpackage->a_alb_cho;?>");
  	return false;
  }
	var selectAlbum=document.getElementById("album_name");
	var selectIndex=getSelectIndex(selectAlbum);
	document.getElementById("album_ufor").value=selectAlbum.options[selectIndex].text;
	if(document.getElementById("album_ufor").value!=''){
		return true;
  }
}

function getSelectIndex(objSelect) {
	var length = objSelect.options.length-1;
	for(var i = length; i >= 0; i--){
	   if(objSelect[i].selected==true){
	       return i;
	       break;
	   }
	}
}

function get_domain_url(){
	var location_site=parent.GET_TOP_URL();
	return location_site.replace(/.[^\/]*$/g,"/");
}
	</script>
</head>

<body id="iframecontent">
<div class="create_button"><a href="modules.php?app=album_edit"><?php echo $a_langpackage->a_creat;?></a></div>
<h2 class="app_album"><?php echo $a_langpackage->a_title;?></h2>
<div class="tabs">
	<ul class="menu">
        <li class="active"><a href="modules.php?app=album" hidefocus="true"><?php echo $a_langpackage->a_mine;?></a></li>
        <li><a href="modules.php?app=album_friend" hidefocus="true"><?php echo $a_langpackage->a_friend;?></a></li>
    </ul>
</div>

<form action="do.php?act=photo_upl" method="post" enctype="multipart/form-data" onsubmit="return check_form();">
	<table class="form_table" id="photo_upload">
		<input name='album_ufor' id='album_ufor' type='hidden' value=''>
		<tr><th><?php echo $a_langpackage->a_cho;?>：</th><td><?php album_name($album_rs,$album_id);?></td><td><a href='javascript:change_upload_type();'><?php echo $a_langpackage->a_change_upload;?></a></td></tr>
		<tr><th><?php echo $a_langpackage->a_upload;?>：</th><td>(<?php echo $a_langpackage->a_upl_err;?>)</td></tr>
	</table>
	<table class="form_table" id='normal_upload' style='display:none'>
		<tr><th></th><td height="40px"><input class="med-text" type='file' name='attach[]' id='attach[]' /></td></tr>
		<tr><th></th><td height="40px"><input class="med-text" type='file' name='attach[]' id='attach[]' /></td></tr>
		<tr><th></th><td height="40px"><input class="med-text" type='file' name='attach[]' id='attach[]' /></td></tr>
		<tr><th></th><td height="40px"><input class="med-text" type='file' name='attach[]' id='attach[]' /></td></tr>
		<tr><th></th><td height="40px"><input class="med-text" type='file' name='attach[]' id='attach[]' /></td></tr>
		<tr><th></th><td height="40px"><input type="submit" name="submit" class="regular-btn" value="<?php echo $a_langpackage->a_b_upl;?>" /></td></tr>
	</table>
</form>
<table class="form_table" id='flash_upload'>
	<tr>
		<td height="100%">
<div class="album_list" style="height:350px;">
<script type='text/javascript'>
function getSessCode(){
	return "<?php echo $sess_code_str;?>";
}

function getUploadScript(){
	var album_id=document.getElementById('album_name').value;
	if(album_id==''){
		alert("<?php echo $a_langpackage->a_alb_cho;?>");
		return "null";
	}else{
		var domain_url=get_domain_url();
  	return domain_url+"do.php?act=photo_upl_flash&id="+album_id;
  }
}

function getKeepupUrl(){
	var album_id=document.getElementById('album_name').value;
  return "modules.php?app=photo_upload&album_id="+album_id;
}

function getSuccUrl(){
	var album_id=document.getElementById('album_name').value;
	return "modules.php?app=photo_update&album_id="+album_id;
}

var hasProductInstall = DetectFlashVer(6, 0, 65);

var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

if ( hasProductInstall && !hasRequestedVersion ) {
	var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
	var MMredirectURL = window.location;
    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
    var MMdoctitle = document.title;

	AC_FL_RunContent(
		"src", "servtools/flash_mod/playerProductInstall",
		"FlashVars","MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
		"width", "100%",
		"height", "90%",
		"align", "middle",
		"id", "upl",
		"quality", "high",
		"bgcolor", "#ecfbff",
		"wmode", "transparent",
		"name", "upl",
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer"
	);
} else if (hasRequestedVersion) {
	AC_FL_RunContent(
			"src", "servtools/flash_mod/upl",
			"width", "100%",
			"height", "90%",
			"align", "middle",
			"id", "upl",
			"quality", "high",
			"bgcolor", "#ecfbff",
			"wmode", "transparent",
			"name", "upl",
			"allowScriptAccess","sameDomain",
			"type", "application/x-shockwave-flash",
			"pluginspage", "http://www.adobe.com/go/getflashplayer"
	);
  } else {
    var alternateContent = 'Alternate HTML content should be placed here. '
  	+ 'This content requires the Adobe Flash Player. '
   	+ '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
    document.write(alternateContent);  // insert non-flash content
  }
	</script>
<noscript>
  	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="upl" width="100%" height="60%"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="upl.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#869ca7" />
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="wmode" value="transparent" />
			<embed src="servtools/flash_mod/upl.swf" quality="high" bgcolor="#869ca7"
				width="100%" height="90%" name="upl" align="middle"
				play="true"
				loop="false"
				quality="high"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer" wmode="transparent"
				>
			</embed>
	</object>
</noscript>
</div>
</td>
</tr>
</table>
</body>
</html>