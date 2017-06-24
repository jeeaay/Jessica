# seo
## 标签
### 首页
首页标题
```php
<?=$this->config["webTitle"]?>
```
栏目列表（样式、html部分可以自行修改）
```php
<?php foreach($cont as $v) {?>
<li><a href="<?=$v['url']?>"><?=$v['cateTitle']?></a></li>
<?php }?>
```
### 栏目页
栏目标题
```php
<?=$title?>
```
栏目列表
```php
<?php foreach($list as $v ){?>
    #这里是遍历的内容
<?php }?>
```
列表的URL 

```php
#如果设置url包含关键词为关闭：
<?=$v['ID']?>.html
#如果设置url包含关键词为开启：
<?php echo $v['ID']."-".urlencode(str_replace(" ","-",$v['title']));?>.html
#自动选择：
<?=$v['ID']?><?php if($this->config['urlTitle']){echo "-".urlencode(str_replace(" ","-",$v['title']));}?>.html
```


