<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/6/2018
 * Time: 18:11
 */
include("../code/wechatCallbackapi.php");

header('Content-type:text');
define("TOKEN", "weixin");

$GLOBALS["HTTP_RAW_POST_DATA"] = "<xml>
    <ToUserName><![CDATA[gh_6677c3eda143]]></ToUserName>
    <FromUserName><![CDATA[ojpX_jig-gyi3_Q9fHXQ4rdHniQs]]></FromUserName>
    <CreateTime>1525600524</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[天气 武汉]]></Content>
    <MsgId>1234567890abcdef</MsgId>
</xml>";

$wechatObj = new wechatCallbackapi();
if(isset($_GET['echostr'])){
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

?>