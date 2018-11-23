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
        $url = $site_url."/sitemap/sitemapindex-".$config["sitemapPassword"]."-".urlencode(str_replace(" ","-",$_POST["dbName"])).".xml";
        if ($_POST["google"]) {
            $res .= " google: ";
            if( !empty(file_get_contents("https://www.google.com/webmasters/sitemaps/ping?sitemap=".$url)) ){
                $res .= "success";
                $is_ping = true;
            }else{
                $res .= "failed";
            }
        }
        if ($_POST["bing"]) {
            $res .= " bing: ";
            if( !empty(file_get_contents("https://www.bing.com/webmaster/ping.aspx?siteMap=".$url)) ){
                $res .= "success";
                $is_ping = true;
            }else{
                $res .= "failed";
            }
        }
    }else{       
    //获取文章的URL
        include CMSPATH."/lib/sqlite.class.php";
        $db = new SQLite($dbPath);
        $sql = "select * from Content where pub_time < ".time()." and is_ping <> 1 order by random() limit 1";
        if( !($post = $db->getlist($sql)[0] )){
            echo json_encode(["err"=>"所有文章都已经ping过了"]);
            exit;
        }
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
        $url = $site_url.$url;
    }
    $ping = new Ping($config["webTitle"],$site_url,$url);
    $is_ping = false;
    if ($_POST["google"] && !$_POST["sitemapIndex"]) {
        $res .= " google: ";
        if($ping->google()){
            $res .= "success";
            $is_ping = true;
        }else{
            $res .= "failed";
        }
    }
    
    if ($_POST["baidu"]) {
        $res .= " baidu: ";
        if($ping->baidu()){
            $res .= "success";
            $is_ping = true;
        }else{
            $res .= "failed";
        }
    }
    
    if($is_ping && !$_POST["sitemapIndex"]){
        $sql = "update Content set is_ping = 1 where ID = ".$post["ID"];
        $db->query($sql);
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