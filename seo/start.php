<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 17:31:26 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 11:33:21
 */
define("WEBROOT",$_SERVER['DOCUMENT_ROOT']);
define("CMSPATH",dirname(__FILE__));
// 自动加载
header ( "Content-Type: text/html; charset=utf-8" );
function __autoload($className) {
	include CMSPATH."/lib/" . strtolower ( $className ) . ".class.php";
}
if (!file_exists(WEBROOT."/config.php")) {
    //进入安装页面
    include CMSPATH."/inc/install.inc.php";
}else{
    //加载配置文件
    include WEBROOT."/config.php";
    //路由开始
    $router = new Router($config);
    var_dump($router->Classify()) ;
}

