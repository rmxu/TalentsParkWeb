<?php 
//前台容器页语言包
class arrayhomelp{
	var $ah_reply = "回复";
	var $ah_label = "标签";
	var $ah_enable_dress = "是否启用此装扮";
	var $ah_system_will = "系统将在";
	var $ah_seconds_return = "秒钟返回首页...";
	var $ah_click_return_home = "如果浏览器没有自动调整，点击这里返回首页";
	var $ah_have = "有";
	var $ah_had_seen = "人看过";
	var $ah_more_mood = "更多心情";
	var $ah_personal_homepage = "个人主页";
	var $ah_data = "资 料";
	var $ah_log = "日 志";
	var $ah_album = "相 册";
	var $ah_share = "分 享";
	var $ah_vote = "投 票";
	var $ah_groups = "群 组";
	var $ah_visitors = "访客";
	var $ah_more = "更多";
	var $ah_friends_circle = "朋友圈";
	var $ah_current_online = "当前在线";
	var $ah_offline = "离线";
	var $ah_stealth = "隐身";
	var $ah_friends_add_suc = "好友添加成功";
	var $ah_browser_clipboard = "您的浏览器不允许脚本访问剪切板，请手动设置！";
	var $ah_enter_name = "输入姓名...";
	var $ah_advanced_search = "高级搜索";
	var $ah_homepage = "首页";
	var $ah_see_who_online = "看谁在线";
	var $ah_set_application = "设置应用";
	var $ah_add_friend = "加为好友";
	var $ah_say_hello_to = "向TA打招呼";
	var $ah_send_letter = "发站内信";
	var $ah_report_user = "举报该用户";
	var $ah_forgot_password = "忘记密码";
	var $ah_total = "共有";
	var $ah_member_events_here = "名会员活动在这里...";
	var $ah_personal_space = "个人空间";
	var $ah_groups_share = "群组/分享";
	var $ah_game_application = "游戏应用";
	var $ah_personal_space_detail = "<dd>建立自己的空间，发表日志、照片，分</dd><dd>享生活中的点点滴滴..</dd>";
	var $ah_groups_share_detail = "<dd>创建自己的群组，与志同道合者讨论感</dd><dd>兴趣话题，分享交流信息...</dd>";
	var $ah_game_application_detail = "<dd>与好友们一起玩超酷的互动游戏和应用</dd><dd>满足你休闲娱乐的需求...</dd>";
	var $ah_loading_data = "数据加载中...";
	var $ah_fill_content = "请填写留言内容！";
	var $ah_latest_photos = "最新照片";
	var $ah_view_all_my_photos = "查看我的全部照片";
	var $ah_all_photos = "全部照片";
	var $ah_latest_blog = "最新日志";
	var $ah_see_all_my_log = "查看我的全部日志";
	var $ah_all_logs = "全部日志";
	var $ah_you_can_enter = "您还可以输入";
	var $ah_word = "字";
	var $ah_to = "给";
	var $ah_message = "留言";
	var $ah_expression = "表情";
	var $ah_new_nothing = "新鲜事";
	var $ah_message_board = "留言板";
	var $ah_see_more_novelty = "查看更多新鲜事";
	var $ah_welcome_you = "欢迎您，";
	var $ah_invite_you_friends = "热情邀请您为好友。";
	var $ah_after_friend = "成为好友后，您们就可以一起讨论话题，及时关注对方的更新，还可以玩有趣的游戏 ... <br />您也可以方便快捷地发布自己的日志、上传图片、记录生活点滴与好友分享。 <br />还等什么呢？赶快加入我们吧。";
	var $ah_times = "次";
	var $ah_basic_info="基本信息";
	var $ah_birthday="生 日";
	var $ah_hometown="家 乡";
	var $ah_residence="居住地";
}
//错误机制语言包
class errorlp{
	var $er_db_unset="数据库设置出现问题，请查看相关的数据库、表和字段是否正确";
	var $er_dont_know="系统出现未知错误";
	var $commit_bug="您可以把遇到的问题提交到iwebSNS的Bug讨论区";
	var $er_refuse_guest="对不起，当前的时间段拒绝访问网站。";
	var $er_refuse_action="对不起，当前的时间段拒绝与网站进行交互。";
	var $er_refuse_ip="对不起，您的ip地址拒绝访问网站。";
}
//登录系统语言包
class loginlp{
	var $l_empty_mail="Email帐户不能为空！";
	var $l_empty_pass="密码不能为空!";
	var $l_empty_repa="重复密码项不能为空!";	
	var $l_not_check="登录帐号错误，请重试";
	var $l_wrong_pass="用户密码错误!";
	var $l_lock_u="对不起，您的账户已被锁定";
	var $l_loading="登录连接中...";
	var $l_email="登录邮箱";
	var $l_pass="密码";
	var $l_repass="重复密码";
	var $l_save_aco="记住我";
	var $l_hid="隐身登录";
	var $l_login="登录";
	var $l_r_aco="还没有开通你的聚易网帐号？";
	var $l_search_result="搜索结果";
	var $l_user_name="用户名";
	var $l_momber_login="会员登录";
	var $l_forget_pw="忘记密码了？";
	var $l_momber_register="会员注册";
	var $l_30s_register="30秒快速注册新用户！";
	var $l_wel_insert="期待你的加入";
	var $l_jooyea_star="推荐会员";
	var $l_new_momber="最新酷友";
	var $l_sell="我要自荐";
	var $l_location="所在地";
	var $l_chose="请选择";
	var $l_confirm="确定";
	var $l_age="年</label>龄";
	var $l_sex="性</label>别";
	var $l_no_restraint="不限";
	var $l_search="搜索";
	var $l_lady="女士";
	var $l_men="男士";
	var $l_close="关闭";
}
?>