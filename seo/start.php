<?php
define("WEBROOT",$_SERVER['DOCUMENT_ROOT']);
define("CMSPATH",dirname(__FILE__));
// 自动加载
header ( "Content-Type: text/html; charset=utf-8" );
function __autoload($className) {
	include CMSPATH."/lib/" . strtolower ( $className ) . ".class.php";
}
if (!file_exists(WEBROOT."/config.php")) {
    include CMSPATH."/install.inc.php";
}
//;
