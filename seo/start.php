<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 17:31:26 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-26 01:01:47
 */
define("WEBROOT",$_SERVER['DOCUMENT_ROOT']);
define("CMSPATH",dirname(__FILE__));
define("TMPPATH",dirname(__FILE__)."/../templates");
// 自动加载
header ( "Content-Type: text/html; charset=utf-8" );
function __autoload($className) {
	include CMSPATH."/lib/" . strtolower ( $className ) . ".class.php";
}

if (file_exists(WEBROOT."/config.php")) {
    //加载配置文件
    include WEBROOT."/config.php";
    //生成sitemap
    if (isset($_GET[$config["sitemapPassword"]])) {
        require CMSPATH."/inc/sitemap.inc.php";
    }else{
        //路由开始
        $router = new Router($config);
        if ( ($classify = $router->Classify())=="404") {
            Common::NotFound();
        }
        //展示内容
        $result = new Result($config,$classify);
        //渲染开始
        switch ($classify["type"]) {
            case 'index':
                require TMPPATH."/".$config["tempName"]."/index.html";
                break;
            
            case 'category':
                //判断分页是否存在
                if ($result->PagerInfo()["totalPages"]<$classify["page"]) {
                    Common::NotFound();
                }
                
                $title = $config["cateTitle"][$result->dbName];
                require TMPPATH."/".$config["tempName"]."/category.html";
                break;
            
            case 'single':
                $single = $result->GetSingle()[0];
                $content = Common::ResFilter($single["content"]);
                $title = $single["title"];
                if ($config["keywordFileSwitch"]) {
                    $title .=  $result->getSubtitle($single["ID"]) ;
                }
                //$pubTime = $single["pub_time"];
                require TMPPATH."/".$config["tempName"]."/single.html";
                break;
            
            default:
                Common::NotFound();
                break;
        }
    }
    
}else{
    //进入安装页面
    include CMSPATH."/inc/install.inc.php";
}

