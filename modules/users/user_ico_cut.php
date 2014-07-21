<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/users/user_ico_cut.html
 * 如果您的模型要进行修改，请修改 models/modules/users/user_ico_cut.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php 
	//引入模块公共方法文件
	require("foundation/module_album.php");
	
	//语言包引入
	$u_langpackage=new userslp;
	
	//变量获得
	$user_id =get_sess_userid();
	$photo_url=short_check(get_argg('photo_url'));
	$img_info=getimagesize($photo_url);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type="text/javascript" src="servtools/img_cut/prototype.js"></script>
<script type="text/javascript" src="servtools/img_cut/drag.js"></script>
<script type="text/javascript" src="servtools/img_cut/cut_image.js"></script>

<style type="text/css">
*{ margin:0; padding:0;}
#wrapper{ clear:both;margin:10px; padding:0;}
#cut_div{ height:200px!important; width:200px; border:1px dashed #eee; background:#000; filter:alpha(opacity=20); opacity: 0.2;}
#preview_div{ height:200px; width:200px; border:1px solid #000; display:none;}
.zoom_disable{
  filter: Gray;
}
</style>
</head>

<body id="iframecontent">
<h2 class="app_user"><?php echo $u_langpackage->u_conf;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=user_info" title="<?php echo $u_langpackage->u_info;?>"><?php echo $u_langpackage->u_info;?></a></li>
        <li class="active"><a href="modules.php?app=user_ico" title="<?php echo $u_langpackage->u_icon;?>"><?php echo $u_langpackage->u_icon;?></a></li>
        <li><a href="modules.php?app=user_pw_change" title="<?php echo $u_langpackage->u_pw;?>"><?php echo $u_langpackage->u_pw;?></a></li>
        <li><a href="modules.php?app=user_dressup" title="<?php echo $u_langpackage->u_dressup;?>"><?php echo $u_langpackage->u_dressup;?></a></li>
        <li><a href="modules.php?app=user_affair" title="<?php echo $u_langpackage->u_set_affair;?>"><?php echo $u_langpackage->u_set_affair;?></a></li>
    </ul>
</div>
<form name="form_ico_info" method="post" action="do.php?act=user_ico_save&pic=<?php echo $photo_url;?>">
	
	<table class='form_table' style="clear:both" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $u_langpackage->u_ico_pre;?>：</th>
			<td>
				<div id='msg'>
					<img id="user_ico" src="<?php echo str_replace('_small','',get_sess_userico());?>" class="user_ico">
					<input type="hidden" name="u_ico_url" value="<?php echo get_sess_userico();?>" />
				</div>
			</td>
		</tr>
		
		<tr>
			<td style="width:10em;"><?php echo $u_langpackage->u_ico_spc;?>：</td>
			<td><?php echo $u_langpackage->u_ico_siz;?></td>
		</tr>
	</table>
	           
	<table width="500px" cellpadding="0" cellspacing="0" style="clear:both;margin-top:15px;margin-left:29px;">             
		<tr>
			<td style="padding-top:10px;"><img src="spacer.gif" width=1 height=1></td>
		</tr>
		
		<tr>
			<td style="width:100%">
				<div id="wrapper">
					<input type=hidden name='cut_image_path' id='cut_image_path' value='<?php echo $photo_url;?>' />
					<div id="cut_div" onDblClick="CutImageUtil.end_cut_image();" title="<?php echo $u_langpackage->u_dbc;?>"></div>
					<div style="padding-bottom:18px;">
						<table border="0" width="500" cellspacing="2" cellpadding="2" id="table1">
							<tr>
								<td width="80"><b><?php echo $u_langpackage->u_ico_cut;?>:</b></td>
								<td id='zoom_in_l' width="26" onclick="zoom_in();" class='hand' align='right'><img id='zoom_in_m' src='skin/<?php echo $skinUrl;?>/images/zoom_in.jpg'></td>
								<td id='zoom_in_r' width="30" onclick="zoom_in();" class='hand' align='left'><?php echo $u_langpackage->u_lar;?></td>
								<td id='zoom_out_l' width="26" onclick="zoom_out();" class='hand' align='right'><img id='zoom_out_m' src='skin/<?php echo $skinUrl;?>/images/zoom_out.jpg'></td>
								<td id='zoom_out_r' width="60" onclick="zoom_out();" class='hand' align='left'><?php echo $u_langpackage->u_sma;?></td>
								<td align="right">
									<input type='button' class="regular-btn" style="font-weight:normal" onclick="CutImageUtil.end_cut_image();" value='<?php echo $u_langpackage->u_cut;?>'>
								</td>
								<td>
									<input type='button' class="regular-btn" style="display:none; font-weight:normal" id="ico_action" onclick="javascript:document.form_ico_info.submit();" value='<?php echo $u_langpackage->u_ico;?>'>
								</td>
								<td align="left" width="10">
								</td>
							</tr>
						</table>
					</div>
					<div style="float:left; margin:0 0 0 10px 10px; padding-left:0px;">
						<div style="clear:both;border:1px #bbb solid; padding:2px;"><img id="cut_img" src="<?php echo $photo_url;?>" onreadystatechange='init_dom();' style="margin:0;padding:0;" /></div>
					</div>
				</div>
				<div style="float:left; margin-left:40px;">
					<div id="preview_div"></div>
				</div>
			</td>
		</tr>
		<tr>
			<th></th>
		</tr>
	</table>
</form>
</body>
<script type="text/javascript">   
	var CutImageUtil = new CutImageClass('cut_img' , 'cut_div' , 'preview_div');
  CutImageUtil.begin_cut_image();
	init_dom();//初始化对象函数
	
  function init_dom(){
		var CutImageUtil = new CutImageClass('cut_img' , 'cut_div' , 'preview_div');
		var timer;
		if(window.document.readyState==4){
			 init_img();
		   CutImageUtil.begin_cut_image();
		   clearTimeout(timer);
		}else{
		  timer=setTimeout('init_img(),CutImageUtil.begin_cut_image()',50);
	  }
  }
  
	function init_img(){
	  var max_width=500;//图片最大宽度
	  var img_width=<?php echo $img_info[0];?>;
	  var img_ojb=document.getElementById("cut_img");
	  if(img_width>max_width){
	  	  img_ojb.style.width=max_width+"px";
	  }else{
	  	  img_ojb.style.width=img_width+"px";
	  }
	}

	function zoom_out(){
    var img_width=document.getElementById("cut_img").offsetWidth;
    var img_height=document.getElementById("cut_img").offsetHeight;
    var height_width=img_height/img_width;//高宽比
    var img_zoom_num=img_width*0.2;
    var img_ojb=document.getElementById("cut_img");
    var img_width_new=img_width-img_zoom_num;
    var img_height_new=height_width*img_width_new;
    if(img_width_new>200&&img_height_new>200){
        img_ojb.style.width=img_width_new+"px";
        zoom_in_enable();
    }else{
        zoom_out_disable();
    }
    CutImageUtil.begin_cut_image();
	}

	function zoom_in(){
	  var max_width=530;//图片最大宽度
    var img_width=document.getElementById("cut_img").offsetWidth;
    var img_zoom_num=img_width*0.2;
    var img_ojb=document.getElementById("cut_img");
    var img_width_new=img_width+img_zoom_num;
    if(img_width_new<max_width){
        img_ojb.style.width=img_width_new+"px";
        zoom_out_enable();
    }else{
    	  zoom_in_disable();
    }
    
    CutImageUtil.begin_cut_image();
	}

	function zoom_out_disable(){
        $('zoom_out_m').src='skin/<?php echo $skinUrl;?>/images/zoom_out_dis.jpg';
        $('zoom_out_l').className='';
        $('zoom_out_r').className='';
 }
 
	function zoom_out_enable(){
        $('zoom_out_m').src='skin/<?php echo $skinUrl;?>/images/zoom_out.jpg';
        $('zoom_out_l').className='hand';
        $('zoom_out_r').className='hand';
 }
 
	function zoom_in_disable(){
        $('zoom_in_m').src='skin/<?php echo $skinUrl;?>/images/zoom_in_dis.jpg';
        $('zoom_in_l').className='';
        $('zoom_in_r').className='';
 }
 
	function zoom_in_enable(){
        $('zoom_in_m').src='skin/<?php echo $skinUrl;?>/images/zoom_in.jpg';
        $('zoom_in_l').className='hand';
        $('zoom_in_r').className='hand';
 }
</script>
</html>
