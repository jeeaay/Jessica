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
    public $dbName;
    // 当前分类使用的数据库完整路径
    private $dbPath;

    function __construct(Array $config = [], Array $type = [])
    {
        $this->config = $config;
        $this->type = $type;
        if ($this->type["type"] == 'category' || $this->type["type"] == 'single') {
            $this->dbName = str_replace("-"," ",urldecode($this->type["cate"]));
            $this->dbPath = WEBROOT."/data/".$this->dbName.".db";
            if ( !is_file($this->dbPath) ) {
                Common::NotFound();
            }
            parent::__construct($this->dbPath);
        }
    }
    // 获取所有的栏目名及url
    public function GetAllCate()
    {
        $allList = [];
        foreach ($this->config["cateTitle"] as $key => $value) {
            $allList[$key]["url"] = "/".str_replace(" ","-",$key)."/";
            $allList[$key]["cateTitle"] = $value;
        }
        return $allList;
    }
    // 获取栏目列表
    public function CateList()
    {
        $postsNum = is_numeric($this->config["postsNum"]) ? $this->config["postsNum"] : 20;
        $page =  is_numeric($this->type["page"]) ? $this->type["page"]-1 : 0;
        if (is_numeric($this->config["postsNum"])) {
            $postsNum = $this->config["postsNum"];
        }
        $sql = 'select * from Content where pub_time < '.time().' order by id desc  limit '.$postsNum.' offset '.$page*$postsNum;
        if ($list = $this->getlist($sql)) {
            return $list;
        }else{
            Common::NotFound();
        }
    }
    public function PagerInfo()
    {
        //每页文章数
        $everyPage = is_numeric($this->config["postsNum"]) ? $this->config["postsNum"] : 20;
        //当前页数
        $currentPage =  is_numeric($this->type["page"]) ? $this->type["page"] : 0;
        //计算分页数量
        $sql="select ID from Content where pub_time < ".time();
        //总文章数
        $totalPosts=$this->RecordCount($sql);
        //总分页数
        $totalPages= ceil($totalPosts/$everyPage);
        return ["everyPage"=>$everyPage,"currentPage"=>$currentPage,"totalPosts"=>$totalPosts,"totalPages"=>$totalPages];
    }
    // 栏目分页
    public function GetPages()
    {
        $info = $this->PagerInfo();
        $pageBefore = ceil($this->config["pagerCount"]/2)-1;
        $pageAfter = floor($this->config["pagerCount"]/2);
        //当前页
        if ($info["currentPage"] > 1) {
            $pages[$info["currentPage"]] = "";
        }
        //当前页前面的分页
        for ($i=0; $i <= $pageBefore; $i++) { 
            $j = $info["currentPage"] - $i;
            if($j<1){
                $j = $j+$this->config["pagerCount"];
            }
            if ($j > $info["totalPages"]) {
                continue;
            }
            if ($j == $info["currentPage"]){
                continue;
            }
            if ($j == 1){
                $pages [$j] = "./";
            }else{
                $pages [$j] = "list-{$j}.html";
            }
            
        }
        //当前页后面的分页
        
        for ($i=0; $i <= $pageAfter; $i++) { 
            $j = $info["currentPage"] + $i;
            if($j>$info["totalPages"]){
                $j = $j-$this->config["pagerCount"];
            }
            if($j<1){
                continue;
            }
            /*if ($j > $info["totalPages"]) {
                continue;
            }*/
            if ($j == $info["currentPage"]){
                continue;
            }
            if ($j == 1){
                $pages [$j] = "./";
            }else{
                $pages [$j] = "list-{$j}.html";
            }
        }
        ksort($pages);
        //最后一页
        $lastPage= "list-{$info["totalPages"]}.html";
        //上一页
        if($info["currentPage"]-1 == 1 ){
            $prePage = "./";
        }elseif($info["currentPage"]-1 > 1){
            $prePage = "list-".($info["currentPage"]-1).".html";
        }else{
            $prePage = false;
        }
        //下一页
        if($info["currentPage"] != $info["totalPages"] ){
            $nextPage = "list-".($info["currentPage"]+1).".html";
        }else{
            $nextPage = false;
        }
        return ["page"=>$pages,"last"=>$lastPage,"next"=>$nextPage,"pre"=>$prePage];
    }
    // 获取内页
    public function GetSingle()
    {
        $sql = 'select * from Content where ID = '.$this->type["id"].' and pub_time < '.time();
        if ($list = $this->getlist($sql)) {
            return $list;
        }else{
            Common::NotFound();
        }
    }
    // 获取随机文章
    public function GetRandPosts( $count = 20 ,$cate =Null )
    {
        if (!is_numeric($count)) {
            $cate = $count;
            $count = 20;
        }
        $getPostsFrom = !$cate ? $this->dbPath : WEBROOT."/data/".str_replace("-"," ",urldecode($cate)).".db";
        if ($cate) {
            $getPostsFrom = WEBROOT."/data/".str_replace("-"," ",urldecode($cate)).".db";
            parent::__construct($this->dbPath);
        }
        $sql = 'select * from Content order by random() limit '.$count.'where pub_time < '.time();
        if ($list = $this->getlist($sql)) {
            return $list;
        }else{
            Common::NotFound();
        }
    }
    // 获取附加的随机标题
    public function getSubtitle(Int $id = NULL)
    {
        if(is_numeric($id)){
            $subTitle = $this->getlist('select `title2` from Content where ID = '.$id)[0][0];
            return $subTitle;            
        }
        else{
            return "";
        }
    }
}
