<?php
if (isset($POST["num"])) {
    # code...
}else{
    $allDbs = json_encode(array_keys($config["cateTitle"])) ;
    require CMSPATH."/inc/sitemap.html";
}