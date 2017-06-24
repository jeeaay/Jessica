<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 12:56:38
 */
function NotFound(String $msg = "Page Not Found")
{
    header("HTTP/1.1 404 Not Found");
    if (file_exists(WEBROOT."/404.html")) {
        include TMPPATH."/".$config["tempName"]."/404.html";
    }else{
        include CMSPATH."/inc/404.php";
    }
    exit;
}