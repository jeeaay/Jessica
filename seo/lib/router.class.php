<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 11:30:13 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 11:21:20
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
        // sitemap
        if($this->uri=="/sitemap.xml"){
            return ["type" => "sitemap"];
        }
        // 目录
        if( preg_match("/^\/([^\/]+)\/$/i",$this->uri, $matche) ){
            
            return ["type" => "category", "cate" => $matche[1]];
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