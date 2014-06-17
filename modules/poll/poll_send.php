<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/poll/poll_send.html
 * 如果您的模型要进行修改，请修改 models/modules/poll/poll_send.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
  //引入公共模块
  require("foundation/fpages_bar.php");
	require("foundation/module_poll.php");
	require("api/base_support.php");
	//限制时间段访问站点
	limit_time($limit_action_time);
  //语言包引入
  $pol_langpackage=new polllp;
  
  //变量区
	$user_id = get_sess_userid();
	$user_info = api_proxy("user_self_by_uid","integral",$user_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>blog</title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<script type='text/javascript' src='servtools/calendar.js'></script>
	
<script type="text/javascript" charset="utf-8">
function $(id_value){
	return document.getElementById(id_value);
}

function initIntro() {
	var introObj = $('intropoll');
	var tipObj = $('addtip');
	if(introObj.style.display == 'none') {
		introObj.style.display = '';
		tipObj.innerHTML = "<?php echo $pol_langpackage->pol_hidden_info;?>";
	} else {
		if (($('message').value.length == 0) || (confirm("<?php echo $pol_langpackage->pol_a_hidden;?>"))) {
			introObj.style.display = 'none';
			$('message').value = '';
			tipObj.innerHTML = "<?php echo $pol_langpackage->pol_add_info;?>";
		}
	}
}

function initReward(status) {
	var rewardObj = $('rewardlist');
	if(status == 1) {
		rewardObj.style.display = '';
	} else {
		rewardObj.style.display = 'none';
		$("credit").value = '';
		$("percredit").value = '';
	}
}

function showMoreOption() {
	$("moreoption").style.display = '';
	$("moretip").style.display = 'none';
}

function check_form(obj){
	var subj=$("subject").value;
	if(subj.length<1||subj.length>80){
		parent.Dialog.alert("<?php echo $pol_langpackage->pol_limit_sub;?>");
		return false;
	}
	
	var optionCount = 0;
	var optionObj = document.getElementsByName("option[]");
	for(var i=0;i<optionObj.length;i++) {
		if(optionObj[i].value.replace(/[\s\n\r]/g,"")!="") {
			optionCount++;
		}
	}
	if(optionCount<2) {
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_w_option;?>');
		return false;
	}
	
if($("credit").value){
	if(isNaN($("credit").value)){
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_award_num;?>');return false;
	}
	if(parseInt($("credit").value)><?php echo $user_info['integral'];?>||parseInt($("credit").value)<1){
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_total_range;?>1~<?php echo $user_info["integral"];?>');return false;
	}
	if($("percredit").value==''){
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_per_range;?>1~'+Math.min(parseInt($("credit").value),10));return false;
	}
	if(isNaN($("percredit").value)){
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_award_num;?>');return false;
	}
	if(parseInt($("percredit").value)>10||parseInt($("percredit").value) > parseInt($("credit").value)||parseInt($("percredit").value) < 1){
		parent.Dialog.alert('<?php echo $pol_langpackage->pol_per_range;?>1~'+Math.min(parseInt($("credit").value),10));return false;
	}
}
	var makefeed = $("makefeed");
	if(makefeed) {
		if(makefeed.checked == false) {
			return window.confirm('<?php echo $pol_langpackage->pol_w_affair;?>');
		}
	}
}
function get_min(){
	if(isNaN($('credit').value)){
		alert('<?php echo $pol_langpackage->pol_award_num;?>');
	}else{
		$("m_point").innerHTML="<?php echo $pol_langpackage->pol_per_range;?>1~"+Math.min(parseInt($('credit').value),10);
	}
}
</script>
</head>
<body id="iframecontent">
<div class="create_button"><a href="javascript:;" onclick="location.href='modules.php?app=poll_send'"><?php echo $pol_langpackage->pol_send;?></a></div>
<h2 class="app_vote"><?php echo $pol_langpackage->pol_title;?></h2>
<div class="tabs">
	<ul class="menu">
        <li><a href="modules.php?app=poll_list&m=new" hidefocus="true"><?php echo $pol_langpackage->pol_new;?></a></li>
        <li><a href="modules.php?app=poll_list&m=hot" hidefocus="true"><?php echo $pol_langpackage->pol_hot;?></a></li>
        <li><a href="modules.php?app=poll_list&m=reward" hidefocus="true"><?php echo $pol_langpackage->pol_reward;?></a></li>
        <li><a href="modules.php?app=poll_mine" hidefocus="true"><?php echo $pol_langpackage->pol_mine;?></a></li>
    </ul>
</div>
<div class="photo_view_box">
	<div class="photo_view_content">	
	
<form id="addnewpoll" name="addnewpoll" method="post" action="do.php?act=poll_add" onsubmit='return check_form(this);' autocomplete='off'>
<table class="form_table">
	<tr>
		<th height="38"><?php echo $pol_langpackage->pol_sub;?></td>
		<td>
			<input class="small-text" type="text" id="subject" name="subject" value="">
			<a id="addtip" href="javascript:;" onclick="initIntro();" onfocus="this.blur();"><?php echo $pol_langpackage->pol_add_info;?></a>
		</td>
	</tr>
	<tr id="intropoll" style="display:none">
		<th valign="top"><?php echo $pol_langpackage->pol_more_info;?></td>
		<td valign="top"><textarea id="message" class="textarea" name="message"></textarea> </td>
	</tr>
	<tr><td colspan="2" height="8"></td></tr>
	<?php echo poll_item(1);?>
	<tr>
		<td></td>
		<td>
			<div><a id="moretip" href="javascript:void(0);" onclick="showMoreOption();" onfocus="this.blur();"><?php echo $pol_langpackage->pol_add_m_option;?></a></div>
		</td>
	</tr>
	<tbody id="moreoption" style="display: none;">
		<?php echo poll_item(11);?>
	</tbody>
	<tr>
		<th><?php echo $pol_langpackage->pol_p_option;?></td>
		<td>
			<?php echo poll_select();?>
		</td>
	</tr>
	<tr>
		<th><?php echo $pol_langpackage->pol_over_time;?></td>
		<td>
			<input class="small-text" type="text" size="16" id="expiration" readonly name="expiration" value="<?php echo date("Y-m-d",time()+60*60*24*30);?>" onclick='calendar(this);' />
		</td>
	</tr>
	<tr>
		<th><?php echo $pol_langpackage->pol_limit;?></td>
		<td>
			<input type="radio" name="sex" value="2" checked /><?php echo $pol_langpackage->pol_no_limit;?>
			<input type="radio" name="sex" value="1" /><?php echo $pol_langpackage->pol_man;?>
			<input type="radio" name="sex" value="0" /><?php echo $pol_langpackage->pol_woman;?>
		</td>
	</tr>
	<tr>
		<th><?php echo $pol_langpackage->pol_com_limit;?></td>
		<td>
			<input type="radio" name="noreply" value="0" checked /><?php echo $pol_langpackage->pol_no_limit;?>
			<input type="radio" name="noreply" value="1" /><?php echo $pol_langpackage->pol_only_fri;?>
		</td>
	</tr>
	<tr>
		<th><?php echo $pol_langpackage->pol_award;?></td>
		<td>
			<input type="radio" name="reward" value="0" checked onclick="initReward(this.value);" /><?php echo $pol_langpackage->pol_no;?>
			<input type="radio" name="reward" value="1" onclick="initReward(this.value);" /><?php echo $pol_langpackage->pol_yes;?>
		</td>
	</tr>
	<tbody id="rewardlist" style="display: none;">
		<tr>
			<th><?php echo $pol_langpackage->pol_a_total;?></td>
			<td>
				<input type="text" class="SmallInput" size="5" id="credit" name="credit" value="" maxlength="5" onblur='get_min();'>
				<?php echo str_replace("{max_p}",$user_info['integral'],$pol_langpackage->pol_r_point);?>
			</td>
		</tr>
		<tr>
			<th><?php echo $pol_langpackage->pol_per_award;?></td>
			<td>
				<input type="text" size="5" id="percredit" name="percredit" value="" maxlength="5">
				<span id='m_point'></span>
			</td>
		</tr>
	</tbody>
	<tr><th><?php echo $pol_langpackage->pol_aff_o;?></td><td><input type="checkbox"  name="makefeed" id="makefeed" value="1" checked><?php echo $pol_langpackage->pol_affair;?></td></tr>	
	<tr><td></td><td><input class="regular-btn" style="border: 0;" type="submit" name="action" value="<?php echo $pol_langpackage->pol_con;?>" /></td></tr>
</table>
</form>
    </div></div>
</body>
</html>