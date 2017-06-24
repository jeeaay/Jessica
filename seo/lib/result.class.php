<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 14:51:20
 */
class Result extends Temp
{
    private $config;
    private $type;
    function __construct(Array $config = [], Array $type = [])
    {
        $this->config = $config;
        $this->type = $type;
    }
    public function GetContent()
    {
        switch ($this->type["type"]) {
            case 'index':
                $this->GetIndex();
                break;
            
            case 'sitemap':
                $this->GetSitemap();
                break;
            
            case 'category':
                $this->GetCategory();
                break;
            
            case 'single':
                $this->GetSingle();
                break;
            
            default:
                return "404";
                break;
        }
    }
    private function GetIndex()
    {
        $cont = [];
        foreach ($this->config["cateTitle"] as $key => $value) {
            $cont[$key]["url"] = "/".str_replace(" ","-",$value)."/";
            $cont[$key]["cateTitle"] = $value;
        }
        require TMPPATH."/".$this->config["tempName"]."/index.html";
    }
    private function GetCategory()
    {
        $cont = [];
        foreach ($this->config["cateTitle"] as $key => $value) {
            $cont[$key]["url"] = "/".str_replace(" ","-",$value)."/";
            $cont[$key]["cateTitle"] = $value;
        }
        require TMPPATH."/".$this->config["tempName"]."/index.html";
    }
}
