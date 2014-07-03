<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/blog/blog_edit.html
 * 如果您的模型要进行修改，请修改 models/modules/blog/blog_edit.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//限制时间段访问站点
	limit_time($limit_action_time);

	//引入模块公共方法文件
	require("foundation/module_blog.php");
	require("foundation/module_album.php");
	require("foundation/fplugin.php");

	//语言包引入
	$b_langpackage=new bloglp;

	//变量定义
	$user_id=get_sess_userid();

	//数据表定义
	$t_blog_sort=$tablePreStr."blog_sort";
	$t_blog=$tablePreStr."blog";
	$t_album=$tablePreStr."album";

	$ulog_id=intval(get_argg('id'));
	$titleStr=$b_langpackage->b_creat;
	$goBackUrl='modules.php?app=blog_list';
	$ulogTitle='';
	$usubPara='';
	$ulogTxt='';
	$album_id='';
	$form_action="do.php?act=blog_add";
	$blog_sort_name='';
  $privacy='';
  $privacy_str='';
  $tag='';
	//判断是否编辑blog内容
	if($ulog_id!=""){
		$titleStr=$b_langpackage->b_edit;
		$goBackUrl='modules.php?app=blog&id='.$ulog_id;
		$result=api_proxy("blog_self_by_bid","*",$ulog_id);
		$ulogTitle=$result['log_title'];
		$usubPara=$result['log_sort'];
		$ulogTxt=$result['log_content'];
		$blog_sort_name=$result['log_sort_name'];
    $form_action="do.php?act=blog_edit&id=".$ulog_id;
    $privacy=$result['privacy'];
    $privacy_str="$t_blog:$ulog_id:$privacy";
    $tag=$result['tag'];
	}
	$album_rs = api_proxy("album_self_by_uid","album_id,album_name",$user_id);
	$blog_sort_rs = api_proxy("blog_sort_by_uid",$user_id);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<?php $plugins=unserialize('a:0:{}');?>
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<link rel="stylesheet" type="text/css" href="servtools/menu_pop/menu_pop.css">
<script type='text/javascript' src='servtools/ajax_client/ajax.js'></script>
<script type='text/javascript' src='servtools/menu_pop/group_user.php'></script>
<script type='text/javascript' src="servtools/menu_pop/menu_pop.js"></script>
<SCRIPT language=JavaScript src="servtools/imgfix.js"></SCRIPT>
<SCRIPT language=JavaScript src="skin/default/js/jooyea.js"></SCRIPT>
<SCRIPT language=JavaScript src="servtools/editor/nicEdit.js"></SCRIPT>
<script Language="JavaScript">
bkLib.onDomLoaded(function(){
	new nicEditor({fullPanel : true}).panelInstance('CONTENT');
});
var oldContent = '<?php echo $ulogTxt;?>';
function CheckForm(){
	var content_inner=$("CONTENT").previousSibling.children[0].innerHTML;
	oldContent = content_inner;
	if(document.myform.blog_title.value==""){
		parent.Dialog.alert("<?php echo $b_langpackage->b_empty_t;?>");
		document.myform.blog_title.focus();
		return (false);
	}
	if(trim(content_inner)==""){
		parent.Dialog.alert("<?php echo $b_langpackage->b_empty_content;?>");
		return false;
   }
}
window.onbeforeunload = function ()
{
	var newContent = document.getElementById("CONTENT").previousSibling.children[0];
	if(newContent && trim(newContent.innerHTML.toString()) == trim(oldContent.toString())){
		return;
	}else{
		return '<?php echo $b_langpackage->b_content_not_saved;?>';
	}
}
parent.hiddenDiv();
</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="#" onclick="javascript:window.history.go(-1);return false;"><?php echo $b_langpackage->b_re_last;?></a></div>
    <h2 class="app_blog"><?php echo $titleStr;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="modules.php?app=blog_list" hidefocus="true"><?php echo $titleStr;?></a></li>
        </ul>
    </div>
   <form action="<?php echo $form_action;?>" method="post"  name="myform" onSubmit="return CheckForm();">
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
        <tr>
			<th><?php echo $b_langpackage->b_title;?>：</th>
			<td colspan="2"><input class="med-text" type="text" autocomplete='off' name="blog_title" value="<?php echo $ulogTitle;?>" maxlength="30">
			</td>
		</tr>
   		<tr>
			<th><?php echo $b_langpackage->b_sort;?>：</th>
			<td colspan="2">
			  <script language=JavaScript>
					function trim(str){ //删除左右两端的空格
						return str.replace(/(^\s*)|(\s*$)|(　*)/g , "");
					}
					function saveNowSort(){//获取接受返回信息层
						var sort_list=$("blog_sort_list");
						var sort_add_msg=$("sort_add_msg");
						var blog_sort=trim($("new_sort_name").value);
						$("blog_sort_name").value=blog_sort;
						if(blog_sort==""){
							parent.Dialog.alert("<?php echo $b_langpackage->b_empty;?>");
							return false;
						}
						//需要POST的值，把每个变量都通过&来联接
						var postStr="new_sort="+blog_sort;
						var saveNowSort=new Ajax();//实例化Ajax
						saveNowSort.getInfo("do.php?act=blog_sort_add","post","app","new_sort="+blog_sort,function(c){sort_list.innerHTML=c;newCancel();});
					}

			  function newShow(){
					document.getElementById('newSort').style.display='';
					document.getElementById('sortManage').style.display='none';
			  }

        function newCancel(){
      		var sort_add_msg=document.getElementById("sort_add_msg");
      		document.getElementById('new_sort_name').value='';
          document.getElementById('newSort').style.display='none';
          document.getElementById('sortManage').style.display='';
        }
        </script>
         <table border="0">
         	   <tr>
         	    	<td>
	         	      <div id=blog_sort_list>
	                    <?php echo blog_sort_list($blog_sort_rs,$usubPara);?>
	                  </div>
                    </td>
                <td>
                	<input type='hidden' name='blog_sort_name' id='blog_sort_name' value='<?php echo $blog_sort_name;?>' />
	                <span id='sort_add_msg'></span>
                    <div id=newSort style="display:none">
                        <input type=text class="small-text" name='new_sort_name' maxlength="20" id='new_sort_name' width='5'>
                        <input type=button class="small-btn" value='<?php echo $b_langpackage->b_button_save;?>' onclick="saveNowSort();">
                        <input type=button class="small-btn" value='<?php echo $b_langpackage->b_button_cancel;?>' onclick="newCancel();">
                    </div>
                    <span id=sortManage>
                        &nbsp;<a onclick="newShow();" href='javascript:;'><?php echo $b_langpackage->b_add_sort;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='modules.php?app=blog_manager_sort'><?php echo $b_langpackage->b_manage_sort;?></a>
                    </span>
                </td>
              </tr>
          </table>
        </td>
      </tr>
      <tr>
      	<th><?php echo $b_langpackage->b_label;?>：</th>
        <td><input type='text' class='small-text' name='tag' value='<?php echo $tag;?>' /></td>
        <td><span class="right"><input type='hidden' name='privacy' id='privacy' value='<?php echo $privacy;?>' /></span></td>
      </tr>
      <tr>
		<th width="60"><?php echo $b_langpackage->b_limit;?>：</th>
		<td colspan="2">
			<a href="javascript:void(0)" onmousedown='menu_pop_show(event,this,1);' id='<?php echo $t_blog;?>:<?php echo $ulog_id;?>:<?php echo $privacy;?>' class="authority_set"><?php echo $b_langpackage->b_set_permissions;?></a></div>
		</td>
		</tr>      
      <tr>
		<th valign="top"><?php echo $b_langpackage->b_content;?>：</th>
        <td colspan="2" style="line-height:1.5">
			   <textarea name="CONTENT" id="CONTENT" class="textarea" style='width:550px;height:300px;_width:550px;'><?php echo $ulogTxt;?></textarea>
		</td>
	    </tr>
	   	<tr>
            <th valign="top"><?php echo $b_langpackage->b_pic;?>：</th>
            <td colspan="2">
                <div id="POPUP_KE_IMAGE">
                    <iframe name="KindImageIframe" id="KindImageIframe" width="100%" height=50  allowTransparency="true" scrolling=no src='modules.php?app=upload_form' frameborder=0></iframe>
                </div>
            </td>
	    </tr>
			<!-- plugins !-->
			<div class="blog_add_bottom">
				<?php echo isset($plugins['blog_add_bottom'])?show_plugins($plugins['blog_add_bottom']):'';?>
			</div>
			<!-- plugins !-->

     		<tr>
			<th></th>
			<td colspan="2">
				<input type=submit class="regular-btn" value="<?php echo $b_langpackage->b_button_sure;?>" />
				<input type=button class="regular-btn" value="<?php echo $b_langpackage->b_button_cancel;?>" onclick='location.href="<?php echo $goBackUrl;?>"' />
			</td>
    </tr>
  </table>
  </form>
</body>
</html>