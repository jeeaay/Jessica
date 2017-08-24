<?php
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
    $filePath = WEBROOT."/sitemap/sitemapindex-".$config["sitemapPassword"]."-".str_replace(" ","-",$_POST["dbName"]).".xml";
    if (!file_put_contents($filePath,$sitemapIndex)) {
        echo json_encode(["err"=>"写入失败，请检查".WEBROOT."/sitemap目录是否有写入权限"]);
    }  
    echo json_encode(["err"=>NULL,"totalPage"=>$totalPage,"dbName"=>$_POST["dbName"]]);
    exit;
}
$allDbs = json_encode(array_keys($config["cateTitle"])) ;
require CMSPATH."/inc/sitemap.html";
