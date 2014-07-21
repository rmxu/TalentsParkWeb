<?php
//时间类库与mysql兼容,格式:YYYY-mm-dd HH:ii:ss
class time_class{
public $format;//时间格式
//构造函数
function __construct($format="Y-m-d H:i:s")
{$this->format=$format;}
//参数定制的时间
function custom()
{return date($this->format,time()+8*60*60);}
//完整时间
function long_time()
{return date("Y-m-d H:i:s",time()+8*60*60);}
//短时间
function short_time()
{return date("Y-m-d",time()+8*60*60);}
//时间戳
function time_stamp()
{return time()+8*60*60;}

};
?>