<?php

?><?php
//ÒýÈëÓïÑÔ°ü
$ah_langpackage=new arrayhomelp;
?><div class="head">
    <h1><a title="" href="main.php"><img alt="" src="skin/<?php echo $skinUrl;?>/images/snslogo.gif"></a></h1>
    <div class="search">
        <div class="schbox">
        <form class="search_box" action="main.php" target="_blank" onsubmit="clear_def(this,'<?php echo $ah_langpackage->ah_enter_name;?>');">
            <input id="searchtop_input" maxlength='20' class="inpt" type="text" name='memName' value="<?php echo $ah_langpackage->ah_enter_name;?>" onblur="inputTxt(this,'set');" onfocus="inputTxt(this,'clean');" />
            <input class="btn" type="submit" value="" />
        	<input type='hidden' name='app' value='mypals_search_list' />
        </form>
        </div>
        <a href="modules.php?app=mypals_search" target="frame_content"><?php echo $u_langpackage->u_senior_search;?></a>
    </div>
</div>
<div class="clear"></div>
<div class="nav">
    <div class="left_nav">
        <dl>
            <dt><a href="modules.php?app=start" target="frame_content"><?php echo $mn_langpackage->mn_index;?></a></dt>
            <dt><a href="home.php?h=<?php echo get_sess_userid();?>"><?php echo $mn_langpackage->mn_home;?></a></dt>
            <dt onmouseover="apMenuShow(true);" onmouseout="apMenuShow(false);">
                <a href="main.php?app=mag_app"><?php echo $mn_langpackage->mn_app;?></a>
                <ul class="ap_menu" id="ap_menu" style="display:none;" onmouseover="apMenuShow(true);" onmouseout="apMenuShow(false);">
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/blog.gif" /></span><span class="am_center"><a href="main.php?app=blog_list" hidefocus="true"><?php echo $mn_langpackage->mn_blog;?></a></span><span class="am_right"><a href="modules.php?app=blog_edit" target="frame_content" hidefocus="true"><?php echo $mn_langpackage->mn_publish;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/share.gif" /></span><span class="am_center"><a href="main.php?app=share_list" hidefocus="true"><?php echo $mn_langpackage->mn_share;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/group.gif" /></span><span class="am_center"><a href="main.php?app=group" hidefocus="true"><?php echo $mn_langpackage->mn_group;?></a></span><span class="am_right"><a href="modules.php?app=group_creat" hidefocus="true" target="frame_content"><?php echo $mn_langpackage->mn_creat;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/pals.gif" /></span><span class="am_center"><a href="main.php?app=mypals" hidefocus="true"><?php echo $mn_langpackage->mn_pal;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/msg_minbox.gif" /></span><span class="am_center"><a href="main.php?app=msg_minbox" hidefocus="true"><?php echo $mn_langpackage->mn_scrip;?></a></span><span class="am_right"><a href="modules.php?app=msg_creator" target="frame_content" hidefocus="true"><?php echo $mn_langpackage->mn_creat;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/msgboard.gif" /></span><span class="am_center"><a href="main.php?app=msgboard_more" hidefocus="true"><?php echo $mn_langpackage->mn_msg;?></a></span></li>
                    <li><span class="am_left"><img src="skin/<?php echo $skinUrl;?>/images/user_hi.gif" /></span><span class="am_center"><a href="main.php?app=user_hi" hidefocus="true"><?php echo $mn_langpackage->mn_hi;?></a></span></li>
                    <li style="border-bottom:0; padding:4px 0 0 8px;"><span class="am_left"><img style="margin-top:6px;" src="skin/<?php echo $skinUrl;?>/images/appset.gif" /></span><span class="am_center"><a href="main.php?app=mag_app" hidefocus="true"><?php echo $ah_langpackage->ah_set_application;?></a></span></li>
                </ul>
            </dt>
            <dt><a href="modules.php?app=mypals_search_list&online=1" hidefocus="true" target="frame_content"><?php echo $mn_langpackage->mn_online;?></a></dt>
            <dt><a href="main.php?app=msg_minbox" hidefocus="true"><?php echo $mn_langpackage->mn_scrip;?></a></dt>
        </dl>
    </div>
    <div class="right_nav">
        <dl>
            <dt><a href="home.php?h=<?php echo get_sess_userid();?>" hidefocus="true"><?php echo filt_word(get_sess_username());?></a></dt>
            <dt><a href="modules.php?app=mypals_invite" target="frame_content" hidefocus="true"><?php echo $mn_langpackage->mn_user_invite;?></a></dt>
            <dt><a href="javascript:void(0);" hidefocus="true" onmouseout="setMenuShow(false);" onclick="setMenuShow(true);"><?php echo $mn_langpackage->mn_user_set;?></a>
            	<div id="set_menu_bridge" style="width:58px;height:13px;display:none;" onmouseover="setMenuShow(true);" onmouseout="setMenuShow(false);">
                    <ul id="set_menu" onmouseover="setMenuShow(true);" onmouseout="setMenuShow(false);" class="set_menu" style="display:none;">
                        <li class="user_info"><a href="modules.php?app=user_info" target="frame_content" hidefocus="true"><?php echo $u_langpackage->u_achives;?></a></li>
                        <li class="user_ico"><a href="modules.php?app=user_ico" target="frame_content" hidefocus="true"><?php echo $u_langpackage->u_icon;?></a></li>
                        <li class="user_pw_change"><a href="modules.php?app=user_pw_change" target="frame_content" hidefocus="true"><?php echo $u_langpackage->u_pw;?></a></li>
                        <li class="user_dressup"><a href="modules.php?app=user_dressup" target="frame_content" hidefocus="true"><?php echo $u_langpackage->u_dressup;?></a></li>
                        <li class="user_affair"><a href="modules.php?app=user_affair" target="frame_content" hidefocus="true"><?php echo $u_langpackage->u_set_affair;?></a></li>
                        <li class="user_privacy"><a href="modules.php?app=privacy" target="frame_content" hidefocus="true"><?php echo $mn_langpackage->mn_user_pri;?></a></li>
                    </ul>
            	</div>
            </dt>
            <dt style="background:none"><a href="do.php?act=logout" hidefocus="true"><?php echo $mn_langpackage->mn_out;?></a></dt>
        </dl>
    </div>
</div>