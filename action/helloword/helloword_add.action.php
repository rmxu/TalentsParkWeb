<?php
//require("foundation/arecent_affair.php");
require("foundation/aanti_refresh.php");
require("foundation/aintegral.php");
//引入语言包 此实例暂不加入
//变量取得
//echo "add called";
$hello_msg=short_check(get_args("hello_msg"));
//防止重复提交
$t_blog=$tablePreStr."helloword";
$dbo = new dbex;
//读写分离定义函数
dbtarget('w',$dbServs);
$sql="insert into $t_blog (msg)"
." values ('".$hello_msg."')";
if($dbo->exeUpdate($sql)){
action_return(1,"添加测试成功！",'');
}
//回应信息
action_return(0,"添加测试失败测！",'');
?>