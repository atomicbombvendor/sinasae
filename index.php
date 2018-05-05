<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/5/2018
 * Time: 16:07
 */

header('Content-type:text');
define("TOKEN", "weixin");
traceHttp();
$wechatObj = new wechatCallbackapiTest();
echo "hello";
if(isset($_GET['echostr'])){
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid(){
        $echostr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echostr;
            exit;
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUserName = $postObj->FromUserName;
            $toUserName = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime><![CDATA[%s]]></CreateTime>
                         <MsgType><![CDATA[%s]]></MsgType>
                         <Content><![CDATA[%s]]></Content>
                         <FuncFlag>0</FuncFlag>
                         </xml>";

            if($keyword == "?" || $keyword == "?"){
                $msgType = "text";
                $content = date("Y-m-d H:i:s", time());
                $result = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $content);
                echo $result;
            }else{
                echo "";
                exit;
            }
        }
    }
}

function traceHttp(){
    $content = date('Y-m-d H:i:s')."\nREMOTE_ADDR:".$_SERVER["REMOTE_ADDR"]."\nQUERY_STRING:".$_SERVER["QUERY_STRING"]."\n\n";

    if(isset($_SERVER["HTTP_APPNAME"])){
        //SAE
        sae_set_display_errors(false);
        sae_debug(trim($content));
        sae_set_display_errors(true);
    }else{
        $max_size = 100000;
        $log_filename = "log.dat";
        if(file_exists($log_filename) and abs(filesize($log_filename)) > $max_size){
            unlink($log_filename);
        }
        file_put_contents($log_filename, $content, FILE_APPEND);
    }
}



?>