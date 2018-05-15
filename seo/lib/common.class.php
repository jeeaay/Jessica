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
    function __construct()
    {
        global $config;
        $this->config = $config;
    }
    //显示404错误，优先使用模板中的404页面
    public static function NotFound()
    {
        global $config;
        header("HTTP/1.1 404 Not Found");
        if (file_exists(TMPPATH."/".$config["tempName"]."/404.html")) {
            include TMPPATH."/".$config["tempName"]."/404.html";
        }else{
            include CMSPATH."/inc/404.php";
        }
        exit;
    }
    // 去除电话、日期、ip、邮箱、域名等
	public static function ResFilter($src) {
		$src = preg_replace ( '/\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', '', $src ); // email
		$src = preg_replace ( '/(http|https|ftp):\/\/[\w-]+\.[\w-]+\.[\w-]+\.(com|net|org|gov|cc|biz|info|cn|in|xyz|top|edu|la|cm|tm|co)/', '', $src ); // 去除域名http://es.aaa.com.cn
		$src = preg_replace ( '/(http|https|ftp):\/\/[\w-]+\.[\w-]+\.(com|net|org|gov|cc|biz|info|cn|in|xyz|top|edu|la|cm|tm|co)/', '', $src ); // 去除域名http://aaa.com.cn
		$src = preg_replace ( '/(http|https|ftp):\/\/[\w-]+\.(com|net|org|gov|cc|biz|info|cn|in|xyz|top|edu|la|cm|tm|co)/', '', $src ); // 去除域名http://aaa.com
		$src = preg_replace ( '/((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)/', '', $src ); // 192.168.1.1
		$src = preg_replace ( '/\d{3,}-\d{3,}-\d{6,}/', '', $src ); // 0086-021-778945
		$src = preg_replace ( '/\d{4,}-\d{2,}-\d{2,}/', '', $src ); // 1999-18-12
		$src = preg_replace ( '/\d{2,}-\d{8,14}/', '', $src ); // 0086-0214224555
		$src = preg_replace ( '/\d{1,}\/\d{1,}\/\d{1,}/i', '', $src ); // 1983/12/21
		$src = preg_replace ( '/\d{1,}:\d{1,}:\d{1,}/', '', $src ); // 19:12:53
		$src = preg_replace ( '/\d{1,}:\d{1,}/', '', $src ); // 19:12
		$src = preg_replace ( '/\d{6,}$/', '', $src ); // 139545648
		return self::TxtReplace($src);
	}
	//品牌词替换
	public static function TxtReplace($str){
		//$str=$this->resFilter($str);
		$changeTxt = file_get_contents ( WEBROOT."/data/replace.txt" );
		$arrChangeTxt = preg_split ( '/[\n]+/', trim ( $changeTxt ) );
		$arr_preg = "";
		foreach ( $arrChangeTxt as $value ) {
			$arr_preg = preg_split ( '/->/', trim ( $value ) );
			$arr_preg [0]=str_replace("/", "\/", $arr_preg [0]);
			$arr_preg [0]=str_replace("[", "\[", $arr_preg [0]);
			$arr_preg [0]=str_replace(".", "\.", $arr_preg [0]);
			$arr_preg [0]=str_replace("*", "\*", $arr_preg [0]);
			$arr_preg [0]=str_replace("+", "\+", $arr_preg [0]);
			$arr_preg [0]=str_replace("|", "\|", $arr_preg [0]);
			$arr_preg [0]="/\b".$arr_preg [0]."\b/i";
			$str = preg_replace ( $arr_preg [0], trim ( $arr_preg [1] ), $str );
		}
		return $str;
	}
	//生成随机密码
	public static function GeneratePassword( $length = 6 ) {
		$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		$password = '';
		for ( $i = 0; $i < $length; $i++ ) 
		{
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}

		return $password;
	}
	/*//根据目录名获取数据库地址
	public static function Dir2Db(String $dir)
	{
		str_replace(" ","-",$dir)
	}*/
}
