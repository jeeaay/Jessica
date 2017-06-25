<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-25 23:48:28 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-25 23:49:32
 */
 /*
 */
 function NotFound(String $msg = "Page Not Found")
    {
        header("HTTP/1.1 404 Not Found");
        if (file_exists(TMPPATH."/".$this->config["tempName"]."/404.html")) {
            include TMPPATH."/".$this->config["tempName"]."/404.html";
        }else{
            include CMSPATH."/inc/404.php";
        }
        exit;
    }