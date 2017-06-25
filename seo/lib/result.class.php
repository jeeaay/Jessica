<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-26 01:00:16
 */
class Result extends SQLite
{
    private $config;
    private $type;
    // 当前分类使用的数据库名
    private $dbName;
    // 当前分类使用的数据库完整路径
    private $dbPath;

    function __construct(Array $config = [], Array $type = [])
    {
        $this->config = $config;
        $this->type = $type;

        if ($this->type["type"] == 'category' || $this->type["type"] == 'single') {
            $this->dbName = str_replace("-"," ",urldecode($this->type["cate"]));
            $this->dbPath = WEBROOT."/data/".$cateDB.".db";
            if ( !is_file($dbName) ) {
                NotFount("This Category does not exist");
            }
            parent::__construct($dbName);
        }
    }
    private function Render()
    {
        switch ($this->type["type"]) {
            case 'index':
                require TMPPATH."/".$this->config["tempName"]."/index.html";
                break;
            
            case 'sitemap':
                $this->GetSitemap();
                break;
            
            case 'category':
                $title = $this->config["cateTitle"][$this->dbName];
                require TMPPATH."/".$this->config["tempName"]."/category.html";
                break;
            
            case 'single':
                $title = $this->config["cateTitle"][$this->dbName];
                require TMPPATH."/".$this->config["tempName"]."/single.html";
                break;
            
            default:
                return "404";
                break;
        }
    }
    // 获取所有的栏目名及url
    private function GetAllCate()
    {
        $allList = [];
        foreach ($this->config["cateTitle"] as $key => $value) {
            $cont[$key]["url"] = "/".str_replace(" ","-",$value)."/";
            $cont[$key]["cateTitle"] = $value;
        }
        return $allList;
    }
    // 获取栏目列表
    private function CateList()
    {
        $postsNum = is_numeric($this->config["postsNum"]) ? $this->config["postsNum"] : 20;
        $page =  is_numeric($this->type["page"]) ? $this->type["page"] : 0;
        if (is_numeric($this->config["postsNum"])) {
            $postsNum = $this->config["postsNum"];
        }
        $sql = 'select * from Content order by id desc limit '.$postsNum.' offset '.$page*$postsNum;
        if ($list = $this->getlist($sql)) {
            return $list;
        }else{
            NotFount("This Category does not exist");
        }
    }
    // 栏目分页
    private function GetPages()
    {
        $postsNum = is_numeric($this->config["postsNum"]) ? $this->config["postsNum"] : 20;
        $page =  is_numeric($this->type["page"]) ? $this->type["page"] : 0;
        //计算分页数量
        $sql="select ID from Content";
        $totalPosts=$this->RecordCount($sql);//总文章数
        $totalPage= ceil($totalPosts/$postsNum);//总分页数
        if ($page>$totalPage) NotFount("No More Pages");
        //分页
        $pagesHTML = "";
        for ($i=1; $i <=$totalPage ; $i++) { 
            if ($i==$page) {
                $pagesHTML.="<li>$i</li>";
                continue;
            }
            if ($i==1) {
                $pagesHTML.="<li><a href='.'>1</a></li>";
                continue;
            }
            $pagesHTML.="<li><a href='list-{$i}.html'>{$i}</a></li>";
        }
        return $pagesHTML;
    }
    // 获取内页
    private function GetSingle()
    {
        $sql = 'select * from Content where ID = '.$this->type["id"];
        if ($list = $this->getlist($sql)) {
            return $list;
        }else{
            NotFount("This Category does not exist");
        }
    }
    // 获取随机文章
    private function GetRandPosts( $count = 20 , $cate = )
    {

    }
}
