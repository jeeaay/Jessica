<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-29 16:05:25 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-07-05 16:59:29
 */
if(isset($_POST["dbName"])){
    header('Content-Type: application/json');
    $dbPath = WEBROOT."/data/".$_POST["dbName"].".db";
    if(!file_exists($dbPath)){
        echo json_encode(["err"=>$dbPath."不存在","post"=>$_POST]);
        exit;
    }
    $url = "";
    $res = "";
    $site_url = 'http://'.$_SERVER['HTTP_HOST'];
    //获取sitemapindex的URL
    if($_POST["sitemapIndex"]){
        $url = "/sitemap/sitemapindex-".$config["sitemapPassword"]."-".urlencode(str_replace(" ","-",$_POST["dbName"])).".xml";
    }else{       
    //获取文章的URL
        include CMSPATH."/lib/sqlite.class.php";
        $db = new SQLite($dbPath);
        $sql = "select * from Content where pub_time < ".time()." and is_ping <> 1 order by random() limit 1";
        $post = $db->getlist($sql)[0];
        $title =$post["title"];
        if($config["keywordFileSwitch"]){
            $title .= " ".$post["title2"];
        }
        $url = "/".urlencode(str_replace(" ","-",$_POST["dbName"]))."/".$post["ID"];
        if($config["urlTitle"]){
            $url .= "-".urlencode(str_replace(" ","-",trim($title)));
        }
        $url .=".html";
        $res = 'ID: <a target="_blank" href="'.$url.'" title="'.$url.'">'.$post["ID"].'</a>';
    }
    $url = $site_url.$url;
    $ping = new Ping($config["webTitle"],$site_url,$url);
    if ($_POST["google"]) {
        $res .= " google: ";
        $res .= $ping->google() ? "success" : "failed";
    }
    if ($_POST["baidu"]) {
        $res .= " baidu: ";
        $res .= $ping->baidu() ? "success" : "failed";
    }
    if ($_POST["yandex"]) {
        $res .= " yandex: ";
        $res .= $ping->yandex() ? "success" : "failed";
    }
    echo json_encode(["err"=>NULL,"res"=>$res]);
}else{
    $document = new Document(WEBROOT."/data");
    $arr = $document->Search("db");
    $dbList = [];
    foreach($arr as $v){
        $dbList[] = str_replace(".db" , "" , $v);
    }
    require CMSPATH."/inc/ping.html";
}