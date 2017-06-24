<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>准备初始化</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">

  </head>
  <body>
    <div class="page-group">
        <div class="page page-current" style="max-width: 760px;margin: 0 auto;">
        <header class="bar bar-nav">
          <h1 class='title'>准备初始化</h1>
        </header>
        <div class="content">
          <div class="content-block">
            <ol>
              <li>在网站根目录（<?=WEBROOT?>）创建目录data</li>
              <li>数据库文件（后缀必须是*.db）放入data目录中，一个数据库文件代表一个网站栏目，数据库的文件名即为栏目路径</li>
              <li>附加文件：用于在标题后附加随机关键词；多个文件用|分割；请将关键词文件放入data目录，txt格式，一行一个关键词</li>
              <li><a href="javascript:location.reload()">刷新本页面开始初始化</a></li>
            </ol>
          </div>
        </div>
        </div>
    </div>

    <script type='text/javascript' src='https://g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>
  </body>
</html>
