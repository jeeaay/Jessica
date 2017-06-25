<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 17:31:26 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-26 00:03:21
 */
define("WEBROOT",$_SERVER['DOCUMENT_ROOT']);
define("CMSPATH",dirname(__FILE__));
define("TMPPATH",dirname(__FILE__)."/../templates");
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
    //实例化通用方法类
    $common = new Common($config);
    //路由开始
    $router = new Router($config);
    if ($router->Classify()=="404") {
        $common->NotFound();
    }
    //展示内容
    $result = new Result($config,$router->Classify());
    //渲染开始
    $result->Render();
    //var_dump($result->Render()) ;
}

