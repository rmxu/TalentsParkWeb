<?php
/*公共包含文件*/
//语言包切换过程
require_once($webRoot."foundation/achange_lp.php");
//语言包路径
$langPackageBasePath="langpackage/".$langPackagePara."/";
//语言包
require_once($webRoot.$langPackageBasePath."backend.php");
require_once($webRoot.$langPackageBasePath."public.php");
require_once($webRoot.$langPackageBasePath."group.php");
require_once($webRoot.$langPackageBasePath."album.php");
require_once($webRoot.$langPackageBasePath."msgscrip.php");
require_once($webRoot.$langPackageBasePath."blog.php");
require_once($webRoot.$langPackageBasePath."users.php");
require_once($webRoot.$langPackageBasePath."msgboard.php");
require_once($webRoot.$langPackageBasePath."recentaffair.php");

//数据库配置及连接文件
require_once($webRoot.$baseLibsPath."conf/dbconf.php");
require_once($webRoot.$baseLibsPath."fdbtarget.php");
require_once($webRoot.$baseLibsPath."libs_inc.php");

//表操作类
require_once($webRoot.$baseLibsPath."cdbex.class.php");

//过滤函数
require_once($webRoot."foundation/freq_filter.php");

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