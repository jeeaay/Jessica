<?php
header ( "Content-Type: text/xml; charset=utf-8" );
error_reporting(0);
ob_start();
$sitemapIndex="";
$siteMapStart= '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$siteMapEnd= "</urlset>";
$index_start='<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$index_End='</sitemapindex>';

$everySitemapPost=2000;//每个sitemap文章数量

$DB=new SQLite($dbName);
//读取分类
$catefile=$this->cateFile;
if (!file_exists($catefile)) notFount("Cate");
$CategoryArr=file($catefile);
$j=0;
foreach ($CategoryArr as $key => $value) {
	$categoryid=intval($key+1) ;
	$sql="select id from content where category={$categoryid}";
	$totalPost=$DB->RecordCount($sql);//总文章数
	$totalPage= ceil($totalPost/$everySitemapPost);//总sitemap数
	//sitemapindex创建开始
	for ($i=1; $i<=$totalPage; $i++) { 
		$j++;
		$cateUrl=urlencode(preg_replace("/[\s]+/i","-",trim($value)));
		$sitemapIndex.="<sitemap><loc>http://".$_SERVER ['HTTP_HOST']."/sitemap{$j}.xml</loc></sitemap>";
		
		//栏目sitemap创建开始
		$offset=($i-1)*$everySitemapPost;
		$sql="select id from content where category={$categoryid} limit {$everySitemapPost} offset {$offset}";
		$PostsArr=$DB->getlist($sql);
		$catesitemap="";
		foreach ($PostsArr as $post) {
			$catesitemap.="<url><loc>http://{$_SERVER ['HTTP_HOST']}/{$cateUrl}/{$post[0]}.html</loc></url>";
		}

		$catesitemap=$siteMapStart.$catesitemap.$siteMapEnd;
		/*$handle=fopen(WEBROOT."/sitemap".$j.".xml", "w");
		fwrite($handle,$catesitemap );
		fclose($handle);*/
	}
}

$sitemapIndex=$index_start.$sitemapIndex.$index_End;
/*$handle=fopen(WEBROOT."/sitemap.xml", "w");
fwrite($handle,$sitemapIndex );
fclose($handle);
ob_end_flush();*/
echo $sitemapIndex;