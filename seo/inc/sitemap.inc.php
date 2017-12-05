<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-28 8:05:46 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-12-05 16:34:03
 */
if (isset($_POST["postsCount"])) {
    header('Content-Type: application/json');
    //检查是否可读写
    if (!is_readable(WEBROOT) || !is_writeable(WEBROOT)) {
        echo json_encode(["err"=>"请将网站目录（".WEBROOT."/data）设置为可读写"]);
        exit;
    }
    if (!is_dir("sitemap")) {
        mkdir("sitemap");
    }
    //每个sitemap数量不能少于100
    if ($_POST["postsCount"]<100) {
        echo json_encode(["err"=>"每个sitemap文章数量太少"]);
        exit;
    }
    //分析sitemap数量
    $dbPath = WEBROOT."/data/".$_POST["dbName"].".db";
    if (!file_exists($dbPath)) {
        Common::NotFound();
    }
    $cateUrl = urlencode(str_replace(" ","-",$_POST["dbName"]));
    $db = new SQLite($dbPath);
    //生成sitemap
    if(isset($_POST["page"])){
        $sql="select ID,title,title2 from Content where pub_time < ".time()." limit ".$_POST["postsCount"]." offset ".$_POST["postsCount"]*$_POST["page"];
        $postList = $db->getlist($sql);
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        if ($config["urlTitle"]) {
            foreach($postList as $v){
                $sitemap.="<url><loc>http://".$_SERVER ['HTTP_HOST']."/{$cateUrl}/{$v["ID"]}-".urlencode(str_replace(" ","-",$v["title"]));
            	if($config["keywordFileSwitch"]){
                    $sitemap.="-".urlencode(str_replace(" ","-",$v["title2"]));
                }
                $sitemap.=".html</loc></url>\n";
            }
        }else{
            foreach($postList as $v){
                $sitemap.="<url><loc>http://".$_SERVER ['HTTP_HOST']."/{$cateUrl}/{$v["ID"]}.html</loc></url>\n";
            }
        }
        $sitemap.= "</urlset>";
        $num = $_POST["page"]+1;
        $filePath = WEBROOT."/sitemap/sitemap-".$config["sitemapPassword"]."-{$cateUrl}".$num.".xml";
        if (!file_put_contents($filePath,$sitemap)) {
            echo json_encode(["err"=>"写入失败，请检查".WEBROOT."/sitemap目录是否有写入权限"]);
        }  
        echo json_encode(["err"=>NULL,"page"=>$_POST["page"]+1,"list"=>$filePath]);
        exit;
    }
    //生成sitemapIndex
    $sql="select ID from Content where pub_time < ".time();
	$totalPost=$db->RecordCount($sql);//总文章数
	$totalPage= ceil($totalPost/$_POST["postsCount"]);//总sitemap数
    //生成栏目的sitemapindex
    $sitemapIndex = "";
    for ($i=0; $i < $totalPage; $i++) { 
        $num = $i+1;
        $sitemapIndex .= "<sitemap><loc>http://".$_SERVER ['HTTP_HOST']."/sitemap/sitemap-".$config["sitemapPassword"]."-{$cateUrl}".$num.".xml</loc></sitemap>\n";
    }
    $sitemapIndex = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n".$sitemapIndex."</sitemapindex>";
    $filePath = WEBROOT."/sitemap/sitemapindex-".$config["sitemapPassword"]."-".urlencode(str_replace(" ","-",$_POST["dbName"])).".xml";
    if (!file_put_contents($filePath,$sitemapIndex)) {
        echo json_encode(["err"=>"写入失败，请检查".WEBROOT."/sitemap目录是否有写入权限"]);
    }  
    echo json_encode(["err"=>NULL,"totalPage"=>$totalPage,"dbName"=>$_POST["dbName"]]);
    exit;
}
//生成sitemap.xml
if (isset($_POST["sitemap"])){
    header('Content-Type: application/json');
    //列出现有的sitemap
    include_once(dirname(__FILE__)."/../lib/document.class.php");
    $sitemapPath = $_SERVER['DOCUMENT_ROOT']."/sitemap";
    $doucment = new Document($sitemapPath);
    //筛选出各栏目sitemap首页
    $sitemapIndexes = array();
    foreach ($doucment->AllFiles() as $value) {
        if(strstr($value,"sitemapindex")){
            $sitemapIndexes[] = $value;
        }
    }
    //拼接成sitemap xml 格式
    $sitemapIndex = "";
    foreach ($sitemapIndexes as $value) {
        $sitemapIndex .= "<sitemap><loc>http://".$_SERVER ['HTTP_HOST']."/sitemap/".$value."</loc></sitemap>\n";
    }
    $sitemapIndex = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n".$sitemapIndex."</sitemapindex>";
    if (!file_put_contents($sitemapPath."/sitemap.xml",$sitemapIndex)) {
        echo json_encode(["err"=>"写入失败，请检查".WEBROOT."/sitemap目录是否有写入权限"]);
    }  
    echo json_encode(["err"=>NULL]);
    exit;
}
$allDbs = json_encode(array_keys($config["cateTitle"])) ;
require CMSPATH."/inc/sitemap.html";
