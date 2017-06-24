<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 13:53:44
 */
class Common 
{
    private $config;
    function __construct(Array $config = [])
    {
        $this->config = $config;
    }
    //显示404错误，优先使用模板中的404页面
    public function NotFound(String $msg = "Page Not Found")
    {
        header("HTTP/1.1 404 Not Found");
        if (file_exists(TMPPATH."/".$this->config["tempName"]."/404.html")) {
            include TMPPATH."/".$this->config["tempName"]."/404.html";
        }else{
            include CMSPATH."/inc/404.php";
        }
        exit;
    }
}
