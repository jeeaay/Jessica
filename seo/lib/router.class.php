<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 11:30:13 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 14:53:30
 * 路由类
 */
class Router{
    //请求的uri
    private $uri;
    private $config;
    function __construct(array $config = [])
    {
        $this->config = $config;
        $this->uri = $_SERVER['REQUEST_URI'];
    }
    //请求uri分类
    public function Classify()
    {
        // 首页
        if ($this->uri==""||$this->uri=="/") {
			return ["type" => "index"];
		}
        // 目录
        if( preg_match("/^\/([^\/]+)\/$/i",$this->uri, $matche) ){
            
            return ["type" => "category", "cate" => $matche[1], "page" => 0];
        }
        // 分页
        if( preg_match("/^\/([^\/]+)\/list-(\d+)\.html/i",$this->uri, $matche) ){
            return ["type" => "category", "cate" => $matche[1], "page" => $matche[2]];
        }
        // 内页
        if ($this->config["urlTitle"]) {
            if ( preg_match("/^\/([^\/]+)\/(\d+)\-(.*)\.html$/i",$this->uri, $matche) ) {
                return ["type" => "single", "cate" => $matche[1], "id" => $matche[2], "urlTitle" => urldecode(str_replace("-"," ",$matche[3]))];
            }
        }else{
            if(!$this->config["urlTitle"] && preg_match("/^\/([^\/]+)\/(\d+)\.html$/i",$this->uri, $matche)){
        
                return ["type" => "single", "cate" => $matche[1], "id" => $matche[2]];
            }
        }
        return "404";
    }
}