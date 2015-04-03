<?php 
define('GT_API_SERVER', 'http://api.geetest.com');
define('GT_SDK_VERSION', 'php_2.15.4.1.1');
class App_Geetest_Service{
    function register($publickey) {
        $this->challenge = $this->_send_request("/register.php", array("gt"=>$publickey));
        if (strlen($this->challenge) != 32) {
            return 0;
        }
        return 1;
    }

    function _send_request($path, $data, $method="GET") {
        if ($method=="GET") {
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'timeout'=>2,
                )
            );
            $context = stream_context_create($opts);
            $response = file_get_contents(GT_API_SERVER.$path."?".http_build_query($data), false, $context);

        }
        return $response;
    }

//验证条
    function get_widget($popupbtnid,$publickey,$product="popup") {
        $params = array(
            "gt" => $publickey,
            "challenge" => $this->challenge,
            "product" => $product,
        );
        if ($product == "popup") {
            $params["popupbtnid"] = $popupbtnid;
        }
        return '<div id="gt_popup_id"><script type="text/javascript" src="'.GT_API_SERVER.'/get.php?'.http_build_query($params).'"></script></div><style type="text/css">.gt_popup{z-index:100}</style>';
    }


public function geetest_validate($challenge,$validate,$seccode){
        if(strlen($validate) > 0 && $this->_check_result_by_private($challenge, $validate)){
        $query = http_build_query(array("seccode"=>$seccode,"sdk"=>GT_SDK_VERSION));
        $servervalidate = $this->_http_post('api.geetest.com', '/validate.php', $query);
            if (strlen($servervalidate) > 0 && $servervalidate == md5($seccode) || $servervalidate == "false") {
                return 1;
            }else if($servervalidate == "expired"){
                return 0;
            }
        }
        return -1;
    }
    public function _http_post($host,$path,$data,$port = 80){
        $http_request = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: " . strlen($data) . "\r\n";
        $http_request .= "\r\n";
        $http_request .= $data;
        $response = '';
        if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
            die ('Could not open socket! ' . $errstr);
        }       
        fwrite($fs, $http_request);
        while (!feof($fs))
            $response .= fgets($fs, 1160);
        fclose($fs);        
        $response = explode("\r\n\r\n", $response, 2);
        return $response[1];
    }
    public function _check_result_by_private($challenge, $validate) { 
        $config = Wekit::load('config.PwConfig')->getValues('geetest');
        if (md5($config['privatekey'].'geetest'.$challenge) != $validate) {
            return FALSE;
        }
        return TRUE;
    }
}

 ?>