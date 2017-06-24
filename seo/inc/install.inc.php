<?php
    if(!is_dir(WEBROOT."/data")){
        require CMSPATH."/inc/config.tips.php";
    }else{
        $document = new Document(WEBROOT."/data");
        if (empty($document->Search("db")) || empty($tempList = $document->AllDir(TMPPATH))) {
            require CMSPATH."/inc/config.tips.php";
            exit;
        } 
        if (!isset($_POST["webTitle"])) {
            $keyList = $document->Search("txt");
            $dbList = $document->Search("db");
            $tempList = $document->AllDir(TMPPATH);
            require CMSPATH."/inc/config.init.php";
        }
        else{
            // 返回json类型
            header('Content-Type: application/json');
            //权限检查
            if (!is_readable(WEBROOT) || !is_writeable(WEBROOT)) {
                echo json_encode(["status"=>"error","msg"=>"请将网站目录（".WEBROOT."）设置为可读写"]);
                exit;
            }
            //标题
            if(empty($_POST["webTitle"])){
                echo json_encode(["status"=>"error","msg"=>"标题不能为空！"]);
                exit;
            }
            $post["webTitle"] = trim($_POST["webTitle"]);
            //关键词
            $post["indexKeyword"] = empty(trim($_POST["indexKeyword"])) ? "false" : "'".trim($_POST["indexKeyword"])."'"; 
            //描述
            $post["indexDescription"] = empty(trim($_POST["indexDescription"])) ? "false" : "'".trim($_POST["indexDescription"])."'";
            //每页文章数
            $post["postsNum"] = empty(trim($_POST["postsNum"])) ? 20 : (int)trim($_POST["postsNum"]);
            //模板
            if(empty($_POST["tempName"])){
                echo json_encode(["status"=>"error","msg"=>"必须选择模板！"]);
                exit;
            }
            $post["tempName"] = "'".trim($_POST["tempName"])."'";
            //附加关键词
            $post["keywordFileSwitch"] = isset($_POST["keywordFileSwitch"])?  "true" : "false";
            $post["keywordFilesName"] = "false" ;
            if ($post["keywordFileSwitch"] && !empty($_POST["keywordFilesName"])) {
                $post["keywordFilesName"] = "" ;                
                foreach (explode(" ",trim($_POST["keywordFilesName"])) as  $value) {
                    if (is_file(WEBROOT."/data/".trim($value))) {
                        $post["keywordFilesName"] = $post["keywordFilesName"].'"'.trim($value).'",';
                    }else{
                        echo json_encode(["status"=>"error","msg"=>"文件".trim($value)."不存在，请检查后再试"]);
                        exit;
                    }
                }
                $post["keywordFilesName"] =  "[".rtrim($post["keywordFilesName"],",")."]";
            }
            //url中添加title
            $post["urlTitle"] = isset($_POST["urlTitle"])?  "true" : "false";
            //栏目标题
            if(empty($_POST["cateTitle"])){
                echo json_encode(["status"=>"error","msg"=>"栏目标题不能为空！"]);
                exit;
            }
            $post["cateTitle"] = "";
            foreach ($_POST["cateTitle"] as $v) {
                $post["cateTitle"] = $post["cateTitle"].'"'.$v.'",';
            }
            $post["cateTitle"] =  "[".rtrim($post["cateTitle"],",")."]";
            //开始创建配置文件
            $conffile=file_get_contents(CMSPATH."/inc/config.simple.php");
			$pattern=array(
                "/(Modified time:)[^\n]+/i",
                "/([\"|\']webTitle[\"|\'])([^,]+),/i",
                "/([\"|\']cateTitle[\"|\'])([^,]+),/i",
                "/([\"|\']indexKeyword[\"|\'])([^,]+),/i",
                "/([\"|\']indexDescription[\"|\'])([^,]+),/i",
                "/([\"|\']postsNum[\"|\'])([^,]+),/i",
                "/([\"|\']tempName[\"|\'])([^,]+),/i",
                "/([\"|\']urlTitle[\"|\'])([^,]+),/i",
                "/([\"|\']keywordFileSwitch[\"|\'])([^,]+),/i",
                "/([\"|\']keywordFilesName[\"|\'])([^,]+),/i"
            );
			$replace=array(
                "$1 ".date("Y-m-d H:i:s",time()),
                "$1 => '".$post["webTitle"]."',",
                "$1 => ".$post["cateTitle"].",",
                "$1 => ".$post["indexKeyword"].",",
                "$1 => ".$post["indexDescription"].",",
                "$1 => ".$post["postsNum"].",",
                "$1 => ".$post["tempName"].",",
                "$1 => ".$post["urlTitle"].",",
                "$1 => ".$post["keywordFileSwitch"].",",
                "$1 => ".$post["keywordFilesName"].",",
            );
			$conffile=preg_replace($pattern,$replace,$conffile);
            //写入配置文件
            file_put_contents(WEBROOT."/config.php",$conffile);
            echo json_encode(["status"=>"success","msg"=>"初始化成功"]);
        }
    }