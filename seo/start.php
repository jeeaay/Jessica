<?php
define("WEBROOT",$_SERVER['DOCUMENT_ROOT']);
define("CMSPATH",dirname(__FILE__));
// 自动加载
header ( "Content-Type: text/html; charset=utf-8" );
function __autoload($className) {
	include CMSPATH."/lib/" . strtolower ( $className ) . ".class.php";
}
if (!file_exists(WEBROOT."/config.php")) {
    if(!is_dir(WEBROOT."/data")){
        require "config.tips.php";
    }else{
        $document = new Document(WEBROOT."/data");
        if (empty($document->Search("db"))) {
            require "config.tips.php";
            exit;
        } 
        if (!isset($_POST["webTitle"])) {
            $keyList = $document->Search("txt");
            $dbList = $document->Search("db");
            require "config.init.php";
        }
        else{
            //权限检查
            if (!is_readable(WEBROOT) || !is_writeable(WEBROOT)) {
                echo json_encode(["status"=>"error","msg"=>"请将网站目录（".WEBROOT."）设置为可读写"]);
                exit;
            }
            //标题
            if(empty($_POST["webTitle"])){
                // 返回json类型
                header('Content-Type: application/json');
                echo json_encode(["status"=>"error","msg"=>"标题不能为空！"]);
                exit;
            }
            $post["webTitle"] = trim($_POST["webTitle"]);
            //关键词
            if(empty(trim($_POST["indexKeyword"]))){
                $post["indexKeyword"] = "false";
            }else{
                $post["indexKeyword"] = "'".trim($_POST["indexKeyword"])."'";
            }
            //描述
            if(empty(trim($_POST["indexDescription"]))){
                $post["indexDescription"] = "false";
            }else{
                $post["indexDescription"] = "'".trim($_POST["indexDescription"])."'";                
            }
            //附加关键词
            $post["keywordFileSwitch"] = isset($_POST["keywordFileSwitch"])?  "true" : "false";
            if ($post["keywordFileSwitch"] && !empty($_POST["keywordFilesName"])) {
                foreach (explode(" ",trim($_POST["keywordFilesName"])) as  $value) {
                    if (is_file(WEBROOT."/data/".trim($value))) {
                        $post["keywordFilesName"][] = trim($value);
                    }else{
                        header('Content-Type: application/json');
                        echo json_encode(["status"=>"error","msg"=>"文件".trim($value)."不存在，请检查后再试"]);
                        exit;
                    }
                }
            }
            //url中添加title
            $post["urlTitle"] = isset($_POST["urlTitle"])?  "true" : "false";
            //栏目标题
            if(empty($_POST["cateTitle"])){
                // 返回json类型
                header('Content-Type: application/json');
                echo json_encode(["status"=>"error","msg"=>"栏目标题不能为空！"]);
                exit;
            }
            foreach ($_POST["cateTitle"] as $value) {
                $post["cateTitle"][] = trim($value);
            }
            //开始创建配置文件
            $conffile=file_get_contents(CMSPATH."/config.simple.php");
			$pattern=array(
                "/(Modified time:)[^\n]+/i",
                "/([\"|\']webTitle[\"|\'])([^,]+),/i",
                "/([\"|\']cateTitle[\"|\'])([^,]+),/i",
                "/([\"|\']indexKeyword[\"|\'])([^,]+),/i",
                "/([\"|\']indexDescription[\"|\'])([^,]+),/i",
                "/([\"|\']urlTitle[\"|\'])([^,]+),/i",
                "/([\"|\']keywordFileSwitch[\"|\'])([^,]+),/i",
                "/([\"|\']keywordFilesName[\"|\'])([^,]+),/i"
            );
            $cateTitle = "";
            foreach($post["cateTitle"] as $v){
                $cateTitle = $cateTitle.'"'.$v.'",';
            }
            $cateTitle =  "[".rtrim($cateTitle,",")."]";
            $keywordFilesName = "";
            foreach($post["keywordFilesName"] as $v){
                $keywordFilesName = $keywordFilesName.'"'.$v.'",';
            }
            $keywordFilesName =  "[".rtrim($keywordFilesName,",")."]";
			$replace=array(
                "$1 ".date("Y-m-d H:i:s",time()),
                "$1 => '".$post["webTitle"]."',",
                "$1 => ".$cateTitle.",",
                "$1 => ".$post["indexKeyword"].",",
                "$1 => ".$post["indexDescription"].",",
                "$1 => ".$post["urlTitle"].",",
                "$1 => ".$post["keywordFileSwitch"].",",
                "$1 => ".$keywordFilesName.",",
            );
			$conffile=preg_replace($pattern,$replace,$conffile);
            //写入配置文件
            file_put_contents(WEBROOT."/config.php",$conffile);
            header('Content-Type: application/json');
            echo json_encode(["status"=>"success","msg"=>"初始化成功"]);
        }
    }
}
//;
