<?php
header("content-type:text/html;charset=utf-8");
define('ROOT_PATH', substr(dirname(strtr(__FILE__,'\\','/')), 0, -7));
$lockfile = ROOT_PATH.'docs/install.lock';
if(file_exists($lockfile)) {
	echo '警告!您已经安装过iweb_sns<br>
		为了保证数据安全，请立即手动删除 install 文件夹<br>
		如果您想重新安装iweb_sns，请删除 docs/install.lock 文件，<a href="index.php">再运行安装文件</a>';
	exit;
}

// 防止 PHP 5.1.x 使用时间函数报错
function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS,$HTTP_SESSION_VARS,$HTTP_SERVER_VARS);
unset($GLOBALS['_ENV'],$GLOBALS['HTTP_ENV_VARS'],$GLOBALS['_REQUEST'],$GLOBALS['HTTP_POST_VARS'],$GLOBALS['HTTP_GET_VARS'],$GLOBALS['HTTP_POST_FILES'],$GLOBALS['HTTP_COOKIE_VARS'],$GLOBALS['HTTP_SESSION_VARS'],$GLOBALS['HTTP_SERVER_VARS']);

if (ini_get('register_globals')){
	isset($_REQUEST['GLOBALS']) && die('发现试图覆盖 GLOBALS 的操作');
	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE','_SERVER', '_ENV', '_FILES');
	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
	foreach ( $input as $k => $v ){
		if ( !in_array($k, $noUnset) && isset($GLOBALS[$k]) ) {
			$GLOBALS[$k] = NULL;
			unset($GLOBALS[$k]);
		}
	}
}
// Fix for IIS, which doesn't set REQUEST_URI
if ( empty( $_SERVER['REQUEST_URI'] ) ) {

	// IIS Mod-Rewrite
	if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
		$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
	}
	// IIS Isapi_Rewrite
	else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
		$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
	}else{
		// Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
		if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
			$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
		else
			$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];

		// Append the query string if it exists and isn't null
		if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
			$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
}

// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
if ( isset($_SERVER['SCRIPT_FILENAME']) && ( strpos($_SERVER['SCRIPT_FILENAME'], 'php.cgi') == strlen($_SERVER['SCRIPT_FILENAME']) - 7 ) )
	$_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

// Fix for Dreamhost and other PHP as CGI hosts
if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false)
	unset($_SERVER['PATH_INFO']);

// Fix empty PHP_SELF
$PHP_SELF = $_SERVER['PHP_SELF'];
if ( empty($PHP_SELF) ){
	$_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]);
}

if ( version_compare( '5.0', phpversion(), '>' ) ) {
	die( '您的服务器运行的 PHP 版本是' . phpversion() . ' 但 iweb_sns 要求至少 5.0。' );
}

if ( !extension_loaded('mysql')){
	die( '您的 PHP 安装看起来缺少 MySQL 数据库部分，这对 iweb_sns 来说是必须的。' );
}

require_once(ROOT_PATH.'install/common.php');
if ( get_magic_quotes_gpc() ) {
	$_GET    = stripslashes_deep($_GET);
	$_POST   = stripslashes_deep($_POST);
	$_COOKIE = stripslashes_deep($_COOKIE);
}
$_GET    = add_magic_quotes($_GET);
$_POST   = add_magic_quotes($_POST);
$_COOKIE = add_magic_quotes($_COOKIE);
$_SERVER = add_magic_quotes($_SERVER);

!$_SERVER['PHP_SELF'] && $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];
$isnsDIR=preg_replace(array('/^\//','/(install)$/'),'',dirname($_SERVER['PHP_SELF']));
$step = isset($_POST['step']) ? $_POST['step'] : '1';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iweb_sns - 安装向导</title>
<link type="text/css" rel="stylesheet" href="css/install.css" />
<script type="text/javascript">
	function $(id) {
		return document.getElementById(id);
	}

	function showMsg(msg) {
		var notice = $('notice');
		notice.value += msg + "\r\n";
		notice.scrollTop = notice.scrollHeight
	}
</script>
</head>
<body>
<div class="head"></div>
<div class="nav nav_<?php echo $step;?>"></div>
<div class="main">
	<div class="top"></div>
    <div class="center">
    <form method="post" action="<?php echo $PHP_SELF;?>">
<?php if($step == '1') {
	$check=1;
	$no_write=$isnsDIR."程序根目录无法书写,请速将根目录属性设置为0777";
	$correct='<font style="color:green;">√</font>';
	$incorrect='<font style="color:red;">× 0777属性检测不通过</font>';
	$uncorrect='<font style="color:red;">× 文件不存在请上传此文件</font>';
	$w_check=array(
		1=>array('path'=>'uploadfiles', 'competence'=>'读/写/删', 'explain'=>'文件上传目录', 'result'=>''),
		2=>array('path'=>'plugins', 'competence'=>'读/写/删', 'explain'=>'插件目录', 'result'=>''),
		3=>array('path'=>'skin', 'competence'=>'读/写/删', 'explain'=>'皮肤目录', 'result'=>''),
		4=>array('path'=>'templates', 'competence'=>'读/写/删', 'explain'=>'模板目录', 'result'=>''),
		5=>array('path'=>'models', 'competence'=>'读/写/删', 'explain'=>'模块程序目录', 'result'=>''),
		6=>array('path'=>'modules', 'competence'=>'读/写/删', 'explain'=>'程序执行目录', 'result'=>''),
		7=>array('path'=>'uiparts', 'competence'=>'读/写/删', 'explain'=>'程序段目录', 'result'=>''),
		8=>array('path'=>'modules.php', 'competence'=>'读/写', 'explain'=>'显示容器', 'result'=>''),
		9=>array('path'=>'do.php', 'competence'=>'读/写', 'explain'=>'执行容器', 'result'=>''),
		10=>array('path'=>'configuration.php', 'competence'=>'读/写', 'explain'=>'配置文件', 'result'=>''),
		11=>array('path'=>'docs/version.txt', 'competence'=>'读/写', 'explain'=>'版本信息', 'result'=>''),
		12=>array('path'=>'sysadmin/toolsBox', 'competence'=>'读/写/删', 'explain'=>'系统工具', 'result'=>''),
		13=>array('path'=>'main.php', 'competence'=>'读/写/删', 'explain'=>'main页面', 'result'=>''),
		14=>array('path'=>'home.php', 'competence'=>'读/写/删', 'explain'=>'home页面', 'result'=>''),
		15=>array('path'=>'index.php', 'competence'=>'读/写/删', 'explain'=>'index页面', 'result'=>''),
		16=>array('path'=>'foundation/fdelay.php', 'competence'=>'读/写/删', 'explain'=>'延迟刷新', 'result'=>''),
		17=>array('path'=>'iweb_mini_lib/conf/dbconf.php', 'competence'=>'读/写/删', 'explain'=>'数据库配置', 'result'=>''),
		18=>array('path'=>'docs', 'competence'=>'读/写/删', 'explain'=>'安装文件', 'result'=>''),
		19=>array('path'=>'docs/bak', 'competence'=>'读/写/删', 'explain'=>'升级备份目录', 'result'=>''),
	);
	if($fp=@fopen(ROOT_PATH.'test.txt',"w+")){
		$state=$correct;
		fclose($fp);
	} else{
		$state=$incorrect.$no_write;
		$check=0;
	}

	foreach($w_check AS $key=>$val){
		if(!file_exists(ROOT_PATH.$val['path'])){
			$w_check[$key]['result'] = $uncorrect;$check=0;
		} elseif(is_writable(ROOT_PATH.$val['path'])){
			$w_check[$key]['result'] = $correct;
		} else{
			$w_check[$key]['result'] =$incorrect; $check=0;
		}
	}
	$check && @unlink(ROOT_PATH.'test.txt');
?>
    	<div class="tips"><p><strong>夺彩互联网，创新IT动力</strong></p></div>
        <table class="list" width="100%">
              <tr>
                <th>名称</th>
                <th>所需权限属性</th>
                <th>说明</th>
                <th>检测结果</th>
              </tr>
				<?php
					foreach($w_check as $key=>$val){
						echo '<tr><td>'.$val['path'].'</td><td>'.$val['competence'].'</td><td>'.$val['explain'].'</td><td>'.$val['result'].'</td></tr>';
					}
        ?>
         </table>
         <div class="clear"></div>
         <div class="agree">
         	<input type="hidden" name="step" value="2" />
         	<?php if($check){?>
         	<input hidefocus="true" type="submit" class="button" value="接受授权协议，开始安装" />
         	<?php }else{?>
         	<input hidefocus="true" type="button" disabled class="button" value="你的安装条件不符合规范" />
         	<?php }?>         	
         	<span>请先认真阅读我们的<a href="javascript:void(0);">《软件使用授权协议》</a></span>

         </div>
<?php }elseif($step == '2') {?>
<h3>设置数据库链接信息</h3>
         	<table class="data_set">
                  <tr><th colspan="3"></th></tr>
                  <tr>
                    <td width="14%">数据库地址</td>
                    <td width="37%"><input type="text" class="setup_input" name="dbhost" value="localhost" /></td>
                    <td width="49%" class="lightcolor">数据库服务器地址，一般为localhost </td>
              </tr>
                  <tr><th colspan="3"></th></tr>
                  <tr>
                    <td>数据库名称</td>
                    <td><input type="text" class="setup_input" name="dbname" value="iwebsns" /></td>
                    <td class="lightcolor"><input name="create" type="checkbox" id="create" value="1"/>&nbsp;&nbsp;如果果不存在，则自动被创建</td>
                  </tr>
                  <tr><th height="13" colspan="3"></th></tr>
                  <tr>
                    <td>数据库用户名</td>
                    <td><input type="text" class="setup_input" name="dbuser" value="root" /></td>
                    <td class="lightcolor">您的MySQL 用户名 </td>
                  </tr>
                  <tr><th colspan="3"></th></tr>
                  <tr>
                    <td>数据库密码</td>
                    <td><input type="password" class="setup_input" name="dbpw" value="" /></td>
                    <td class="lightcolor">您的MySQL密码</td>
                  </tr>
                  <tr><th colspan="3"></th></tr>
                  <tr>
                    <td>数据表前缀</td>
                    <td><input type="text" class="setup_input" name="tablepre" value="isns_" /></td>
                    <td class="lightcolor">同一数据库安装多个iWeb产品时可改变默认前缀 </td>
                  </tr>
                  <tr><th colspan="3"></th></tr>
                  <tr><th colspan="3"></th></tr>
      			</table>
    			 <h3>程序配置</h3>
                 <table class="data_set">
                 <tr><th colspan="3"></th></tr>
                      <tr>
                        <td width="14%">网站地址</td>
                      <td width="37%">
                        	<?php echo "http://{$_SERVER['HTTP_HOST']}/";?>
                       	<input name="isnsDIR" type="text" class="setup_input" value="<?php echo $isnsDIR;?>" style="width:110px;" /></td>
                        <td width="49%" class="lightcolor">一般不用修改，向导自动获取</td>
                   </tr>
                      <tr><th colspan="3"></th></tr>
                      <tr><th colspan="3"></th></tr>
				</table>
                <h3>设置管理员信息</h3>
                 <table class="data_set"> <tr>
                 <th colspan="2"></th></tr>
                      <tr>
                        <td width="14%">管理员账户</td>
                        <td width="86%"><input type="text" class="setup_input" name="admin" value="admin" /></td>
                   </tr>
                      <tr><th colspan="2"></th></tr>
                      <tr>
                        <td>管理员密码</td>
                        <td><input type="password" class="setup_input" name="password" value="" /></td>
                      </tr>
                      <tr><th colspan="2"></th></tr>
                      <tr><th colspan="2"></th></tr>
				</table>
                <!--<h3>选择默认模块&nbsp;<input type="checkbox" checked="checked" name="" /></h3>!-->
         <div class="agree">
         	<input type="hidden" name="step" value="3" />
         	<input hidefocus="true" type="submit" class="button" value="提交设置信息，开始创建数据库" />
         </div>
<?php }elseif($step == '3') {
	$creatable = 0;
	if(trim($_POST['dbname']) == "" || trim($_POST['dbhost']) == "" || trim($_POST['dbuser']) == "" ){
?>
    <p>请返回并确认所有选项均已填写.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
	} elseif(!@mysql_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpw'])) {
?>
    <p>数据库不能连接.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
	} elseif(!@mysql_select_db($_POST['dbname'])&&!isset($_POST['create'])) {

?>
    <p>数据库<?php echo $_POST['dbname'];?>不存在.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
	} elseif(strstr($_POST['tablepre'], '.')) {
?>
    <p>您指定的数据表前缀包含点字符，请返回修改.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
	} else {
		$creatable = 1;
		$configfile=ROOT_PATH.'iweb_mini_lib/conf/dbconf.php';
		if(is_writeable($configfile)) {
			$dbhost 	= trim($_POST['dbhost']);
			$dbuser 	= trim($_POST['dbuser']);
			$dbpw 		= trim($_POST['dbpw']);
			$dbname 	= trim($_POST['dbname']);
			$dbprefix	= trim($_POST['tablepre']);
			$isnsDIR	= trim($_POST['isnsDIR']);
			$admin		= trim($_POST['admin']);
			$password	= trim($_POST['password']);
			if(empty($password)){
				echo "<script> alert('管理员密码不能为空！');history.go(-1); </script>";
			}

			//配置文件静态化
			$config_re=fopen($configfile,"w+");
			$insert_config_data='
<?php
$host="'.$dbhost.':3306";//mysql数据库服务器,比如localhost:3306
$user="'.$dbuser.'"; //mysql数据库默认用户名
$pwd="'.$dbpw.'"; //mysql数据库默认密码
$db="'.$dbname.'"; //默认数据库名
global $tablePreStr;//设置外部变量
$tablePreStr="'.$dbprefix.'";//表前缀

//当前提供服务的mysql数据库
global $dbServs;
$dbServs=array($host,$db,$user,$pwd);
?>';
			fwrite($config_re,trim($insert_config_data));
			fclose($config_re);

		}
    	mysql_query("set names 'UTF8'");
		if(!@mysql_select_db($dbname)&& $_POST['create']){
    		$database=addslashes($dbname);
    		if(version_compare(mysql_get_server_info(), '4.1.0', '>=')){
		    	$DATABASESQL="DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
    		}
    		mysql_query("CREATE DATABASE `$database` ".$DATABASESQL);
    		@mysql_select_db($dbname);
		}
		require_once ($configfile);
		$installSQL=ROOT_PATH.'docs/iweb_sns.sql';
		!is_readable($installSQL)&&exit('数据库文件不存在或者读取失败');
		require_once(ROOT_PATH.'install/cdbex.class.php');
		$db = new dbex;
		$conf_content=file_get_contents("../configuration.php");
		$conf_content=preg_replace('/(\$siteDomain=\"http:\/\/{\$\_SERVER\[\'HTTP_HOST\']}\/)[^\;]*(";)/',"$1{$isnsDIR}$2",$conf_content);//网站域名
		$conf_rf=fopen("../configuration.php","w+");
		fwrite($conf_rf,$conf_content);
		fclose($conf_rf);
?>
    </p>
	<textarea name="notice" readonly="readonly" rows="10" cols="86" id="notice"></textarea>
	<div class="agree">
		<input type="hidden" name="step" value="4" />
		<input hidefocus="true" type="submit" id="createTables" class="button" style="color:#b6b6b6;cursor:wait;" disabled="disabled" value="正在创建数据库...请稍后" />
	</div>
<?php }}elseif($step == '4') {

	require("../configuration.php");
	require("../includes.php");
	require("../foundation/ftpl_compile.php");

?>
	<div class="tips">
		<p>
			<strong>
				<ul>
					<li>恭喜，iWebSNS安装程序已经顺利执行完毕！</li>
					<li>为了您的数据安全，请尽快删除整个 install 目录</li>
				</ul>
			</strong>
		</p>
	</div>
	<br />
	<div class="data_create">
	<?php
		list_child_file("default");
		file_put_contents(ROOT_PATH.'./docs/install.lock',"");
	?>
	</div>
	<div class="agree">
		<div class="btn"><div class="btn_right"><a hidefocus="true" href="../">进入首页</a></div></div>
	    <div class="btn"><div class="btn_right"><a hidefocus="true" href="../sysadmin/login.php">直接进入管理后台</a></div></div>
	</div>
<?php }?>
    </form>
    <div class="clear"></div>
    </div>
    <div class="bottom"></div>
</div>
<strong>Powered by iweb_sns V1.0 &copy; 2010 </strong>
<br /><br />
<?php
	if($step == '3' && $creatable)
	{
		runquery(openfile($installSQL),$tablePreStr);
?>
		<script type="text/javascript">showMsg('');showMsg('共创建了<?php echo $tablenum;?>个数据表.');</script>
		<script type="text/javascript">$('createTables').disabled = '';$('createTables').value = '完 成';$('createTables').style.color = '#4e4e4e';$('createTables').style.cursor = 'pointer';</script>
<?php
		$sql="INSERT INTO isns_admin(`admin_name`,`admin_password`,`admin_group`,`active_time`,`is_pass`) VALUES('$admin','".md5($password)."','superadmin',NOW(),1)";
		$sql=str_replace('isns_',$tablePreStr,$sql);
		if(!$db->query($sql)){
			echo '创建后台管理员失败！';
			exit;
		}
	}
?>
</body>
</html>
<?php
function runquery($sql,$tablePreStr) {
	global  $db, $tablenum;
	$sql = str_replace("\r", "\n", str_replace('isns_',$tablePreStr,$sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			if(!isset($ret[$num]))
			{
				$ret[$num] = substr($query,0,2) == '/*' ? '' : $query;
			}
			else
			{
				$ret[$num] .= substr($query,0,2) == '/*' ? '' : $query;
			}
		}
		$num++;
	}
	unset($sql);
	
	foreach($ret as $query) {

		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				preg_match("|CREATE TABLE (.*) \(  |i",$query, $name);
				flush();
				if($db->query($query)){
					create_table('创建表 ' . $name[1] . ' ... 成功！');
					$tablenum++;
				}else{
					create_table('创建表 ' . $name[1] . ' ... 失败！');
					exit;
				}
			}else if(substr($query, 0, 11) == 'INSERT INTO'){
				if($db->query($query)){
					create_table('插入数据...成功!');
				}else{
					create_table('插入数据...失败!');
					exit;
				}
			}else{
				$db->query($query);
			}
		}
	}
}
//模板编译
function list_child_file($local){
	$compile_type="serve";
	$ref=opendir("../templates/".$local);
	while($tp_dir=readdir($ref)){
		if(!preg_match("/^\./",$tp_dir)){
			if(filetype("../templates/".$local."/".$tp_dir)=="dir"){
				list_child_file($local."/".$tp_dir);
			}
			if(filetype("../templates/".$local."/".$tp_dir)=="file"){
				$loc='default';
				$show_local=$local.'/'.$tp_dir;
				$show_local=preg_replace("/$loc\//","",$show_local);
				tpl_engine($loc,$show_local,0,$compile_type);
			}
		}
	}
}
function create_table($table_info)
{
	echo '<script type="text/javascript">showMsg(\''.addslashes($table_info).' \');</script>'."\r\n";
	flush();
}
?>
