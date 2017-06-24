<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 15:56:53
 */
class Result extends SQLite
{
    private $config;
    private $type;
    function __construct(Array $config = [], Array $type = [])
    {
        $this->config = $config;
        $this->type = $type;
    }
    private function cateList(String $dbName = "", Int $page = 0, Int $postsNum = 20)
    {
        if (is_numeric($this->config["postsNum"])) {
            $postsNum = $this->config["postsNum"];
        }
        if ($dbName=="" || !is_file($dbName)) {
            return 404;
        }
        try{
            $this->connection=new PDO('sqlite:'.$dbName);
        }catch(PDOException $e){
            exit ("数据库错误！".$e);
        }
        $sql = 'select * from Content order by id desc limit '.$postsNum.' offset '.$page*$postsNum;
        return $this->getlist($sql);
    }
    public function GetContent()
    {
        switch ($this->type["type"]) {
            case 'index':
                return $this->GetIndex();
                break;
            
            case 'sitemap':
                return $this->GetSitemap();
                break;
            
            case 'category':
                return $this->GetCategory();
                break;
            
            case 'single':
                return $this->GetSingle();
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
        $dbName = WEBROOT."/data/".str_replace("-"," ",urldecode($this->type["cate"])).".db";
        if($list = $this->cateList($dbName,$this->type["page"]) == "404"){
            return 404;
        }else{
            require TMPPATH."/".$this->config["tempName"]."/category.html";
        }
        exit;
    }
}
