<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-25 23:48:28 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-26 00:29:51
 */
 /*
 */
 function NotFound(String $msg = "Page Not Found")
    {
        Global $config;
        header("HTTP/1.1 404 Not Found");
        if (file_exists(TMPPATH."/".$config["tempName"]."/404.html")) {
            include TMPPATH."/".$config["tempName"]."/404.html";
        }else{
            include CMSPATH."/inc/404.php";
        }
        exit;
    }