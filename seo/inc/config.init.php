<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>初始化</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="https://g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
    <style>
    .buttons .button.active {background-color: #0894ec;color: #fff;z-index: 90;}
    .buttons .button{margin:1px}
    </style>
  </head>
  <body>
    <div class="page-group">
        <div class="page page-current" style="max-width: 760px;margin: 0 auto;">
        <header class="bar bar-nav">
          <h1 class='title'>网站初始化</h1>
        </header>
        <div class="content">
          <div class="list-block">
            <ul>
              <!-- Text inputs -->
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">网站名</div>
                    <div class="item-input">
                      <input id="webTitle" type="text" placeholder="首页标题">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">关键词</div>
                    <div class="item-input">
                      <input id="indexKeyword" type="text" placeholder="首页的keyword">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">描述</div>
                    <div class="item-input">
                      <input id="indexDescription" type="text" placeholder="首页的description">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">每页条数</div>
                    <div class="item-input">
                      <input id="postsNum" type="text" placeholder="列表每页显示的文章数量，默认20">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">模板</div>
                    <div class="item-input">
                      <input id="tempName" type="text" placeholder="点击下面选取一个模板">
                    </div>
                  </div>
                </div>
                <div class="content-block" style="margin:0">
                  <p class="buttons" style="margin:0;display: flex;flex-wrap: wrap;">
                    <?php 
                    if(is_array($tempList) && !empty($tempList)){
                      foreach ($tempList as $value){ 
                    ?>
                    <a href="#" class="button select-temp"><?=$value?></a>
                    <?php } 
                    }else{
                    ?>
                    请先模板文件放入<?=TMPPATH?>/模板名/目录中
                    <?php
                    }
                    ?>
                  </p>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">url包含标题</div>
                    <div class="item-input">
                      <label class="label-switch">
                        <input value="1" id="urlTitle" type="checkbox">
                        <div class="checkbox"></div>
                      </label>
                    </div>
                  </div>
                </div>
              </li>
              <li class="keyword-switch">
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">附加关键词</div>
                    <div class="item-input">
                      <label class="label-switch">
                        <input value="1" id="keywordFileSwitch" type="checkbox">
                        <div class="checkbox"></div>
                      </label>
                    </div>
                  </div>
                </div>
              </li>
              <li class="keyword-files">
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">附加文件</div>
                    <div class="item-input">
                      <input id="keywordFilesName" type="text" placeholder="附加关键词文件，点击下面选取，注意顺序">
                    </div>
                  </div>
                </div>
                <div class="content-block" style="margin:0">
                  <p class="buttons-row"  style="margin:0;">
                    <?php 
                    if(is_array($keyList) && !empty($keyList)){
                      foreach ($keyList as $value){
			                  if ($value == "replace.txt") continue; 
                    ?>
                    <a href="#" class="button select-key-files"><?=$value?></a>
                    <?php } 
                    }else{
                    ?>
                    请先将附加关键词文件放入<?=WEBROOT?>/data/目录中
                    <?php
                    }
                    ?>
                  </p>
                </div>
              </li>
              <div class="content-block" style="margin:0">
                <p style="margin-bottom: 0">设置各栏目的标题（不填则与数据库名相同）：</p>
              </div>
              <?php 
              if(is_array($dbList) && !empty($dbList)){
                foreach ($dbList as $value){ 
                  $value = str_replace(".db" , "" , $value);
              ?>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label db-cate-name"><?=$value?></div>
                    <div class="item-input">
                      <input class="cateTitle" type="text" data-value="<?=$value?>" placeholder="<?=$value?>">
                    </div>
                  </div>
                </div>
              </li>
              <?php } 
              }else{
              ?>
              请先将后缀为.db的数据库文件放入<?=WEBROOT?>/data/目录中
              <?php
              }
              ?>
            </ul>
          </div>
          <div class="content-block">
            <div class="row">
              <div class="col-50"><a id="submit" href="#" class="button button-big button-fill button-success">提交</a></div>
              <div class="col-50"><a href="#" class="button button-big button-fill button-danger">取消</a></div>
            </div>
          </div>
          <div>
            <ol>
              <li>首页关键词、描述不需要的话不填写即可</li>
              <li>附加文件：用于在标题后附加随机的关键词，需要注意顺序</li>
              <li>数据库文件名为栏目的url</li>
              <li>url包含标题：把标题添加到url里 不勾选为[id].html 勾选后为 id-key-word.html</li>
            </ol>
          </div>
        </div>
        </div>
    </div>

    <script type='text/javascript' src='https://g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='https://g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>
    <script>
      $(function () {
        // 显示/隐藏
        $(".keyword-files").hide();
        $(".keyword-switch .checkbox").click(function (e) { 
          if($("#keywordFileSwitch:checked").val()){
            $(".keyword-files").hide();
          }else{
            $(".keyword-files").show();
          }
        });
        //选取模板
        $(".select-temp").click(function (e) {
          e.preventDefault();
          $(this).addClass("active").siblings().removeClass("active");
          $("#tempName").val($.trim($(this).html()));
        });
        // 选取附加关键词文件
        $(".select-key-files").click(function (e) { 
          e.preventDefault();
          if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            var val = $("#keywordFilesName").val();
            val = val.replace($.trim($(this).html()),"");
            $("#keywordFilesName").val($.trim(val));
          }else{
            $(this).addClass("active");
            $("#keywordFilesName").val($.trim($("#keywordFilesName").val()+" "+$(this).html()));
          }
          
        });
        // 提交
        $("#submit").click(function (e) { 
          e.preventDefault();
          if($("#webTitle").val()==""){
            $.alert("必须有网站标题！");
            return false;
          }
          if($("#tempName").val()==""){
            $.alert("必须选择一个模板！");
            return false;
          }
          if($("#keywordFileSwitch:checked").val()!=undefined && $("#keywordFilesName").val()==""){
            $.alert("请输入文件名，多个文件用|分割");
            return false;
          }
          var data = {
            "webTitle":$("#webTitle").val(),
            "indexKeyword":$("#indexKeyword").val(),
            "indexDescription":$("#indexDescription").val(),
            "tempName":$("#tempName").val(),
            "keywordFilesName":$("#keywordFilesName").val()
          }
          $("#postsNum").val()==undefined || $("#postsNum").val()=="" ? false : data.postsNum = $("#postsNum").val();
          if($("#keywordFileSwitch:checked").val()){
            data.keywordFileSwitch=$("#keywordFileSwitch:checked").val();
          }
          if($("#urlTitle:checked").val()){
            data.urlTitle=$("#urlTitle:checked").val();
          }
          data.cateTitle = "";
          $(".cateTitle").each(function (index) {
            var val = $(this).val()=="" || $(this).val()==undefined ? $(this).attr("data-value") : $(this).val();
            if (data.cateTitle == "") {
              data.cateTitle = "'"+$(".db-cate-name").eq(index).html()+"' => '"+val+"'";
            }else{
              data.cateTitle = data.cateTitle + " , '"+$(".db-cate-name").eq(index).html()+"' => '"+val+"'";
            }
          });
          data.cateTitle = "[" + data.cateTitle + "]";
          $.post(location.href, data,
            function (data, textStatus, jqXHR) {
              if(data.status=="success"){
                $.alert(data.msg,function () {location.reload()});
              }else{
                $.alert(data.msg);
              }
              
            },
          );
        });
      });
    </script>
  </body>
</html>
