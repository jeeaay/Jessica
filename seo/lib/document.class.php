<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-23 11:30:13 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 13:23:23
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
        if( !is_dir($path)){
            exit("该参数需要需要指定一个目录");
        }
        $this->path=$path;
    }
    public function AllList(String $path = "")
    {
        if ($path=="") {
            $path =$this->path;
        }
        if( !is_dir($path)){
            exit("该参数需要需要指定一个目录");
        }
        $allList = [];
        $list = scandir($path);
        foreach($list as $value){
            if (!preg_match("/^\.(.*)?/i" , $value)) {
                if(is_dir($path."/".$value)){
                    $allList["dirs"][] = $value;
                }else{
                    $allList["files"][] = $value;
                }
            }
        }
        return $allList;
    }
    public function AllFiles(String $path = "")
    {
        if ($path=="") {
            $path =$this->path;
        }
        if( !is_dir($path)){
            exit("该参数需要需要指定一个目录");
        }
        return $this->AllList($path)["files"];
    }
    public function AllDir(String $path = "")
    {
        if ($path=="") {
            $path =$this->path;
        }
        if( !is_dir($path)){
            exit("该参数需要需要指定一个目录");
        }
        return $this->AllList($path)["dirs"];
    }
    /*
    * 检查指定后缀名的文件是否存在
    * 列出所有指定后缀名的文件
    */
    public function Search(String $extension = "",String $path = "")
    {
        if ($path=="") {
            $path =$this->path;
        }
        if( !is_dir($path)){
            exit("该参数需要需要指定一个目录");
        }
        $files = [];
        $list = scandir($path);
        foreach($list as $value){
            if (!preg_match("/^\.(.*)?/i" , $value) && is_file($path."/".$value) && preg_match("/(.*)\.".$extension."/i",$value)) {
                $files[] = $value;
            }
        }
        return $files;
    }
    
}