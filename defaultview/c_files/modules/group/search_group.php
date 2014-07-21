<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/group/search_group.html
 * 如果您的模型要进行修改，请修改 models/modules/group/search_group.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	//引入语言包
	$g_langpackage=new grouplp;
	
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("foundation/module_group.php");
	require("api/base_support.php");

	//变量区
	$userid=get_sess_userid();
	$url_uid = get_argg('url_uid');
	$user_join_group=get_sess_jgroup();
	$user_creat_group=get_sess_cgroup();

	//数据表定义
	$t_groups=$tablePreStr."groups";
	$t_group_members=$tablePreStr."group_members";

	//定义读操作
	dbtarget('r',$dbServs);	
	$dbo=new dbex();		
	$cols="1=1";
		
	//按群组名
	if(get_argg('group_name')){
		$search=short_check(get_argg('group_name'));
		$cols.=" and group_name like '%$search%' ";
	}
	
	//按群组标签名
	if(get_argg('tag')){
		$search=short_check(get_argg('tag'));
		$cols.=" and tag like '%$search%' ";
	}
	
	//按群组类型
	if(get_argg('group_type_id')){
		$search=intval(get_argg('group_type_id'));
		$cols.=" and group_type_id='$search' ";
	}
	$page_num=trim(get_argg('page')); 
	
	$condition="$cols and is_pass=1";
	$order_by="order by member_count desc";
	$type="getRs";
	$dbo->setPages(20,$page_num);//设置分页	
	$search=get_db_data($dbo,$t_groups,$condition,$order_by,$type);	
	$page_total=$dbo->totalPage;//分页总数

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($search)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}
	
	$g_join_num=$g_langpackage->g_join_num;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<base href='<?php echo $siteDomain;?>' />
<SCRIPT language=JavaScript src="servtools/ajax_client/ajax.js"></SCRIPT>
<script language="JavaScript">
function join_group(group_id){
	var join_group=new Ajax();
	join_group.getInfo("do.php","GET","app","act=group_join&group_id="+group_id,function(c){parent.Dialog.alert(c);}); 
}
</script>

<link rel="stylesheet" type="text/css" href="skin/<?php echo $skinUrl;?>/css/iframe.css">
</head>

<body id="iframecontent">
  <div class="create_button"><a href="modules.php?app=group_select"><?php echo $g_langpackage->g_return_search;?></a></div>
  <h2 class="app_friend"><?php echo $g_langpackage->g_find_group;?></h2>
  <div class="tabs">
    <ul class="menu">
		<li class="active"><a><span class="nowbutr"><?php echo $g_langpackage->g_find_group;?></a></li> 
        </ul>
  </div>

	
	<?php foreach($search as $rs){?>
			<div class="group_box" onmouseover="this.className = 'group_box_active';" onmouseout="this.className='group_box';">
			<div class="group_box_content">
                <div class="group_control">				<?php echo group_state($rs['group_id'],$rs['group_join_type'],$user_creat_group,$user_join_group,$rs['group_req_id']);?>
                    <a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo $g_langpackage->g_en_space;?></a>
                </div>
				<div class="group_photo"><a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><img src="<?php echo $rs['group_logo'] ? $rs['group_logo'] : 'uploadfiles/group_logo/default_group_logo.jpg';?>" width='100px' height='100px' onerror="parent.pic_error(this)" alt="<?php echo $rs['group_name'];?>" /></a></div>
				<dl class="group_list">
					<dt><a href='modules.php?app=group_space&group_id=<?php echo $rs["group_id"];?>&user_id=<?php echo $url_uid;?>'><?php echo filt_word($rs['group_name']);?></a></dt>
					<dd><?php echo $g_langpackage->g_type;?>：<?php echo $rs['group_type'];?></dd>
					<dd><?php echo $g_langpackage->g_founder;?>
：<a href='home.php?h=<?php echo $userid;?>' target="_blank"><?php echo filt_word($rs['group_creat_name']);?></a></dd>
					<dd><?php echo join_type($rs['group_join_type']);?><label><?php echo str_replace('{num}',$rs['member_count'],$g_join_num);?></label></dd>
				</dl>
			</div>
		</div>
	<?php }?>
<p class="clear">
<?php echo page_show($isNull,$page_num,$page_total);?>
</p>
	<div class="guide_info <?php echo $none_data;?>">
		<?php echo $g_langpackage->g_search_none;?>
	</div>
	
</body>
</html>