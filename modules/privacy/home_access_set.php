<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/privacy/home_access_set.html
 * 如果您的模型要进行修改，请修改 models/modules/privacy/home_access_set.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	//语言包引入
	$pr_langpackage=new privacylp;

	//变量获得
	$user_id=get_sess_userid();

	$user_privacy=api_proxy("user_self_by_uid","access_limit,access_questions,access_answers",$user_id);

	$access_qa_array=array(
		array("q"=>'',"a"=>''),array("q"=>'',"a"=>''),array("q"=>'',"a"=>'')
	);

	$arr_qs=explode(',',$user_privacy['access_questions']);
	$arr_as=explode(',',$user_privacy['access_answers']);

	$i=0;
	foreach($arr_qs as $str_q){
		if($str_q!=''){
		$access_qa_array[$i]['q']=$str_q;
		$access_qa_array[$i]['a']=$arr_as[$i];
		}
		$i++;
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base href='<?php echo $siteDomain;?>' />
<script type="text/javascript">
	
function check_form(){
	if(document.form1.home_acess.value==''){
		parent.Dialog.alert("<?php echo $pr_langpackage->pr_no_data;?>");
		return false;
	}
}
	
function show_aps(){
	document.getElementById('acess_pass_set').style.display='';
}

function hidden_aps(){     
	document.getElementById('acess_pass_set').style.display='none';
}
</script>

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>
<body id="iframecontent">
    <h2 class="app_blog"><?php echo $pr_langpackage->pr_conf;?></h2>
    <div class="tabs">
        <ul class="menu">
		    <li class="active"><a href="modules.php?app=privacy" title="<?php echo $pr_langpackage->pr_access;?>" ><?php echo $pr_langpackage->pr_access;?></a></li>
		    <li><a href="modules.php?app=pr_inputmess" title="<?php echo $pr_langpackage->pr_inputmess;?>"><?php echo $pr_langpackage->pr_inputmess;?></a></li>
		    <li><a href="modules.php?app=pr_reqcheck" title="<?php echo $pr_langpackage->pr_reqcheck;?>"><?php echo $pr_langpackage->pr_reqcheck;?></a></li>
        </ul>
    </div>
	<div class="rs_head"><?php echo $pr_langpackage->pr_home;?></div>
	<form id="form1" name="form1" method="post" action="do.php?act=pr_access" onsubmit="return check_form();">
	<table border="0" class='form_table'>
		<tr>
			<th width="20%"><input type="radio" value="0" name="home_acess" id="home_acess" <?php echo radio_checked(0,$user_privacy['access_limit']);?> onclick='javascript:hidden_aps();'>
			</th>
			<td width="80%">
				<label><?php echo $pr_langpackage->pr_public;?></label>
			</td>
		</tr>
		<tr>
	    	<th><input type="radio" value="1" name="home_acess" id="home_acess" <?php echo radio_checked(1,$user_privacy['access_limit']);?> onclick='javascript:hidden_aps();'>
			</th>
	    	<td><label><?php echo $pr_langpackage->pr_only_reger;?></label></td>
		</tr>
		<tr>
			<th><input type="radio" value="2" name="home_acess" id="home_acess" <?php echo radio_checked(2,$user_privacy['access_limit']);?> onclick='javascript:hidden_aps();'></th>
			<td><label><?php echo $pr_langpackage->pr_only_pals;?></label></td>
		</tr>
		<tr><th><input type="radio" value="3" name="home_acess" id="home_acess" <?php echo radio_checked(3,$user_privacy['access_limit']);?> onclick='javascript:show_aps();'></th><td align="left"><label><?php echo $pr_langpackage->pr_set_ques;?></label></td>
		</tr>
		<tr id='acess_pass_set' style='display:none;'>
			<th>&nbsp;</th>
			<td>        
				<div>
					<label><?php echo $pr_langpackage->pr_ques1;?>： </label>
					<input class="med-text" type="text" value="<?php echo $access_qa_array[0]['q'];?>" name="question_1" maxlength="20" size="20"></div><div>
					<label><?php echo $pr_langpackage->pr_ans1;?>： </label>
					<input class="med-text" type="text" value='<?php echo $access_qa_array[0]['a'];?>' name="answer_1" maxlength="20" size="20">
				</div>
				<div>
					<label><?php echo $pr_langpackage->pr_ques2;?>： </label>
					<input class="med-text" type="text" value="<?php echo $access_qa_array[1]['q'];?>" name="question_2" maxlength="20" size="20"></div><div>
					<label><?php echo $pr_langpackage->pr_ans2;?>： </label>
					<input class="med-text" type="text" value="<?php echo $access_qa_array[1]['a'];?>" name="answer_2" maxlength="20" size="20">
				</div>	
				<div>											
					<label><?php echo $pr_langpackage->pr_ques3;?>： </label>
					<input class="med-text" type="text" value="<?php echo $access_qa_array[2]['q'];?>" name="question_3" maxlength="20" size="20"></div><div>
					<label><?php echo $pr_langpackage->pr_ans3;?>： </label>
					<input class="med-text" type="text" value="<?php echo $access_qa_array[2]['a'];?>" name="answer_3" maxlength="20" size="20">
				</div>
				<p><?php echo $pr_langpackage->pr_que_limit;?></p>
				<div class="example">
					<strong><?php echo $pr_langpackage->pr_example;?></strong> 
					<?php echo $pr_langpackage->pr_ex_que;?>
					<br />
					<?php echo $pr_langpackage->pr_ex_an;?>
				</div>
			</td>
  		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="Submit" class="regular-btn" value="<?php echo $pr_langpackage->pr_button_action;?>" /><input type="button" name="Submit2" class="regular-btn" onclick="location.href='modules.php?app=privacy'" value="<?php echo $pr_langpackage->pr_button_cancel;?>" /></td>
		</tr>  
	</table>
<?php if(strstr(radio_checked(3,$user_privacy['access_limit']),'checked')){?>
 <script type="text/javascript">show_aps();</script>
<?php }?>
</form>
</body>
</html>
