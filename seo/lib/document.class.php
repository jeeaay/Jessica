<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 11:30:13 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-23 13:41:14
 * 检查指定后缀名的文件是否存在
 * 列出所有指定后缀名的文件
 */
class Document{
    //需要查询的目录
    public $path="";
    //需要查询的后缀
    public $extension="";
    function __construct(String $path = "")
    {
        if(empty($path) || !is_dir($path)){
            exit("需要指定目录");
        }
        $this->path=$path;
    }
    public function AllList()
    {
        $allList = [];
        $list = scandir($this->path);
        foreach($list as $value){
            if (!preg_match("/^\.(.*)?/i" , $value)) {
                if(is_dir($this->path."/".$value)){
                    $allList["dirs"][] = $value;
                }else{
                    $allList["files"][] = $value;
                }
            }
        }
        return $allList;
    }
    public function AllFiles()
    {
        return $this->AllList()["files"];
    }
    public function AllDir()
    {
        return $this->AllList()["dirs"];
    }
    /*
    * 检查指定后缀名的文件是否存在
    * 列出所有指定后缀名的文件
    */
    public function Search(String $extension = "")
    {
        $files = [];
        $list = scandir($this->path);
        foreach($list as $value){
            if (!preg_match("/^\.(.*)?/i" , $value) && is_file($this->path."/".$value) && preg_match("/(.*)\.".$extension."/i",$value)) {
                $files[] = $value;
            }
        }
        return $files;
    }
    
}