<?php 
	require("session_check.php");
	$is_check=check_rights("c06");
	if(!$is_check){
		echo $m_langpackage->m_no_pri;
		exit;
	}
	require("../foundation/fcontent_format.php");
	require("../foundation/module_album.php");
	//语言包引入
	$u_langpackage=new userslp;
	$f_langpackage=new foundationlp;
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	//变量获得
	$photo_url=short_check(get_argg('photo_url'));
	$user_id=intval(get_argg('user_id'));
	$img_info=getimagesize('../'.$photo_url);
  
  //表定义
  $t_recommend=$tablePreStr."recommend";
  
	$dbo = new dbex;
	dbtarget('w',$dbServs);  
	
	$sql="select show_ico from $t_recommend where user_id=$user_id";
	$recom=$dbo->getRow($sql);
	$recom_ico=$recom['show_ico'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type="text/javascript" src="img_cut/prototype.js"></script>
<script type="text/javascript" src="img_cut/drag.js"></script>
<script type="text/javascript" src="img_cut/cut_image.js"></script>

<style type="text/css">
*{ margin:0; padding:0;}
#wrapper{ clear:both;margin:10px; padding:0;}
#cut_div{ height:210px!important; width:255px; border:1px dashed #eee; background:#000; filter:alpha(opacity=20); opacity: 0.6;}
#preview_div{ height:100px; width:100px; border:1px solid #000; display:none;}
.zoom_disable{
  filter: Gray;
}
</style>
</head>

<body style='font-size:12px'>
<div id="maincontent">
    <div class="wrap">
        <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="show_ico_cut.php?user_id=<?php echo $user_id;?>"><?php echo $ad_langpackage->ad_top_slide;?></a> &gt;&gt; <a href="pic_cut.php?photo_url=<?php echo $photo_url;?>&user_id=<?php echo $user_id;?>"><?php echo $ad_langpackage->ad_image_cut;?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $f_langpackage->f_index_pic;?></h3>
            <div class="content">
<form name="form_ico_info" method="post" action="pic_save.action.php">
	<table class='form-table' style="clear:both">
		<tr>
			<td><?php echo $f_langpackage->f_img_preview?>：</td>
			<td>
				<div id='msg'>
					<img src='<?php echo "../".$recom_ico;?>' width='255px' height='210px' />
					<input type="hidden" name="u_ico_url" value="<?php echo $recom_ico;?>">
				</div>
			</td>
		</tr>
	</table>
	           
	<table class='form-table' style="clear:both;">
		<tr>
			<td style="padding-top:10px;"><img src="spacer.gif" width=1 height=1></td>
		</tr>
		
		<tr>
			<td style="width:100%">
				<div id="wrapper">
					<input type=hidden name='cut_image_path' id='cut_image_path' value='<?php echo $photo_url;?>' />
					<input type='hidden' name='user_id' value='<?php echo $user_id;?>' />
					<div id="cut_div" onDblClick="CutImageUtil.end_cut_image();" title="<?php echo $u_langpackage->u_dbc;?>"></div>
					<div style="padding-bottom:18px;">
						<table cellspacing="2" cellpadding="2" id="table1">
							<tr>
								<td id='zoom_in_l' onclick="zoom_in();" class='hand' align='right'><img id='zoom_in_m' src='images/zoom_in.jpg'></td>
								<td id='zoom_in_r' onclick="zoom_in();" class='hand' align='left'><?php echo $u_langpackage->u_lar;?></td>
								<td id='zoom_out_l' onclick="zoom_out();" class='hand' align='right'><img id='zoom_out_m' src='images/zoom_out.jpg'></td>
								<td id='zoom_out_r' onclick="zoom_out();" class='hand' align='left'><?php echo $u_langpackage->u_sma;?></td>
								<td align="right">
									<input type='button' class="regular-button" onclick="CutImageUtil.end_cut_image();" value='<?php echo $u_langpackage->u_cut;?>'>
									<input type='button' class="regular-button" onclick="javascript:document.form_ico_info.submit();" value='<?php echo $f_langpackage->f_refer;?>'>
								</td>
								<td align="left" width="10">
								</td>
							</tr>
						</table>
					</div>
	
					<div style="float:left; margin:0 0 0 10px 10px; padding-left:0px;">
						<div style="clear:both;border:1px #bbb solid; padding:2px;">
							<img id="cut_img" src="<?php echo '../'.$photo_url;?>" ondatasetcomplete='init_dom();' style="margin:0;padding:0;" />
						</div>
					</div>
				
				</div>
				
				<div style="float:left; margin-left:40px;">
					<div id="preview_div"></div>
				</div>
				
				<script type="text/javascript">   
					var CutImageUtil = new CutImageClass('cut_img' , 'cut_div' , 'preview_div');
				  CutImageUtil.begin_cut_image();
					init_dom();//初始化对象函数
					
			    function init_dom(){
						var CutImageUtil = new CutImageClass('cut_img' , 'cut_div' , 'preview_div');
						var timer;
						if(document.readyState=="complete"){
							 init_img();
						   CutImageUtil.begin_cut_image();					   
						   clearTimeout(timer);
						}else{
						  timer=setTimeout('init_img(),CutImageUtil.begin_cut_image()',100);
					  }
			    }
			    
					function init_img(){
					  var max_width=550;//图片最大宽度
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
				    var height_width=img_height/img_width;	//高宽比
				    var img_zoom_num=img_width*0.2;	    
				    var img_ojb=document.getElementById("cut_img");
				    var img_width_new=img_width-img_zoom_num;
				    var img_height_new=height_width*img_width_new;
				    if(img_width_new>255&&img_height_new>210){
				        img_ojb.style.width=img_width_new+"px";
				        zoom_in_enable();
				    }else{
				        zoom_out_disable();
				    }
				    
				    CutImageUtil.begin_cut_image();
				}
				
					function zoom_in(){
					  var max_width=550;//图片最大宽度
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
				        $('zoom_out_m').src='images/zoom_out_dis.jpg';
				        $('zoom_out_l').className='';
				        $('zoom_out_r').className='';
				 }
				 
					function zoom_out_enable(){
				        $('zoom_out_m').src='images/zoom_out.jpg';
				        $('zoom_out_l').className='hand';
				        $('zoom_out_r').className='hand';
				 }
				 
					function zoom_in_disable(){
				        $('zoom_in_m').src='images/zoom_in_dis.jpg';
				        $('zoom_in_l').className='';
				        $('zoom_in_r').className='';
				 }
				 
					function zoom_in_enable(){
				        $('zoom_in_m').src='images/zoom_in.jpg';
				        $('zoom_in_l').className='hand';
				        $('zoom_in_r').className='hand';
				 }
				</script>
	               	
			</td>
		</tr>
		
	</table>
</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
