<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/msgscrip/rpshow.html
 * 如果您的模型要进行修改，请修改 models/modules/msgscrip/rpshow.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$m_langpackage=new msglp;
	$mp_langpackage=new mypalslp;
	require('api/base_support.php');
	
  //变量获得
  $msg_id=intval(get_argg("id"));
  $user_id=get_sess_userid();
	$type=intval(get_argg("t"));
	$send_join_js='';
	
  //数据表定义
  $t_msg_inbox = $tablePreStr."msg_inbox";
  $t_msg_outbox = $tablePreStr."msg_outbox";
  
  if($type==1){
  	$dbo = new dbex;
		//读写分离定义函数
		dbtarget('r',$dbServs);
    $sql="select mess_title,mess_content,to_user_id,to_user,to_user_ico,add_time,state,mess_id "
         ."from $t_msg_outbox where mess_id='$msg_id'";
		$msg_row = $dbo ->getRow($sql);
		
    $relaUserStr=$m_langpackage->m_to_user;
    $reTurnTxt=$m_langpackage->m_out;
    $reTurnUrl="modules.php?app=msg_moutbox";
		$mess_id=$msg_row['mess_id'];
    if($msg_row['state']=="0"){
       $reButTxt=$m_langpackage->m_b_sed;
       $reButUrl="do.php?act=msg_send&to_id=$mess_id";
    }else{
       $reButTxt=$m_langpackage->m_b_con;
       $reButUrl=$reTurnUrl;
    }
  }else{
		$dbo = new dbex;
		//读写分离定义函数
		dbtarget('r',$dbServs);

		$sql="select mess_title,mess_content,from_user_id,from_user,from_user_ico,add_time,mesinit_id,mess_id,readed "
		   ."from $t_msg_inbox where mess_id='$msg_id'";
		$msg_row = $dbo ->getRow($sql);
		$relaUserStr=$m_langpackage->m_from_user;
		$reTurnTxt=$m_langpackage->m_in;
		$reButTxt=$m_langpackage->m_b_com;
		$reTurnUrl="modules.php?app=msg_minbox";
		$mess_id=$msg_row['mess_id'];
		$from_user_id=$msg_row['from_user_id'];
		$mess_title=$msg_row['mess_title'];
		$mesint_id=$msg_row['mesinit_id'];
		$reButUrl="modules.php?app=msg_creator&2id=$from_user_id&rt=".urlencode($mess_title);
		if($type=='2'){
			$send_join_js="mypals_add($from_user_id);";
			$reTurnUrl="modules.php?app=msg_notice";
			$reButTxt=$m_langpackage->m_b_bak;
			$reTurnTxt=$m_langpackage->m_to_notice;
			$reButUrl=$reTurnUrl;
 	 	}
		//读写分离定义函数
		dbtarget('w',$dbServs);
    if($msg_row['readed']=="0"){
      $sql="update $t_msg_inbox set readed='1' where mess_id=$mess_id";
      $dbo ->exeUpdate($sql);
      $sql="update $t_msg_outbox set state='2' where mess_id=$mesint_id";
      $dbo ->exeUpdate($sql);
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script type='text/javascript'>
function mypals_add_callback(content,other_id){
	if(content=="success"){
		parent.Dialog.alert("<?php echo $mp_langpackage->mp_suc_add;?>");
	}else{
		parent.Dialog.alert(content);
	}
}

function mypals_add(other_id){
	var mypals_add=new Ajax();
	mypals_add.getInfo("do.php","get","app","act=add_mypals&other_id="+other_id,function(c){mypals_add_callback(c,other_id);}); 
}
</script>
</head>
<body id="iframecontent">
    <div class="create_button"><a href="<?php echo $reTurnUrl;?>"><?php echo $m_langpackage->m_b_bak;?><?php echo $reTurnTxt;?></a></div>
    <h2 class="app_msgscrip"><?php echo $m_langpackage->m_title;?></h2>
    <div class="tabs">
        <ul class="menu">
            <li class="active"><a href="javascript:void(0)" hidefocus="true"><?php echo $m_langpackage->m_mess_detail;?></a></li>
        </ul>
    </div>
	<table class="form_table <?php echo $isset_data;?>" >
		<tr>
			<th rowspan="5" valign="top" width="22%">
				<a class="avatar" href='home.php?h=<?php echo $msg_row[2];?>' target="_blank">
					<img src='<?php echo $msg_row[4];?>' onerror="parent.pic_error(this)" class='user_ico' />
				</a>
			</th>	
			<th><?php echo $relaUserStr;?>：</th>
			<td><?php echo $msg_row[3];?></td>
		</tr>
		
		<tr>
			<th><?php echo $m_langpackage->m_tit;?>：</th>
			<td><?php echo $msg_row[0];?></td>
		</tr>
		
		<tr>
			<th> <?php echo $m_langpackage->m_time;?>：</th>
			<td><?php echo $msg_row[5];?></td>
		</tr>
		<tr>
			<th><?php echo $m_langpackage->m_cont;?>：</td>
			<td><?php echo str_replace("{send_join_js}",$send_join_js,$msg_row[1]);?></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type=button class="regular-btn" value="<?php echo $reButTxt;?>" onclick="location.href='<?php echo $reButUrl;?>'">&nbsp;&nbsp;&nbsp;
				<input type=button class="regular-btn" value="<?php echo $m_langpackage->m_del;?>"	onclick='location.href="do.php?act=msg_del&id=<?php echo $msg_id;?>&t=<?php echo get_argg("t");?>";'>
			</td>
		</tr>
	</table>
</body>
</html>