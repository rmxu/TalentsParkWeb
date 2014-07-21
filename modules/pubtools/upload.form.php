<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/pubtools/upload.form.html
 * 如果您的模型要进行修改，请修改 models/modules/pubtools/upload.form.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php   
  //语言包引入
  $pu_langpackage=new pubtooslp;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>
  <base href='<?php echo $siteDomain;?>' />
  <script type="text/javascript" language="javascript">
  	function uploadImg(formObj)
  	{
  		if(document.getElementById("imgUrl").value == '')
  		{
  			return false;
  		}
  		return true;
  	}
  </script><link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/base.css">

  </head>
    <body style="background-color:transparent; text-align:left; padding-top:5px;*padding-top:8px">
    <form name="upload" method="post" action="do.php?act=upload_act" onSubmit="return uploadImg(this);" enctype="multipart/form-data" class="left">    
        <input type="file" class='left mr10' style="height:22px;" id="imgUrl" name="attach[]"/><input type="submit" class='small-btn left' name="submit" value=" <?php echo $pu_langpackage->pu_button;?> " /><div class="clear"></div>（<?php echo $pu_langpackage->pu_file_label;?>）     
    </form>
    </body>
</html>
