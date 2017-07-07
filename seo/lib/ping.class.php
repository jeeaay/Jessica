<?php
class Ping {
   
    public $method;
   
    function __construct($site_name, $site_url, $update_url, $update_rss = "") {
        $this->method = "
  <?xml version=\"1.0\" encoding=\"UTF-8\"?>
  <methodCall>
    <methodName>weblogUpdates.extendedPing</methodName>
    <params>
   <param><value>{$site_name}</value></param>
   <param><value>{$site_url}</value></param>
   <param><value>{$update_url}</value></param>
   <param><value>{$update_rss}</value></param>
    </params>
  </methodCall>";
        return $this->method;
    }
   
    private function _post($url, $postvar) {
        $ch = curl_init();
        $headers = array(
            "POST " . $url . " HTTP/1.0",
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Content-length: " . strlen($postvar)
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvar);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
   
    public function google() {
        return strpos($this->_post('http://blogsearch.google.com/ping/RPC2', $this->method), "<boolean>0</boolean>") ? true : false;
    }
   
    public function baidu() {
        return strpos($this->_post('http://ping.baidu.com/ping/RPC2', $this->method), "<int>0</int>") ? true : false;
    }
    // yandex格式错误 需要修改格式
    public function yandex() {
        return $this->_post('http://ping.blogs.yandex.ru/RPC2', $this->method);
    }
    
   
}