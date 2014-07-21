<?php /*公共包含文件*/
//语言包切换过程
require_once($webRoot."foundation/achange_lp.php");

//语言包路径
$langPackageBasePath="langpackage/".$langPackagePara."/";

//语言包文件
require_once($webRoot.$langPackageBasePath."container.php");//前台公共语言包
require_once($webRoot.$langPackageBasePath."foundation.php");
require_once($webRoot.$langPackageBasePath."userapp.php");
require_once($webRoot.$langPackageBasePath."recentaffair.php");
require_once($webRoot.$langPackageBasePath."pubtools.php");
require_once($webRoot.$langPackageBasePath."mypals.php");
require_once($webRoot.$langPackageBasePath."group.php");
require_once($webRoot.$langPackageBasePath."album.php");
require_once($webRoot.$langPackageBasePath."msgscrip.php");
require_once($webRoot.$langPackageBasePath."blog.php");
require_once($webRoot.$langPackageBasePath."users.php");
require_once($webRoot.$langPackageBasePath."msgboard.php");
require_once($webRoot.$langPackageBasePath."mood.php");
require_once($webRoot.$langPackageBasePath."privacy.php");
require_once($webRoot.$langPackageBasePath."friend.php");
require_once($webRoot.$langPackageBasePath."public.php");
require_once($webRoot.$langPackageBasePath."remind.php");
require_once($webRoot.$langPackageBasePath."guest.php");
require_once($webRoot.$langPackageBasePath."reg.php");
require_once($webRoot.$langPackageBasePath."goback_info.php");
require_once($webRoot.$langPackageBasePath."poll.php");
require_once($webRoot.$langPackageBasePath."share.php");
require_once($webRoot.$langPackageBasePath."report.php");
require_once($webRoot.$langPackageBasePath."plugins.php");

//offline信息页面
require_once($webRoot."foundation/aoffline.php");

//访问限制
require_once($webRoot."foundation/alimit_guest.php");

//数据库配置及连接文件
require_once($webRoot.$baseLibsPath."conf/dbconf.php");
require_once($webRoot.$baseLibsPath."fdbtarget.php");
require_once($webRoot.$baseLibsPath."libs_inc.php");

//表操作类
require_once($webRoot.$baseLibsPath."cdbex.class.php");

//过滤函数
require_once($webRoot."foundation/freq_filter.php");

//main_iframe呈现应用工具控制函数
require_once($webRoot."foundation/fmain_target.php");

//时间函数
require_once($webRoot."foundation/ctime.class.php");

//文件上传函数
require_once($webRoot."foundation/cupload.class.php");

//积分配置
require_once($webRoot.$baseLibsPath."integral.php");

//封装session
require_once($webRoot."foundation/fsession.php");

//封装get post
require_once($webRoot."foundation/fgetandpost.php");

//权限验证
require_once($webRoot."foundation/fcheck_rights.php");
?>