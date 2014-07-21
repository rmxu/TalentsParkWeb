<?php
//语言包参数，目前参数值zh,en
$langPackagePara="zh";

//配置信息
$webRoot=strtr(dirname(__FILE__),"\\","/")."/";
$metaDesc="寻找你的校友";
$metaKeys="iwebSNS";
$metaAuthor="";
$siteName="校友网";
$copyright="2010版权所有www.jooyea.net";
$domainRemark="";
$offLine=0;
$adminEmail="";
$siteDomain="http://{$_SERVER['HTTP_HOST']}/TalentsParkWeb/";
$skinUrl="default/jooyea";
$tplAct="default";
$compileType="serve";
$indexFile="index.php";
$urlRewrite=2;
$inviteCode=0;
$inviteCodeLength=8;
$inviteCodeValue=1;
$allowReg=1;
$inviteCodeLife=72;

//时区设置
date_default_timezone_set ("Asia/Shanghai");

//支持库配置
$baseLibsPath="iweb_mini_lib/";

//防刷新时间设置,只限制insert动作.单位:秒
$allowRefreshTime=5;

//超限系统延时设置,单位:秒
$delayTime=5;

//开启缓存
$ctrlCache=1;

//缓存更新延时设置,单位为秒
$cache_update_delay_time=5;

//出生年份范围
$setMinYear=1950;
$setMaxYear=2000;

//站点调试信息设置
ini_set("display_errors",0);

//站点关闭提示信息
$offlineMessage="本网站目前正在维护中,请稍后再来访问";

//分页数据量
$cachePages=10;

//限制访问的时间段
$limit_guest_time="";

//限制交互时间段
$limit_action_time="";

//限制访问的ip列表
$limit_ip_list="";

//主页显示动态条数
$homeAffairNum=10;

//首页显示动态人数
$mainAffairNum=5;

//是否开启过滤
$wordFilt=1;

//敏感词过滤
$filtrateStr="";

//cookie开启校验
$cookieOpen=1;

//session前缀
global $session_prefix;
$session_prefix="isns_";

//plugins位置文件
$pluginOpsition=array("home.html","index.html","main.html","modules/blog/blog_edit.html");
?>