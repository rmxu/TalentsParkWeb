<?php
//require("foundation/arecent_affair.php");
require("foundation/aanti_refresh.php");
require("foundation/aintegral.php");
//引入语言包 此实例暂不加入
//变量取得
$hello_id=short_check(get_args("hello_id"));
//数据表定义区
$t_blog=$tablePreStr."helloword";
$dbo = new dbex;
//读写分离定义函数
dbtarget('w',$dbServs);
$sql="delete from $t_blog where id= $hello_id ";
if($dbo->exeUpdate($sql)){
action_return(1,"删除测试成功！",'');
}
//回应信息
action_return(0,"删除测试失败测！",'');
?>