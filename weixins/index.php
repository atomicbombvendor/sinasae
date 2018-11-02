<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/5/2018
 * Time: 16:07
 */

include("code/wechatCallbackapi.php");
header('Content-type:text');
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapi();
if(isset($_GET['echostr'])){
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

?>